import type { Pool, ResultSetHeader, RowDataPacket } from 'mysql2/promise'
import type { EntityConfig, Row, Store } from './types'

/** Armazenamento baseado no MySQL (igual ao projeto original em PHP). */
export class MySqlStore implements Store {
  kind = 'mysql' as const
  private pool: Pool

  constructor(pool: Pool) {
    this.pool = pool
  }

  async list(entity: EntityConfig, search: string): Promise<Row[]> {
    const query = search.trim()
    let sql = `SELECT * FROM \`${entity.table}\``
    const params: string[] = []
    if (query) {
      const like = entity.searchCols.map((col) => `\`${col}\` LIKE ?`).join(' OR ')
      sql += ` WHERE ${like}`
      for (const _col of entity.searchCols) params.push(`%${query}%`)
    }
    sql += ' ORDER BY `nome`'
    const [rows] = await this.pool.query<RowDataPacket[]>(sql, params)
    return rows as Row[]
  }

  async get(table: string, id: number): Promise<Row | null> {
    const [rows] = await this.pool.query<RowDataPacket[]>(
      `SELECT * FROM \`${table}\` WHERE id = ?`,
      [id],
    )
    return rows.length > 0 ? (rows[0] as Row) : null
  }

  async insert(table: string, data: Row): Promise<number> {
    const cols = Object.keys(data)
    const sql = `INSERT INTO \`${table}\` (${cols.map((c) => `\`${c}\``).join(', ')}) VALUES (${cols
      .map(() => '?')
      .join(', ')})`
    const [result] = await this.pool.query<ResultSetHeader>(sql, Object.values(data))
    return result.insertId
  }

  async update(table: string, id: number, data: Row): Promise<boolean> {
    const cols = Object.keys(data)
    const sql = `UPDATE \`${table}\` SET ${cols
      .map((c) => `\`${c}\` = ?`)
      .join(', ')} WHERE id = ?`
    const [result] = await this.pool.query<ResultSetHeader>(sql, [...Object.values(data), id])
    return result.affectedRows > 0
  }

  async remove(table: string, id: number): Promise<boolean> {
    const [result] = await this.pool.query<ResultSetHeader>(
      `DELETE FROM \`${table}\` WHERE id = ?`,
      [id],
    )
    return result.affectedRows > 0
  }

  async count(table: string, column: string, value: number): Promise<number> {
    const [rows] = await this.pool.query<RowDataPacket[]>(
      `SELECT COUNT(*) AS total FROM \`${table}\` WHERE \`${column}\` = ?`,
      [value],
    )
    return Number(rows[0]?.total ?? 0)
  }

  async pickNames(table: string): Promise<Map<number, string>> {
    const [rows] = await this.pool.query<RowDataPacket[]>(
      `SELECT id, nome FROM \`${table}\``,
    )
    const map = new Map<number, string>()
    for (const row of rows) map.set(Number(row.id), String(row.nome ?? ''))
    return map
  }
}
