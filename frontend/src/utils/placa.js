const FORMATO_ANTIGO = /^[A-Z]{3}\d{4}$/
const FORMATO_MERCOSUL = /^[A-Z]{3}\d[A-Z]\d{2}$/
const TAMANHO_PREFIXO = 3

export function normalizarPlaca(valor) {
  return String(valor ?? '')
    .toUpperCase()
    .replace(/[^A-Z0-9]/g, '')
}

export function validarPlaca(valor) {
  const placa = normalizarPlaca(valor)
  return FORMATO_ANTIGO.test(placa) || FORMATO_MERCOSUL.test(placa)
}

export function formatarPlaca(valor) {
  const placa = normalizarPlaca(valor)

  if (!FORMATO_ANTIGO.test(placa)) {
    return placa
  }

  return `${placa.slice(0, TAMANHO_PREFIXO)}-${placa.slice(TAMANHO_PREFIXO)}`
}
