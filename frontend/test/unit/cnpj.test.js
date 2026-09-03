import { describe, expect, it } from 'vitest'
import { formatarCnpj, somenteDigitos, validarCnpj } from '@/utils/cnpj'

describe('validarCnpj', () => {
  it('aceita CNPJ válido com ou sem máscara', () => {
    expect(validarCnpj('11.222.333/0001-81')).toBe(true)
    expect(validarCnpj('11222333000181')).toBe(true)
    expect(validarCnpj('12.345.678/0001-95')).toBe(true)
  })

  it('rejeita dígitos verificadores incorretos', () => {
    expect(validarCnpj('11.222.333/0001-82')).toBe(false)
    expect(validarCnpj('12.345.678/0001-96')).toBe(false)
  })

  it('rejeita tamanho errado, dígitos repetidos e valores vazios', () => {
    expect(validarCnpj('11.222.333/0001')).toBe(false)
    expect(validarCnpj('11.111.111/1111-11')).toBe(false)
    expect(validarCnpj('')).toBe(false)
    expect(validarCnpj(null)).toBe(false)
  })
})

describe('formatarCnpj', () => {
  it('aplica a máscara em 14 dígitos', () => {
    expect(formatarCnpj('11222333000181')).toBe('11.222.333/0001-81')
  })

  it('devolve apenas os dígitos quando o tamanho é inválido', () => {
    expect(formatarCnpj('1122')).toBe('1122')
  })
})

describe('somenteDigitos', () => {
  it('remove tudo que não é número', () => {
    expect(somenteDigitos('11.222.333/0001-81')).toBe('11222333000181')
    expect(somenteDigitos(undefined)).toBe('')
  })
})
