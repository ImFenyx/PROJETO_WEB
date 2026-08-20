import type { EntityConfig, Store } from './types'

/** Bloqueia a exclusão quando há registros vinculados em outras tabelas. */
async function guard(
  store: Store,
  checks: { table: string; column: string }[],
  id: number,
): Promise<string | null> {
  for (const check of checks) {
    const total = await store.count(check.table, check.column, id)
    if (total > 0) {
      return `Não é possível excluir: há ${total} registro(s) vinculado(s) em "${check.table}".`
    }
  }
  return null
}

export const continentes: EntityConfig = {
  table: 'continentes',
  fields: [
    { key: 'nome', type: 'string' },
    { key: 'populacao', type: 'number' },
    { key: 'area_km2', type: 'number' },
    { key: 'total_paises', type: 'number' },
  ],
  nameRefs: [],
  searchCols: ['nome'],
  deleteGuard: (store, id) => guard(store, [{ table: 'paises', column: 'continente_id' }], id),
}

export const governantes: EntityConfig = {
  table: 'governantes',
  fields: [
    { key: 'nome', type: 'string' },
    { key: 'partido_politico', type: 'string' },
    { key: 'data_nascimento', type: 'date' },
    { key: 'data_inicio_mandato', type: 'date' },
    { key: 'data_fim_mandato', type: 'date' },
  ],
  nameRefs: [],
  searchCols: ['nome', 'partido_politico'],
  compute: (row) => {
    const data = row.data_nascimento
    if (typeof data === 'string' && data.length > 0) {
      const nascimento = new Date(`${data}T00:00:00`).getTime()
      if (Number.isFinite(nascimento)) {
        const anos = Math.floor((Date.now() - nascimento) / (365.25 * 24 * 60 * 60 * 1000))
        row.idade = anos >= 0 ? anos : null
        return
      }
    }
    row.idade = null
  },
  deleteGuard: (store, id) =>
    guard(
      store,
      [
        { table: 'paises', column: 'governante_id' },
        { table: 'cidades', column: 'governante_id' },
      ],
      id,
    ),
}

export const paises: EntityConfig = {
  table: 'paises',
  fields: [
    { key: 'nome', type: 'string' },
    { key: 'continente_id', type: 'number' },
    { key: 'populacao', type: 'number' },
    { key: 'area_km2', type: 'number' },
    { key: 'idioma', type: 'string' },
    { key: 'governante_id', type: 'number' },
    { key: 'clima', type: 'string' },
    { key: 'regime_politico', type: 'string' },
    { key: 'moeda', type: 'string' },
  ],
  nameRefs: [
    { fk: 'continente_id', table: 'continentes', as: 'continente_nome' },
    { fk: 'governante_id', table: 'governantes', as: 'governante_nome' },
  ],
  searchCols: ['nome', 'idioma', 'clima', 'regime_politico', 'moeda'],
  deleteGuard: (store, id) => guard(store, [{ table: 'cidades', column: 'pais_id' }], id),
}

export const cidades: EntityConfig = {
  table: 'cidades',
  fields: [
    { key: 'nome', type: 'string' },
    { key: 'pais_id', type: 'number' },
    { key: 'populacao', type: 'number' },
    { key: 'area_km2', type: 'number' },
    { key: 'governante_id', type: 'number' },
    { key: 'clima', type: 'string' },
    { key: 'data_fundacao', type: 'date' },
  ],
  nameRefs: [
    { fk: 'pais_id', table: 'paises', as: 'pais_nome' },
    { fk: 'governante_id', table: 'governantes', as: 'governante_nome' },
  ],
  searchCols: ['nome', 'clima'],
}

export const entities = { continentes, governantes, paises, cidades }
