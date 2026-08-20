export type Governante = {
  id: number
  nome: string
  partido_politico: string | null
  data_nascimento: string | null
  idade: number | null
  data_inicio_mandato: string | null
  data_fim_mandato: string | null
}

export type Continente = {
  id: number
  nome: string
  populacao: number | null
  area_km2: number | null
  total_paises: number | null
}

export type Pais = {
  id: number
  nome: string
  continente_id: number | null
  populacao: number | null
  area_km2: number | null
  idioma: string | null
  governante_id: number | null
  clima: string | null
  regime_politico: string | null
  moeda: string | null
  continente_nome?: string | null
  governante_nome?: string | null
}

export type Cidade = {
  id: number
  nome: string
  pais_id: number | null
  populacao: number | null
  area_km2: number | null
  governante_id: number | null
  clima: string | null
  data_fundacao: string | null
  pais_nome?: string | null
  governante_nome?: string | null
}
