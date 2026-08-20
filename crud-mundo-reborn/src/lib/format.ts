const numberFormat = new Intl.NumberFormat('pt-BR')

export function formatNumber(value: unknown): string {
  if (value === null || value === undefined || value === '') return '—'
  return numberFormat.format(Number(value))
}

export function formatArea(value: unknown): string {
  if (value === null || value === undefined || value === '') return '—'
  return `${numberFormat.format(Number(value))} km²`
}

export function formatDate(value: unknown): string {
  if (value === null || value === undefined || value === '') return '—'
  const text = String(value)
  const match = text.match(/^(\d{4})-(\d{2})-(\d{2})/)
  if (match) return `${match[3]}/${match[2]}/${match[1]}`
  return text
}
