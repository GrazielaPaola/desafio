import { defineStore } from 'pinia'
import { ref } from 'vue'
import { motoristaService } from '@/services/motoristaService'
import { useListagemPaginada } from '@/composables/useListagemPaginada'

const POR_PAGINA_MAXIMO = 100

export const useMotoristasStore = defineStore('motoristas', () => {
  const listagem = useListagemPaginada(motoristaService)
  const opcoes = ref([])

  async function carregarOpcoes() {
    const resposta = await motoristaService.listar({ por_pagina: POR_PAGINA_MAXIMO })
    opcoes.value = resposta.data
  }

  return {
    motoristas: listagem.itens,
    carregando: listagem.carregando,
    paginacao: listagem.paginacao,
    opcoes,
    carregar: listagem.carregar,
    salvar: listagem.salvar,
    excluir: listagem.excluir,
    carregarOpcoes,
  }
})
