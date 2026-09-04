import { computed, ref } from 'vue'
import { coletaService } from '@/services/coletaService'
import { corDoMotorista } from '@/utils/cores'
import { adicionarDias, dataParaApi, inicioDaSemana } from '@/utils/data'

const DIAS_NA_SEMANA = 7
const LIMITE_COLETAS_POR_SEMANA = 100

export function useAgenda() {
  const inicioSemana = ref(inicioDaSemana(new Date()))
  const coletas = ref([])
  const carregando = ref(false)

  const dias = computed(() =>
    Array.from({ length: DIAS_NA_SEMANA }, (_, indice) =>
      adicionarDias(inicioSemana.value, indice),
    ),
  )

  const fimSemana = computed(() => dias.value[DIAS_NA_SEMANA - 1])

  const motoristasDaSemana = computed(() => {
    const porId = new Map(coletas.value.map((coleta) => [coleta.motorista.id, coleta.motorista]))
    return [...porId.values()].sort((a, b) => a.nome.localeCompare(b.nome))
  })

  async function carregar() {
    carregando.value = true

    try {
      const resposta = await coletaService.listar({
        data_inicio: dataParaApi(inicioSemana.value),
        data_fim: dataParaApi(fimSemana.value),
        por_pagina: LIMITE_COLETAS_POR_SEMANA,
      })
      coletas.value = resposta.data
    } finally {
      carregando.value = false
    }
  }

  function coletasDoDia(dia) {
    const chave = dataParaApi(dia)
    return coletas.value.filter((coleta) => coleta.data === chave)
  }

  function irParaSemana(inicio) {
    inicioSemana.value = inicio
    return carregar()
  }

  const semanaAnterior = () => irParaSemana(adicionarDias(inicioSemana.value, -DIAS_NA_SEMANA))
  const proximaSemana = () => irParaSemana(adicionarDias(inicioSemana.value, DIAS_NA_SEMANA))
  const semanaAtual = () => irParaSemana(inicioDaSemana(new Date()))

  return {
    inicioSemana,
    fimSemana,
    dias,
    coletas,
    carregando,
    motoristasDaSemana,
    carregar,
    coletasDoDia,
    corDoMotorista,
    semanaAnterior,
    proximaSemana,
    semanaAtual,
  }
}
