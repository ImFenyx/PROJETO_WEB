export type Row = Record<string, unknown>

export type FieldType = 'string' | 'number' | 'date'

export type Field = {
  key: string
  type: FieldType
}

export type NameRef = {
  /** Coluna da chave estrangeira (ex.: 'continente_id'). */
  fk: string
  /** Tabela referenciada (ex.: 'continentes'). */
  table: string
  /** Nome da coluna gerada com o nome do registro (ex.: 'continente_nome'). */
  as: string
}

export type EntityConfig = {
  table: string
  fields: Field[]
  nameRefs: NameRef[]
  searchCols: string[]
  /** Campos calculados (ex.: idade do governante). */
  compute?: (row: Row) => void
  /** Retorna uma mensagem de erro caso a exclusão deva ser bloqueada. */
  deleteGuard?: (store: Store, id: number) => Promise<string | null>
}

export type Store = {
  kind: 'mysql' | 'memory'
  list: (entity: EntityConfig, search: string) => Promise<Row[]>
  get: (table: string, id: number) => Promise<Row | null>
  insert: (table: string, data: Row) => Promise<number>
  update: (table: string, id: number, data: Row) => Promise<boolean>
  remove: (table: string, id: number) => Promise<boolean>
  count: (table: string, column: string, value: number) => Promise<number>
  pickNames: (table: string) => Promise<Map<number, string>>
}
