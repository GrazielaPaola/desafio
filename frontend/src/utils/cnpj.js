const TAMANHO_CNPJ = 14
const PESOS_PRIMEIRO_DIGITO = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]
const PESOS_SEGUNDO_DIGITO = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]
const POSICAO_PRIMEIRO_DIGITO = 12
const POSICAO_SEGUNDO_DIGITO = 13

export const MASCARA_CNPJ = '##.###.###/####-##'

export function somenteDigitos(valor) {
  return String(valor ?? '').replace(/\D/g, '')
}

function calcularDigitoVerificador(digitos, pesos) {
  const soma = pesos.reduce(
    (acumulado, peso, indice) => acumulado + Number(digitos[indice]) * peso,
    0,
  )
  const resto = soma % 11
  return resto < 2 ? 0 : 11 - resto
}

function todosDigitosIguais(digitos) {
  return /^(\d)\1+$/.test(digitos)
}

export function validarCnpj(valor) {
  const digitos = somenteDigitos(valor)

  if (digitos.length !== TAMANHO_CNPJ || todosDigitosIguais(digitos)) {
    return false
  }

  const primeiroDigito = calcularDigitoVerificador(digitos, PESOS_PRIMEIRO_DIGITO)
  const segundoDigito = calcularDigitoVerificador(digitos, PESOS_SEGUNDO_DIGITO)

  return (
    primeiroDigito === Number(digitos[POSICAO_PRIMEIRO_DIGITO]) &&
    segundoDigito === Number(digitos[POSICAO_SEGUNDO_DIGITO])
  )
}

export function formatarCnpj(valor) {
  const digitos = somenteDigitos(valor)

  if (digitos.length !== TAMANHO_CNPJ) {
    return digitos
  }

  return digitos.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, '$1.$2.$3/$4-$5')
}
