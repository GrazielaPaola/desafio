import { ref, watch } from 'vue'

export const OPCOES_POR_PAGINA = [10, 15, 25, 50]

export function usePaginacaoTabela(props, emit) {
  const paginacaoTabela = ref({ ...props.paginacao })

  watch(
    () => props.paginacao,
    (nova) => {
      paginacaoTabela.value = { ...nova }
    },
    { deep: true },
  )

  function aoSolicitar(evento) {
    emit('solicitar', evento.pagination)
  }

  return { paginacaoTabela, aoSolicitar }
}
