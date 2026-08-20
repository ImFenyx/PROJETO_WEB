import type { EntityConfig, Row, Store } from './types'

/**
 * Armazenamento em memória usado como fallback quando o MySQL não está
 * disponível (ex.: no preview). Permite testar o CRUD completo com dados
 * de exemplo sem depender de um banco de dados.
 */
export class MemoryStore implements Store {
  kind = 'memory' as const

  private tables: Record<string, Row[]> = {}
  private sequence: Record<string, number> = {}

  seed(table: string, rows: Row[]) {
    this.tables[table] = rows.map((row) => ({ ...row }))
    this.sequence[table] = rows.reduce((max, row) => Math.max(max, Number(row.id) || 0), 0)
  }

  private rows(table: string): Row[] {
    return this.tables[table] ?? []
  }

  async list(entity: EntityConfig, search: string): Promise<Row[]> {
    const query = search.trim().toLowerCase()
    let result = this.rows(entity.table).map((row) => ({ ...row }))
    if (query) {
      result = result.filter((row) =>
        entity.searchCols.some((col) => String(row[col] ?? '').toLowerCase().includes(query)),
      )
    }
    result.sort((a, b) =>
      String(a.nome ?? '').localeCompare(String(b.nome ?? ''), 'pt-BR', { sensitivity: 'base' }),
    )
    return result
  }

  async get(table: string, id: number): Promise<Row | null> {
    const row = this.rows(table).find((r) => Number(r.id) === id)
    return row ? { ...row } : null
  }

  async insert(table: string, data: Row): Promise<number> {
    const id = (this.sequence[table] = (this.sequence[table] ?? 0) + 1)
    this.tables[table] = [...this.rows(table), { id, ...data }]
    return id
  }

  async update(table: string, id: number, data: Row): Promise<boolean> {
    const exists = this.rows(table).some((r) => Number(r.id) === id)
    if (!exists) return false
    this.tables[table] = this.rows(table).map((r) => (Number(r.id) === id ? { ...r, ...data, id } : r))
    return true
  }

  async remove(table: string, id: number): Promise<boolean> {
    const before = this.rows(table).length
    this.tables[table] = this.rows(table).filter((r) => Number(r.id) !== id)
    return this.rows(table).length < before
  }

  async count(table: string, column: string, value: number): Promise<number> {
    return this.rows(table).filter((r) => Number(r[column]) === value).length
  }

  async pickNames(table: string): Promise<Map<number, string>> {
    const map = new Map<number, string>()
    for (const row of this.rows(table)) map.set(Number(row.id), String(row.nome ?? ''))
    return map
  }
}

