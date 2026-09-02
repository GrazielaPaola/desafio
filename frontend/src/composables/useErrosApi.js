import { ref } from 'vue'
import { useNotificacao } from './useNotificacao'

const STATUS_VALIDACAO = 422
const MENSAGEM_PADRAO = 'Não foi possível concluir a operação. Tente novamente.'

function primeiraMensagemPorCampo(erros) {
  return Object.fromEntries(
    Object.entries(erros ?? {}).map(([campo, mensagens]) => [campo, mensagens[0]]),
  )
}

export function useErrosApi() {
  const errosCampo = ref({})
  const mensagemGeral = ref('')
  const { erro: notificarErro } = useNotificacao()

  function limpar() {
    errosCampo.value = {}
    mensagemGeral.value = ''
  }

  function tratar(erro) {
    const resposta = erro?.response

    if (resposta?.status === STATUS_VALIDACAO) {
      errosCampo.value = primeiraMensagemPorCampo(resposta.data.errors)
      return
    }

    mensagemGeral.value = resposta?.data?.message || MENSAGEM_PADRAO
    notificarErro(mensagemGeral.value)
  }

  return { errosCampo, mensagemGeral, limpar, tratar }
}
