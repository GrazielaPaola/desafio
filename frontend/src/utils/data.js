import { date } from 'quasar'

export const FORMATO_API = 'YYYY-MM-DD'
export const FORMATO_TELA = 'DD/MM/YYYY'
export const FORMATO_CALENDARIO = 'YYYY/MM/DD'
export const MASCARA_DATA = '##/##/####'

const UNIDADE_DIAS = 'days'
const SEGUNDA_FEIRA = 1
const DIAS_NA_SEMANA = 7
const DIAS_DA_SEMANA_ABREVIADOS = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb']
const MESES_ABREVIADOS = [
  'JAN',
  'FEV',
  'MAR',
  'ABR',
  'MAI',
  'JUN',
  'JUL',
  'AGO',
  'SET',
  'OUT',
  'NOV',
  'DEZ',
]

function diasAPartirDeHoje(valor, formato) {
  return date.getDateDiff(date.extractDate(valor, formato), new Date(), UNIDADE_DIAS)
}

export function apiParaTela(valor) {
  if (!valor) return ''
  return date.formatDate(date.extractDate(valor, FORMATO_API), FORMATO_TELA)
}

export function telaParaApi(valor) {
  if (!valor) return ''
  return date.formatDate(date.extractDate(valor, FORMATO_TELA), FORMATO_API)
}

export function dataParaApi(valor) {
  return date.formatDate(valor, FORMATO_API)
}

export function diaDe(valorApi) {
  return valorApi ? String(valorApi).slice(8, 10) : ''
}

export function mesDe(valorApi) {
  return MESES_ABREVIADOS[Number(String(valorApi).slice(5, 7)) - 1] ?? ''
}

export function ehDataValidaNaTela(valor) {
  if (!valor || valor.length !== FORMATO_TELA.length) return false
  const convertida = date.extractDate(valor, FORMATO_TELA)
  return date.isValid(convertida) && date.formatDate(convertida, FORMATO_TELA) === valor
}

export function ehDataFuturaNaTela(valor) {
  return ehDataValidaNaTela(valor) && diasAPartirDeHoje(valor, FORMATO_TELA) > 0
}

export function dataPermitidaNoCalendario(valor) {
  return diasAPartirDeHoje(valor, FORMATO_CALENDARIO) > 0
}

export function adicionarDias(valor, dias) {
  return date.addToDate(valor, { [UNIDADE_DIAS]: dias })
}

export function inicioDaSemana(valor) {
  const inicioDoDia = date.startOfDate(valor, 'day')
  const deslocamento = (inicioDoDia.getDay() - SEGUNDA_FEIRA + DIAS_NA_SEMANA) % DIAS_NA_SEMANA
  return adicionarDias(inicioDoDia, -deslocamento)
}

export function ehHoje(valor) {
  return date.isSameDate(valor, new Date(), 'day')
}

export function formatarDiaDaSemana(valor) {
  return DIAS_DA_SEMANA_ABREVIADOS[valor.getDay()]
}

export function formatarDiaEMes(valor) {
  return date.formatDate(valor, 'DD/MM')
}

export function formatarDataCompleta(valor) {
  return date.formatDate(valor, FORMATO_TELA)
}
