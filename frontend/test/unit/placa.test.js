import { describe, expect, it } from 'vitest'
import { formatarPlaca, normalizarPlaca, validarPlaca } from '@/utils/placa'

describe('validarPlaca', () => {
  it('aceita formato antigo com ou sem hífen e em minúsculas', () => {
    expect(validarPlaca('ABC-1234')).toBe(true)
    expect(validarPlaca('ABC1234')).toBe(true)
    expect(validarPlaca('abc-1234')).toBe(true)
  })

  it('aceita formato Mercosul', () => {
    expect(validarPlaca('ABC1D23')).toBe(true)
    expect(validarPlaca('abc1d23')).toBe(true)
  })

  it('rejeita formatos inválidos', () => {
    expect(validarPlaca('AB-1234')).toBe(false)
    expect(validarPlaca('ABCD123')).toBe(false)
    expect(validarPlaca('1234ABC')).toBe(false)
    expect(validarPlaca('')).toBe(false)
    expect(validarPlaca(null)).toBe(false)
  })
})

describe('normalizarPlaca e formatarPlaca', () => {
  it('normaliza para maiúsculas sem hífen', () => {
    expect(normalizarPlaca('abc-1234')).toBe('ABC1234')
  })

  it('formata o padrão antigo com hífen e mantém Mercosul sem hífen', () => {
    expect(formatarPlaca('abc1234')).toBe('ABC-1234')
    expect(formatarPlaca('abc1d23')).toBe('ABC1D23')
  })
})
