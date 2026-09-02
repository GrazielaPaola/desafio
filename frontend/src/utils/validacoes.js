import { validarCnpj } from './cnpj'
import { validarPlaca } from './placa'
import { ehDataFuturaNaTela, ehDataValidaNaTela } from './data'

export function obrigatorio(valor) {
  const preenchido = valor !== null && valor !== undefined && String(valor).trim() !== ''
  return preenchido || 'Campo obrigatório'
}

export function tamanhoMaximo(maximo) {
  return (valor) => !valor || String(valor).length <= maximo || `Máximo de ${maximo} caracteres`
}

export function cnpjValido(valor) {
  return validarCnpj(valor) || 'CNPJ inválido'
}

export function placaValida(valor) {
  return validarPlaca(valor) || 'Placa inválida. Use ABC-1234 ou ABC1D23'
}

export function dataValida(valor) {
  return ehDataValidaNaTela(valor) || 'Data inválida'
}

export function dataFutura(valor) {
  return ehDataFuturaNaTela(valor) || 'A data deve ser maior que a data atual'
}