/** Dados de exemplo usados pelo armazenamento em memória. */
export function seedData(store: MemoryStore) {
  store.seed('continentes', [
    { id: 1, nome: 'América do Sul', populacao: 434000000, area_km2: 17840000, total_paises: 12 },
    { id: 2, nome: 'América do Norte', populacao: 592000000, area_km2: 24709000, total_paises: 23 },
    { id: 3, nome: 'Europa', populacao: 746000000, area_km2: 10180000, total_paises: 50 },
    { id: 4, nome: 'Ásia', populacao: 4700000000, area_km2: 44579000, total_paises: 49 },
    { id: 5, nome: 'África', populacao: 1400000000, area_km2: 30370000, total_paises: 54 },
    { id: 6, nome: 'Oceania', populacao: 43000000, area_km2: 8526000, total_paises: 14 },
  ])

  store.seed('governantes', [
    {
      id: 1,
      nome: 'Luiz Inácio Lula da Silva',
      partido_politico: 'PT',
      data_nascimento: '1945-10-27',
      data_inicio_mandato: '2023-01-01',
      data_fim_mandato: null,
    },
    {
      id: 2,
      nome: 'Javier Milei',
      partido_politico: 'La Libertad Avanza',
      data_nascimento: '1970-10-22',
      data_inicio_mandato: '2023-12-10',
      data_fim_mandato: null,
    },
    {
      id: 3,
      nome: 'Emmanuel Macron',
      partido_politico: 'Renaissance',
      data_nascimento: '1977-12-21',
      data_inicio_mandato: '2017-05-14',
      data_fim_mandato: null,
    },
    {
      id: 4,
      nome: 'Xi Jinping',
      partido_politico: 'Partido Comunista Chinês',
      data_nascimento: '1953-06-15',
      data_inicio_mandato: '2013-03-14',
      data_fim_mandato: null,
    },
    {
      id: 5,
      nome: 'Cyril Ramaphosa',
      partido_politico: 'ANC',
      data_nascimento: '1952-11-17',
      data_inicio_mandato: '2018-02-15',
      data_fim_mandato: null,
    },
    {
      id: 6,
      nome: 'Anthony Albanese',
      partido_politico: 'Partido Trabalhista',
      data_nascimento: '1963-03-02',
      data_inicio_mandato: '2022-05-23',
      data_fim_mandato: null,
    },
  ])

  store.seed('paises', [
    {
      id: 1,
      nome: 'Brasil',
      continente_id: 1,
      populacao: 203000000,
      area_km2: 8515767,
      idioma: 'Português',
      governante_id: 1,
      clima: 'Tropical',
      regime_politico: 'República Presidencialista',
      moeda: 'Real (BRL)',
    },
    {
      id: 2,
      nome: 'Argentina',
      continente_id: 1,
      populacao: 46000000,
      area_km2: 2780400,
      idioma: 'Espanhol',
      governante_id: 2,
      clima: 'Temperado',
      regime_politico: 'República Presidencialista',
      moeda: 'Peso Argentino (ARS)',
    },
    {
      id: 3,
      nome: 'França',
      continente_id: 3,
      populacao: 68000000,
      area_km2: 643801,
      idioma: 'Francês',
      governante_id: 3,
      clima: 'Temperado',
      regime_politico: 'República Semipresidencialista',
      moeda: 'Euro (EUR)',
    },
    {
      id: 4,
      nome: 'China',
      continente_id: 4,
      populacao: 1410000000,
      area_km2: 9596961,
      idioma: 'Mandarim',
      governante_id: 4,
      clima: 'Variado',
      regime_politico: 'República Socialista',
      moeda: 'Yuan (CNY)',
    },
    {
      id: 5,
      nome: 'África do Sul',
      continente_id: 5,
      populacao: 62000000,
      area_km2: 1221037,
      idioma: '11 idiomas oficiais',
      governante_id: 5,
      clima: 'Variado',
      regime_politico: 'República Parlamentarista',
      moeda: 'Rand (ZAR)',
    },
    {
      id: 6,
      nome: 'Austrália',
      continente_id: 6,
      populacao: 26000000,
      area_km2: 7692024,
      idioma: 'Inglês',
      governante_id: 6,
      clima: 'Desértico/Temperado',
      regime_politico: 'Monarquia Constitucional',
      moeda: 'Dólar Australiano (AUD)',
    },
  ])

  store.seed('cidades', [
    {
      id: 1,
      nome: 'São Paulo',
      pais_id: 1,
      populacao: 11450000,
      area_km2: 1521,
      governante_id: null,
      clima: 'Subtropical',
      data_fundacao: '1554-01-25',
    },
    {
      id: 2,
      nome: 'Rio de Janeiro',
      pais_id: 1,
      populacao: 6211000,
      area_km2: 1200,
      governante_id: null,
      clima: 'Tropical',
      data_fundacao: '1565-03-01',
    },
    {
      id: 3,
      nome: 'Buenos Aires',
      pais_id: 2,
      populacao: 2890000,
      area_km2: 203,
      governante_id: null,
      clima: 'Temperado',
      data_fundacao: '1536-02-02',
    },
    {
      id: 4,
      nome: 'Paris',
      pais_id: 3,
      populacao: 2140000,
      area_km2: 105,
      governante_id: null,
      clima: 'Temperado',
      data_fundacao: null,
    },
    {
      id: 5,
      nome: 'Pequim',
      pais_id: 4,
      populacao: 21890000,
      area_km2: 16410,
      governante_id: null,
      clima: 'Continental',
      data_fundacao: null,
    },
    {
      id: 6,
      nome: 'Cidade do Cabo',
      pais_id: 5,
      populacao: 433000,
      area_km2: 2445,
      governante_id: null,
      clima: 'Mediterrâneo',
      data_fundacao: '1652-04-06',
    },
    {
      id: 7,
      nome: 'Sydney',
      pais_id: 6,
      populacao: 5310000,
      area_km2: 12368,
      governante_id: null,
      clima: 'Subtropical',
      data_fundacao: '1788-01-26',
    },
  ])
}
