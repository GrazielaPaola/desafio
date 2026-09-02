import { coletaService } from '@/services/coletaService'
import { useListagemPaginada } from './useListagemPaginada'

export function useColetas() {
  const listagem = useListagemPaginada(coletaService)

  return {
    coletas: listagem.itens,
    carregando: listagem.carregando,
    paginacao: listagem.paginacao,
    carregar: listagem.carregar,
    salvar: listagem.salvar,
    excluir: listagem.excluir,
  }
}
