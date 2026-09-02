import { ref } from 'vue'

const PRIMEIRA_PAGINA = 1
const POR_PAGINA_PADRAO = 15

export function useListagemPaginada(servico) {
  const itens = ref([])
  const carregando = ref(false)
  const paginacao = ref({ page: PRIMEIRA_PAGINA, rowsPerPage: POR_PAGINA_PADRAO, rowsNumber: 0 })

  async function carregar({
    page = paginacao.value.page,
    rowsPerPage = paginacao.value.rowsPerPage,
  } = {}) {
    carregando.value = true

    try {
      const resposta = await servico.listar({ page, por_pagina: rowsPerPage })

      if (resposta.data.length === 0 && page > PRIMEIRA_PAGINA) {
        return carregar({ page: page - 1, rowsPerPage })
      }

      itens.value = resposta.data
      paginacao.value = {
        page: resposta.meta.current_page,
        rowsPerPage: resposta.meta.per_page,
        rowsNumber: resposta.meta.total,
      }
    } finally {
      carregando.value = false
    }
  }

  async function salvar(dados, id = null) {
    const item = id ? await servico.atualizar(id, dados) : await servico.criar(dados)
    await carregar()
    return item
  }

  async function excluir(id) {
    await servico.excluir(id)
    await carregar()
  }

  return { itens, carregando, paginacao, carregar, salvar, excluir }
}
