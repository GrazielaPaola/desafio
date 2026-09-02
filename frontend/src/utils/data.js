import { date } from 'quasar'

export const FORMATO_API = 'YYYY-MM-DD'
export const FORMATO_TELA = 'DD/MM/YYYY'
export const FORMATO_CALENDARIO = 'YYYY/MM/DD'
export const MASCARA_DATA = '##/##/####'

const UNIDADE_DIAS = 'days'

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
