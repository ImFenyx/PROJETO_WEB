import express from 'express'
import mysql from 'mysql2/promise'
import { entities } from './entities'
import { MemoryStore, seedData } from './memory'
import { MySqlStore } from './mysql'
import type { EntityConfig, Field, Row, Store } from './types'

const PORT = Number(process.env.PORT ?? 3001)

async function pickStore(): Promise<Store> {
  const config = {
    host: process.env.DB_HOST ?? '127.0.0.1',
    port: Number(process.env.DB_PORT ?? 3306),
    user: process.env.DB_USER ?? 'root',
    password: process.env.DB_PASSWORD ?? '',
    database: process.env.DB_NAME ?? 'bd_mundo',
    connectTimeout: 2000,
  }

  try {
    const pool = mysql.createPool({ ...config, connectionLimit: 10 })
    const connection = await pool.getConnection()
    await connection.ping()
    connection.release()
    console.log(
      `[db] conectado ao MySQL em ${config.host}:${config.port}/${config.database}`,
    )
    return new MySqlStore(pool)
  } catch (error) {
    const message = error instanceof Error ? error.message : String(error)
    console.warn(`[db] MySQL indisponível (${message}).`)
    console.warn('[db] usando armazenamento em memória com dados de exemplo.')
    const store = new MemoryStore()
    seedData(store)
    return store
  }
}

function coerce(field: Field, raw: unknown): unknown {
  if (raw === null || raw === undefined) return null
  if (field.type === 'number') {
    const text = String(raw).trim()
    if (text === '') return null
    const value = Number(text)
    return Number.isFinite(value) ? value : null
  }
  const text = String(raw).trim()
  return text === '' ? null : text
}

function coerceData(entity: EntityConfig, body: unknown): Row | null {
  if (body === null || typeof body !== 'object') return null
  const data: Row = {}
  for (const field of entity.fields) {
    data[field.key] = coerce(field, (body as Row)[field.key])
  }
  return data
}

function buildRouter(entity: EntityConfig, store: Store): express.Router {
  const router = express.Router()

  async function enrich(rows: Row[]): Promise<Row[]> {
    const nameMaps = new Map<string, Map<number, string>>()
    for (const ref of entity.nameRefs) {
      if (!nameMaps.has(ref.table)) {
        nameMaps.set(ref.table, await store.pickNames(ref.table))
      }
    }
    return rows.map((row) => {
      const output: Row = { ...row }
      for (const ref of entity.nameRefs) {
        const map = nameMaps.get(ref.table)
        const fk = output[ref.fk]
        output[ref.as] = fk === null || fk === undefined ? null : (map?.get(Number(fk)) ?? null)
      }
      entity.compute?.(output)
      return output
    })
  }

  router.get('/', async (req, res) => {
    const search = typeof req.query.q === 'string' ? req.query.q : ''
    const rows = await store.list(entity, search)
    res.json(await enrich(rows))
  })

  router.get('/:id', async (req, res) => {
    const id = Number(req.params.id)
    const row = await store.get(entity.table, id)
    if (!row) {
      res.status(404).json({ error: 'Registro não encontrado.' })
      return
    }
    res.json((await enrich([row]))[0])
  })

  router.post('/', async (req, res) => {
    const data = coerceData(entity, req.body)
    if (!data || typeof data.nome !== 'string' || data.nome.length === 0) {
      res.status(400).json({ error: 'O campo "nome" é obrigatório.' })
      return
    }
    const id = await store.insert(entity.table, data)
    const row = await store.get(entity.table, id)
    res.status(201).json((await enrich([row as Row]))[0])
  })

  router.put('/:id', async (req, res) => {
    const id = Number(req.params.id)
    const data = coerceData(entity, req.body)
    if (!data || typeof data.nome !== 'string' || data.nome.length === 0) {
      res.status(400).json({ error: 'O campo "nome" é obrigatório.' })
      return
    }
    const updated = await store.update(entity.table, id, data)
    if (!updated) {
      res.status(404).json({ error: 'Registro não encontrado.' })
      return
    }
    const row = await store.get(entity.table, id)
    res.json((await enrich([row as Row]))[0])
  })

  router.delete('/:id', async (req, res) => {
    const id = Number(req.params.id)
    if (entity.deleteGuard) {
      const reason = await entity.deleteGuard(store, id)
      if (reason) {
        res.status(409).json({ error: reason })
        return
      }
    }
    const removed = await store.remove(entity.table, id)
    if (!removed) {
      res.status(404).json({ error: 'Registro não encontrado.' })
      return
    }
    res.status(204).end()
  })

  return router
}

const store = await pickStore()

const app = express()
app.use(express.json())

app.get('/api/health', (_req, res) => {
  res.json({ ok: true, store: store.kind })
})

app.use('/api/continentes', buildRouter(entities.continentes, store))
app.use('/api/governantes', buildRouter(entities.governantes, store))
app.use('/api/paises', buildRouter(entities.paises, store))
app.use('/api/cidades', buildRouter(entities.cidades, store))

app.use('/api', (_req, res) => {
  res.status(404).json({ error: 'Rota não encontrada.' })
})

// eslint-disable-next-line @typescript-eslint/no-unused-vars
app.use((error: unknown, _req: express.Request, res: express.Response, _next: express.NextFunction) => {
  console.error('[api] erro:', error)
  res.status(500).json({ error: 'Erro interno no servidor.' })
})

app.listen(PORT, '0.0.0.0', () => {
  console.log(`[api] ouvindo em http://0.0.0.0:${PORT} (${store.kind})`)
})
