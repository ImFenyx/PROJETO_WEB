import type { Cidade, Continente, Governante, Pais } from './types'

async function http<T>(path: string, init?: RequestInit): Promise<T> {
  const response = await fetch(`/api${path}`, {
    method: init?.method,
    headers: { 'Content-Type': 'application/json', ...(init?.headers ?? {}) },
    body: init?.body,
  })

  if (!response.ok) {
    let message = `Erro ${response.status}`
    try {
      const body = (await response.json()) as { error?: string }
      if (body?.error) message = body.error
    } catch {
      // mantém a mensagem padrão
    }
    throw new Error(message)
  }

  if (response.status === 204) return undefined as T
  return (await response.json()) as T
}

export type CrudApi<T extends { id: number }> = {
  list: (search?: string) => Promise<T[]>
  create: (data: Record<string, unknown>) => Promise<T>
  update: (id: number, data: Record<string, unknown>) => Promise<T>
  remove: (id: number) => Promise<void>
}

function crud<T extends { id: number }>(resource: string): CrudApi<T> {
  return {
    list: (search = '') =>
      http<T[]>(`/${resource}${search ? `?q=${encodeURIComponent(search)}` : ''}`),
    create: (data) => http<T>(`/${resource}`, { method: 'POST', body: JSON.stringify(data) }),
    update: (id, data) =>
      http<T>(`/${resource}/${id}`, { method: 'PUT', body: JSON.stringify(data) }),
    remove: (id) => http<void>(`/${resource}/${id}`, { method: 'DELETE' }),
  }
}

export type SelectOption = { value: string; label: string }

/** Carrega as opções de um select a partir de outra entidade. */
export async function loadOptions(resource: string): Promise<SelectOption[]> {
  const rows = await http<{ id: number; nome: string }[]>(`/${resource}`)
  return rows.map((row) => ({ value: String(row.id), label: row.nome }))
}

export const paisesApi = crud<Pais>('paises')
export const cidadesApi = crud<Cidade>('cidades')
export const continentesApi = crud<Continente>('continentes')
export const governantesApi = crud<Governante>('governantes')
