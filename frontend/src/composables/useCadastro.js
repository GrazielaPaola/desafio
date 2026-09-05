import { ref } from 'vue'
import { useConfirmacao } from './useConfirmacao'
import { useErrosApi } from './useErrosApi'
import { useNotificacao } from './useNotificacao'
import { useResumoStore } from '@/stores/resumo'

export function useCadastro({ salvar, excluir, mensagens }) {
  const formularioAberto = ref(false)
  const selecionado = ref(null)
  const salvando = ref(false)

  const { errosCampo, mensagemGeral, limpar, tratar } = useErrosApi()
  const { sucesso } = useNotificacao()
  const { confirmarExclusao } = useConfirmacao()
  const resumoStore = useResumoStore()

  function atualizarResumo() {
    resumoStore.carregar().catch(tratar)
  }

  function abrirFormulario(item = null) {
    selecionado.value = item
    limpar()
    formularioAberto.value = true
  }

  async function salvarItem(dados) {
    salvando.value = true
    limpar()

    try {
      await salvar(dados, selecionado.value?.id)
      sucesso(selecionado.value ? mensagens.atualizado : mensagens.criado)
      formularioAberto.value = false
      atualizarResumo()
    } catch (erro) {
      tratar(erro)
    } finally {
      salvando.value = false
    }
  }

  async function excluirItem(item) {
    const confirmado = await confirmarExclusao(mensagens.confirmarExclusao(item))
    if (!confirmado) return

    try {
      await excluir(item.id)
      sucesso(mensagens.excluido)
      atualizarResumo()
    } catch (erro) {
      tratar(erro)
    }
  }

  return {
    formularioAberto,
    selecionado,
    salvando,
    errosCampo,
    mensagemGeral,
    tratar,
    abrirFormulario,
    salvarItem,
    excluirItem,
  }
}
