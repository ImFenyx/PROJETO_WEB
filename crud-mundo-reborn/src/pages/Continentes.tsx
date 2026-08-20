import CrudPage from '../components/CrudPage'
import type { ColumnConfig, FieldConfig } from '../components/CrudPage'
import { continentesApi } from '../lib/api'
import { formatArea, formatNumber } from '../lib/format'
import type { Continente } from '../lib/types'

const columns: ColumnConfig<Continente>[] = [
  { key: 'id', header: 'ID' },
  { key: 'nome', header: 'Nome', render: (row) => <span className="font-medium">{row.nome}</span> },
  { key: 'populacao', header: 'População', render: (row) => formatNumber(row.populacao) },
  { key: 'area_km2', header: 'Área (km²)', render: (row) => formatArea(row.area_km2) },
  { key: 'total_paises', header: 'Total de Países', render: (row) => formatNumber(row.total_paises) },
]

const fields: FieldConfig[] = [
  { key: 'nome', label: 'Nome', type: 'text', required: true },
  { key: 'populacao', label: 'População', type: 'number', min: 0 },
  { key: 'area_km2', label: 'Área (km²)', type: 'number', min: 0, step: '0.01' },
  { key: 'total_paises', label: 'Total de Países', type: 'number', min: 0 },
]

function Continentes() {
  return (
    <CrudPage<Continente>
      emoji="🌍"
      title="Continentes"
      columns={columns}
      fields={fields}
      api={continentesApi}
      searchPlaceholder="Buscar continente..."
      entityLabel="continente"
    />
  )
}

export default Continentes
