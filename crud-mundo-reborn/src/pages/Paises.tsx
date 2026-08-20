import CrudPage from '../components/CrudPage'
import type { ColumnConfig, FieldConfig } from '../components/CrudPage'
import { loadOptions, paisesApi } from '../lib/api'
import { formatArea, formatNumber } from '../lib/format'
import type { Pais } from '../lib/types'

const columns: ColumnConfig<Pais>[] = [
  { key: 'id', header: 'ID' },
  { key: 'nome', header: 'Nome', render: (row) => <span className="font-medium">{row.nome}</span> },
  { key: 'continente_nome', header: 'Continente' },
  { key: 'governante_nome', header: 'Governante' },
  { key: 'populacao', header: 'População', render: (row) => formatNumber(row.populacao) },
  { key: 'area_km2', header: 'Área (km²)', render: (row) => formatArea(row.area_km2) },
  { key: 'idioma', header: 'Idioma' },
  { key: 'clima', header: 'Clima' },
  { key: 'regime_politico', header: 'Regime' },
  { key: 'moeda', header: 'Moeda' },
]

const fields: FieldConfig[] = [
  { key: 'nome', label: 'Nome', type: 'text', required: true },
  { key: 'continente_id', label: 'Continente', type: 'select', loadOptions: () => loadOptions('continentes') },
  { key: 'governante_id', label: 'Governante', type: 'select', loadOptions: () => loadOptions('governantes') },
  { key: 'populacao', label: 'População', type: 'number', min: 0 },
  { key: 'area_km2', label: 'Área (km²)', type: 'number', min: 0, step: '0.01' },
  { key: 'idioma', label: 'Idioma', type: 'text' },
  { key: 'clima', label: 'Clima', type: 'text' },
  { key: 'regime_politico', label: 'Regime Político', type: 'text' },
  { key: 'moeda', label: 'Moeda', type: 'text' },
]

function Paises() {
  return (
    <CrudPage<Pais>
      emoji="🏳️"
      title="Países"
      columns={columns}
      fields={fields}
      api={paisesApi}
      searchPlaceholder="Buscar país..."
      entityLabel="país"
    />
  )
}

export default Paises
