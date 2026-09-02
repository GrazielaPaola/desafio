import { Notify } from 'quasar'

export function useNotificacao() {
  function sucesso(mensagem) {
    Notify.create({ type: 'positive', message: mensagem })
  }

  function erro(mensagem) {
    Notify.create({ type: 'negative', message: mensagem })
  }

  return { sucesso, erro }
}
