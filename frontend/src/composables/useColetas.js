import { coletaService } from '@/services/coletaService'
import { useListagemPaginada } from './useListagemPaginada'

export const FILTROS_INICIAIS_COLETAS = {
  busca: '',
  motorista_id: null,
  data_inicio: '',
  data_fim: '',
}

export function useColetas() {
  const listagem = useListagemPaginada(coletaService, FILTROS_INICIAIS_COLETAS)

  function exportar() {
    return coletaService.exportar(listagem.parametrosAtuais())
  }

  return {
    coletas: listagem.itens,
    carregando: listagem.carregando,
    paginacao: listagem.paginacao,
    filtros: listagem.filtros,
    carregar: listagem.carregar,
    aplicarFiltros: listagem.aplicarFiltros,
    salvar: listagem.salvar,
    excluir: listagem.excluir,
    exportar,
  }
}
