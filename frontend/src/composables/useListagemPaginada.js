import { ref } from 'vue'

const PRIMEIRA_PAGINA = 1
const POR_PAGINA_PADRAO = 15
const DIRECAO_ASCENDENTE = 'asc'
const DIRECAO_DESCENDENTE = 'desc'

function semVazios(objeto) {
  return Object.fromEntries(
    Object.entries(objeto).filter(
      ([, valor]) => valor !== null && valor !== undefined && valor !== '',
    ),
  )
}

export function useListagemPaginada(servico, filtrosIniciais = {}) {
  const itens = ref([])
  const carregando = ref(false)
  const filtros = ref({ ...filtrosIniciais })
  const paginacao = ref({
    page: PRIMEIRA_PAGINA,
    rowsPerPage: POR_PAGINA_PADRAO,
    rowsNumber: 0,
    sortBy: null,
    descending: false,
  })

  function parametrosDeOrdenacao(ordenacao) {
    if (!ordenacao.sortBy) return {}
    return {
      ordenar_por: ordenacao.sortBy,
      direcao: ordenacao.descending ? DIRECAO_DESCENDENTE : DIRECAO_ASCENDENTE,
    }
  }

  function parametrosAtuais() {
    return semVazios({ ...parametrosDeOrdenacao(paginacao.value), ...filtros.value })
  }

  async function carregar(parametros = {}) {
    const solicitada = { ...paginacao.value, ...parametros }
    carregando.value = true

    try {
      const resposta = await servico.listar(
        semVazios({
          page: solicitada.page,
          por_pagina: solicitada.rowsPerPage,
          ...parametrosDeOrdenacao(solicitada),
          ...filtros.value,
        }),
      )

      if (resposta.data.length === 0 && solicitada.page > PRIMEIRA_PAGINA) {
        return carregar({ ...solicitada, page: solicitada.page - 1 })
      }

      itens.value = resposta.data
      paginacao.value = {
        ...solicitada,
        page: resposta.meta.current_page,
        rowsPerPage: resposta.meta.per_page,
        rowsNumber: resposta.meta.total,
      }
    } finally {
      carregando.value = false
    }
  }

  function aplicarFiltros(novosFiltros) {
    filtros.value = { ...novosFiltros }
    return carregar({ page: PRIMEIRA_PAGINA })
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

  return {
    itens,
    carregando,
    paginacao,
    filtros,
    carregar,
    aplicarFiltros,
    parametrosAtuais,
    salvar,
    excluir,
  }
}
