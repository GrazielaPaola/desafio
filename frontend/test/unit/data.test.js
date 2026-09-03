import { describe, expect, it } from 'vitest'
import {
  adicionarDias,
  apiParaTela,
  dataParaApi,
  ehDataFuturaNaTela,
  ehDataValidaNaTela,
  formatarDiaDaSemana,
  inicioDaSemana,
  telaParaApi,
} from '@/utils/data'

function paraTela(data) {
  return apiParaTela(dataParaApi(data))
}

describe('conversão entre API e tela', () => {
  it('converte YYYY-MM-DD para DD/MM/YYYY e vice-versa', () => {
    expect(apiParaTela('2026-09-10')).toBe('10/09/2026')
    expect(telaParaApi('10/09/2026')).toBe('2026-09-10')
  })

  it('devolve vazio para valores ausentes', () => {
    expect(apiParaTela('')).toBe('')
    expect(telaParaApi(null)).toBe('')
  })
})

describe('ehDataValidaNaTela', () => {
  it('aceita datas reais e rejeita datas inexistentes ou incompletas', () => {
    expect(ehDataValidaNaTela('28/02/2026')).toBe(true)
    expect(ehDataValidaNaTela('31/02/2026')).toBe(false)
    expect(ehDataValidaNaTela('10/09/26')).toBe(false)
    expect(ehDataValidaNaTela('')).toBe(false)
  })
})

describe('ehDataFuturaNaTela', () => {
  it('considera futura apenas a data posterior a hoje', () => {
    const hoje = new Date()

    expect(ehDataFuturaNaTela(paraTela(adicionarDias(hoje, 1)))).toBe(true)
    expect(ehDataFuturaNaTela(paraTela(hoje))).toBe(false)
    expect(ehDataFuturaNaTela(paraTela(adicionarDias(hoje, -1)))).toBe(false)
  })
})

describe('inicioDaSemana', () => {
  it('retorna a segunda-feira da semana da data informada', () => {
    const quarta = new Date(2026, 8, 2)
    const domingo = new Date(2026, 8, 6)
    const segunda = new Date(2026, 8, 7)

    expect(dataParaApi(inicioDaSemana(quarta))).toBe('2026-08-31')
    expect(dataParaApi(inicioDaSemana(domingo))).toBe('2026-08-31')
    expect(dataParaApi(inicioDaSemana(segunda))).toBe('2026-09-07')
  })
})

describe('formatarDiaDaSemana', () => {
  it('abrevia o dia da semana em português', () => {
    expect(formatarDiaDaSemana(new Date(2026, 8, 7))).toBe('Seg')
    expect(formatarDiaDaSemana(new Date(2026, 8, 6))).toBe('Dom')
  })
})
