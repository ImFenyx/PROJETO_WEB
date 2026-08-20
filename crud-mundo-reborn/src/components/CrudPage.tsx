import { useCallback, useEffect, useState } from 'react'
import type { FormEvent, ReactNode } from 'react'
import type { CrudApi, SelectOption } from '../lib/api'
import PageHeader from './PageHeader'
import Modal from './Modal'

export type FieldConfig = {
  key: string
  label: string
  type: 'text' | 'number' | 'date' | 'select'
  required?: boolean
  placeholder?: string
  min?: number
  step?: string
  options?: SelectOption[]
  loadOptions?: () => Promise<SelectOption[]>
}

export type ColumnConfig<T> = {
  key: string
  header: string
  render?: (row: T) => ReactNode
}

type CrudPageProps<T extends { id: number }> = {
  emoji?: string
  title: string
  columns: ColumnConfig<T>[]
  fields: FieldConfig[]
  api: CrudApi<T>
  searchPlaceholder: string
  entityLabel: string
}

function defaultCell(value: unknown): string {
  if (value === null || value === undefined || value === '') return '—'
  return String(value)
}

function CrudPage<T extends { id: number }>({
  emoji,
  title,
  columns,
  fields,
  api,
  searchPlaceholder,
  entityLabel,
}: CrudPageProps<T>) {
  const [rows, setRows] = useState<T[]>([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState<string | null>(null)
  const [search, setSearch] = useState('')

  const [modalOpen, setModalOpen] = useState(false)
  const [editing, setEditing] = useState<T | null>(null)
  const [form, setForm] = useState<Record<string, string>>({})
  const [options, setOptions] = useState<Record<string, SelectOption[]>>({})
  const [saving, setSaving] = useState(false)
  const [formError, setFormError] = useState<string | null>(null)

  const load = useCallback(
    async (query: string) => {
      setLoading(true)
      setError(null)
      try {
        setRows(await api.list(query))
      } catch (err) {
        setError(err instanceof Error ? err.message : 'Erro ao carregar os dados.')
      } finally {
        setLoading(false)
      }
    },
    [api],
  )

  useEffect(() => {
    const timer = setTimeout(() => {
      void load(search)
    }, 250)
    return () => clearTimeout(timer)
  }, [search, load])

  const emptyForm = useCallback(() => {
    const next: Record<string, string> = {}
    for (const field of fields) next[field.key] = ''
    return next
  }, [fields])

  const resolveOptions = useCallback(
    async (row: T | null) => {
      const result: Record<string, SelectOption[]> = {}
      await Promise.all(
        fields
          .filter((field) => field.type === 'select')
          .map(async (field) => {
            let list = field.options ?? []
            if (field.loadOptions) list = await field.loadOptions()
            if (row) {
              const record = row as Record<string, unknown>
              const current = record[field.key]
              if (current !== null && current !== undefined && current !== '') {
                const value = String(current)
                if (!list.some((option) => option.value === value)) {
                  const nameKey = field.key.replace(/_id$/, '_nome')
                  const label = String(record[nameKey] ?? `#${value}`)
                  list = [{ value, label }, ...list]
                }
              }
            }
            result[field.key] = list
          }),
      )
      setOptions(result)
    },
    [fields],
  )

  const openCreate = useCallback(async () => {
    setEditing(null)
    setForm(emptyForm())
    setFormError(null)
    await resolveOptions(null)
    setModalOpen(true)
  }, [emptyForm, resolveOptions])

  const openEdit = useCallback(
    async (row: T) => {
      setEditing(row)
      const record = row as Record<string, unknown>
      const next: Record<string, string> = {}
      for (const field of fields) {
        const value = record[field.key]
        next[field.key] = value === null || value === undefined ? '' : String(value)
      }
      setForm(next)
      setFormError(null)
      await resolveOptions(row)
      setModalOpen(true)
    },
    [fields, resolveOptions],
  )

  const closeModal = useCallback(() => {
    setModalOpen(false)
    setEditing(null)
    setFormError(null)
  }, [])

  const submit = useCallback(
    async (event: FormEvent) => {
      event.preventDefault()
      setSaving(true)
      setFormError(null)
      try {
        if (editing) await api.update(editing.id, form)
        else await api.create(form)
        setModalOpen(false)
        setEditing(null)
        await load(search)
      } catch (err) {
        setFormError(err instanceof Error ? err.message : 'Erro ao salvar.')
      } finally {
        setSaving(false)
      }
    },
    [api, editing, form, load, search],
  )

  const removeRow = useCallback(
    async (row: T) => {
      const record = row as Record<string, unknown>
      const name = String(record.nome ?? row.id)
      if (!window.confirm(`Excluir ${entityLabel} "${name}"?`)) return
      try {
        await api.remove(row.id)
        await load(search)
      } catch (err) {
        window.alert(err instanceof Error ? err.message : 'Erro ao excluir.')
      }
    },
    [api, entityLabel, load, search],
  )

  const setField = useCallback((key: string, value: string) => {
    setForm((previous) => ({ ...previous, [key]: value }))
  }, [])

  return (
    <div className="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 lg:px-8 space-y-6">
      <PageHeader emoji={emoji} title={title} />

      <div className="rounded-2xl border border-surface1 bg-surface/70 backdrop-blur-sm">
        <div className="flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">
          <input
            type="text"
            value={search}
            onChange={(event) => setSearch(event.target.value)}
            placeholder={searchPlaceholder}
            className="w-full sm:max-w-md rounded-xl border border-surface1 bg-background px-4 py-2.5 text-text placeholder-overlay0 focus:outline-none focus:border-primary transition-colors"
          />
          <button
            type="button"
            onClick={() => void openCreate()}
            className="bg-primary text-background font-semibold px-5 py-2.5 rounded-xl hover:brightness-110 active:scale-[0.98] transition-all shrink-0"
          >
            + Novo
          </button>
        </div>

        {error && (
          <div className="mx-6 mb-4 rounded-xl border border-danger/40 bg-danger/10 px-4 py-3 text-danger text-sm">
            {error}
          </div>
        )}

        <div className="overflow-x-auto">
          <table className="w-full text-sm text-left">
            <thead>
              <tr className="text-subtext border-b border-surface1">
                {columns.map((column) => (
                  <th key={column.key} className="py-3 px-4 font-medium whitespace-nowrap">
                    {column.header}
                  </th>
                ))}
                <th className="py-3 px-4 font-medium whitespace-nowrap">Ações</th>
              </tr>
            </thead>
            <tbody>
              {loading ? (
                <tr>
                  <td
                    colSpan={columns.length + 1}
                    className="py-10 text-center text-subtext"
                  >
                    Carregando...
                  </td>
                </tr>
              ) : rows.length === 0 ? (
                <tr>
                  <td
                    colSpan={columns.length + 1}
                    className="py-10 text-center text-subtext"
                  >
                    Nenhum registro encontrado.
                  </td>
                </tr>
              ) : (
                rows.map((row) => (
                  <tr
                    key={row.id}
                    className="border-b border-surface1 hover:bg-surface/50 transition-colors"
                  >
                    {columns.map((column) => (
                      <td key={column.key} className="py-3 px-4">
                        {column.render
                          ? column.render(row)
                          : defaultCell((row as Record<string, unknown>)[column.key])}
                      </td>
                    ))}
                    <td className="py-3 px-4">
                      <div className="flex gap-3 whitespace-nowrap">
                        <button
                          type="button"
                          onClick={() => void openEdit(row)}
                          className="text-info hover:brightness-125 transition-colors text-sm"
                        >
                          Editar
                        </button>
                        <button
                          type="button"
                          onClick={() => void removeRow(row)}
                          className="text-danger hover:brightness-125 transition-colors text-sm"
                        >
                          Excluir
                        </button>
                      </div>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </div>

      {modalOpen && (
        <Modal
          title={editing ? `Editar ${entityLabel}` : `Novo ${entityLabel}`}
          onClose={closeModal}
        >
          <form onSubmit={submit} className="space-y-4">
            <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
              {fields.map((field) => {
                const baseClass =
                  'w-full rounded-xl border border-surface1 bg-background px-4 py-2.5 text-text placeholder-overlay0 focus:outline-none focus:border-primary transition-colors'

                if (field.type === 'select') {
                  return (
                    <div key={field.key}>
                      <label className="block text-sm text-subtext mb-1">
                        {field.label}
                        {field.required && <span className="text-danger"> *</span>}
                      </label>
                      <select
                        value={form[field.key] ?? ''}
                        onChange={(event) => setField(field.key, event.target.value)}
                        className={baseClass}
                      >
                        <option value="">Selecione...</option>
                        {(options[field.key] ?? []).map((option) => (
                          <option key={option.value} value={option.value}>
                            {option.label}
                          </option>
                        ))}
                      </select>
                    </div>
                  )
                }

                return (
                  <div key={field.key}>
                    <label className="block text-sm text-subtext mb-1">
                      {field.label}
                      {field.required && <span className="text-danger"> *</span>}
                    </label>
                    <input
                      type={field.type}
                      value={form[field.key] ?? ''}
                      min={field.min}
                      step={field.step}
                      required={field.required}
                      placeholder={field.placeholder}
                      onChange={(event) => setField(field.key, event.target.value)}
                      className={baseClass}
                    />
                  </div>
                )
              })}
            </div>

            {formError && (
              <div className="rounded-xl border border-danger/40 bg-danger/10 px-4 py-3 text-danger text-sm">
                {formError}
              </div>
            )}

            <div className="flex gap-3 pt-2">
              <button
                type="submit"
                disabled={saving}
                className="bg-primary text-background font-semibold px-5 py-2.5 rounded-xl hover:brightness-110 active:scale-[0.98] transition-all disabled:opacity-50 disabled:cursor-not-allowed"
              >
                {saving ? 'Salvando...' : editing ? 'Atualizar' : 'Cadastrar'}
              </button>
              <button
                type="button"
                onClick={closeModal}
                className="bg-surface1 text-text font-semibold px-5 py-2.5 rounded-xl hover:brightness-110 active:scale-[0.98] transition-all"
              >
                Cancelar
              </button>
            </div>
          </form>
        </Modal>
      )}
    </div>
  )
}

export default CrudPage
