import { describe, expect, it } from 'vitest'
import {
  cnpjValido,
  dataFutura,
  dataValida,
  obrigatorio,
  placaValida,
  tamanhoMaximo,
} from '@/utils/validacoes'

describe('rules do formulário', () => {
  it('obrigatorio exige valor preenchido', () => {
    expect(obrigatorio('abc')).toBe(true)
    expect(obrigatorio(0)).toBe(true)
    expect(obrigatorio('   ')).toBe('Campo obrigatório')
    expect(obrigatorio(null)).toBe('Campo obrigatório')
  })

  it('tamanhoMaximo limita o número de caracteres', () => {
    expect(tamanhoMaximo(3)('abc')).toBe(true)
    expect(tamanhoMaximo(3)('abcd')).toBe('Máximo de 3 caracteres')
    expect(tamanhoMaximo(3)('')).toBe(true)
  })

  it('cnpjValido e placaValida devolvem mensagem quando inválidos', () => {
    expect(cnpjValido('11.222.333/0001-81')).toBe(true)
    expect(cnpjValido('11.222.333/0001-82')).toBe('CNPJ inválido')
    expect(placaValida('ABC1D23')).toBe(true)
    expect(placaValida('AB12')).toBe('Placa inválida. Use ABC-1234 ou ABC1D23')
  })

  it('dataValida e dataFutura validam o formato de tela', () => {
    expect(dataValida('10/09/2026')).toBe(true)
    expect(dataValida('31/02/2026')).toBe('Data inválida')
    expect(dataFutura('01/01/2000')).toBe('A data deve ser maior que a data atual')
  })
})
