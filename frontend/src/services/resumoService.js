import { api } from './api'

const RECURSO = '/resumo'

export const resumoService = {
  obter: () => api.get(RECURSO).then((resposta) => resposta.data),
}
