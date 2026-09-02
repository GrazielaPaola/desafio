import { Dialog } from 'quasar'

export function useConfirmacao() {
  function confirmarExclusao(mensagem) {
    return new Promise((resolver) => {
      Dialog.create({
        title: 'Confirmar exclusão',
        message: mensagem,
        persistent: true,
        cancel: { label: 'Cancelar', flat: true },
        ok: { label: 'Excluir', color: 'negative' },
      })
        .onOk(() => resolver(true))
        .onCancel(() => resolver(false))
    })
  }

  return { confirmarExclusao }
}
