import CrudPage from '../components/CrudPage'
import type { ColumnConfig, FieldConfig } from '../components/CrudPage'
import { governantesApi } from '../lib/api'
import { formatDate, formatNumber } from '../lib/format'
import type { Governante } from '../lib/types'

const columns: ColumnConfig<Governante>[] = [
  { key: 'id', header: 'ID' },
  { key: 'nome', header: 'Nome', render: (row) => <span className="font-medium">{row.nome}</span> },
  { key: 'partido_politico', header: 'Partido' },
  { key: 'idade', header: 'Idade', render: (row) => formatNumber(row.idade) },
  { key: 'data_nascimento', header: 'Nascimento', render: (row) => formatDate(row.data_nascimento) },
  { key: 'data_inicio_mandato', header: 'Início do Mandato', render: (row) => formatDate(row.data_inicio_mandato) },
  { key: 'data_fim_mandato', header: 'Fim do Mandato', render: (row) => formatDate(row.data_fim_mandato) },
]

const fields: FieldConfig[] = [
  { key: 'nome', label: 'Nome', type: 'text', required: true },
  { key: 'partido_politico', label: 'Partido Político', type: 'text' },
  { key: 'data_nascimento', label: 'Data de Nascimento', type: 'date' },
  { key: 'data_inicio_mandato', label: 'Início do Mandato', type: 'date' },
  { key: 'data_fim_mandato', label: 'Fim do Mandato', type: 'date' },
]

function Governantes() {
  return (
    <CrudPage<Governante>
      emoji="👥"
      title="Governantes"
      columns={columns}
      fields={fields}
      api={governantesApi}
      searchPlaceholder="Buscar governante..."
      entityLabel="governante"
    />
  )
}

export default Governantes
