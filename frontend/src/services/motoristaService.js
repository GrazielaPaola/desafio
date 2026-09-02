import { api } from './api'

const RECURSO = '/motoristas'

export const motoristaService = {
  listar: (parametros) =>
    api.get(RECURSO, { params: parametros }).then((resposta) => resposta.data),
  buscar: (id) => api.get(`${RECURSO}/${id}`).then((resposta) => resposta.data),
  criar: (dados) => api.post(RECURSO, dados).then((resposta) => resposta.data),
  atualizar: (id, dados) => api.put(`${RECURSO}/${id}`, dados).then((resposta) => resposta.data),
  excluir: (id) => api.delete(`${RECURSO}/${id}`),
}
