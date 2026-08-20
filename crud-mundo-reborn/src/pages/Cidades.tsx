import CrudPage from '../components/CrudPage'
import type { ColumnConfig, FieldConfig } from '../components/CrudPage'
import { cidadesApi, loadOptions } from '../lib/api'
import { formatArea, formatDate, formatNumber } from '../lib/format'
import type { Cidade } from '../lib/types'

const columns: ColumnConfig<Cidade>[] = [
  { key: 'id', header: 'ID' },
  { key: 'nome', header: 'Nome', render: (row) => <span className="font-medium">{row.nome}</span> },
  { key: 'pais_nome', header: 'País' },
  { key: 'governante_nome', header: 'Governante' },
  { key: 'populacao', header: 'População', render: (row) => formatNumber(row.populacao) },
  { key: 'area_km2', header: 'Área (km²)', render: (row) => formatArea(row.area_km2) },
  { key: 'clima', header: 'Clima' },
  { key: 'data_fundacao', header: 'Fundação', render: (row) => formatDate(row.data_fundacao) },
]

const fields: FieldConfig[] = [
  { key: 'nome', label: 'Nome', type: 'text', required: true },
  { key: 'pais_id', label: 'País', type: 'select', loadOptions: () => loadOptions('paises') },
  { key: 'governante_id', label: 'Governante', type: 'select', loadOptions: () => loadOptions('governantes') },
  { key: 'populacao', label: 'População', type: 'number', min: 0 },
  { key: 'area_km2', label: 'Área (km²)', type: 'number', min: 0, step: '0.01' },
  { key: 'clima', label: 'Clima', type: 'text' },
  { key: 'data_fundacao', label: 'Data de Fundação', type: 'date' },
]

function Cidades() {
  return (
    <CrudPage<Cidade>
      emoji="🏙️"
      title="Cidades"
      columns={columns}
      fields={fields}
      api={cidadesApi}
      searchPlaceholder="Buscar cidade..."
      entityLabel="cidade"
    />
  )
}

export default Cidades
