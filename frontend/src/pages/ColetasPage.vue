<template>
  <q-page padding>
    <div class="row items-center justify-between q-mb-md">
      <div class="text-h5">Coletas</div>
      <q-btn color="primary" icon="add" label="Nova coleta" @click="nova" />
    </div>

    <ColetasTable
      :coletas="coletas"
      :carregando="carregando"
      :paginacao="paginacao"
      @solicitar="carregarPagina"
      @editar="editar"
      @excluir="excluir"
    />

    <ColetaForm
      v-model="formularioAberto"
      :coleta="coletaSelecionada"
      :motoristas="opcoesMotoristas"
      :erros-campo="errosCampo"
      :mensagem-geral="mensagemGeral"
      :salvando="salvando"
      @salvar="salvar"
    />
  </q-page>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { storeToRefs } from 'pinia'
import ColetasTable from '@/components/ColetasTable.vue'
import ColetaForm from '@/components/ColetaForm.vue'
import { useColetas } from '@/composables/useColetas'
import { useMotoristasStore } from '@/stores/motoristas'
import { useErrosApi } from '@/composables/useErrosApi'
import { useNotificacao } from '@/composables/useNotificacao'
import { useConfirmacao } from '@/composables/useConfirmacao'
import { apiParaTela } from '@/utils/data'

const {
  coletas,
  carregando,
  paginacao,
  carregar,
  salvar: salvarColeta,
  excluir: excluirColeta,
} = useColetas()
const motoristasStore = useMotoristasStore()
const { opcoes: opcoesMotoristas } = storeToRefs(motoristasStore)
const { errosCampo, mensagemGeral, limpar, tratar } = useErrosApi()
const { sucesso } = useNotificacao()
const { confirmarExclusao } = useConfirmacao()

const formularioAberto = ref(false)
const coletaSelecionada = ref(null)
const salvando = ref(false)

onMounted(() => {
  carregarPagina()
  motoristasStore.carregarOpcoes().catch(tratar)
})

function carregarPagina(pagina) {
  carregar(pagina).catch(tratar)
}

function nova() {
  abrirFormulario(null)
}

function editar(coleta) {
  abrirFormulario(coleta)
}

function abrirFormulario(coleta) {
  coletaSelecionada.value = coleta
  limpar()
  formularioAberto.value = true
}

async function salvar(dados) {
  salvando.value = true
  limpar()

  try {
    await salvarColeta(dados, coletaSelecionada.value?.id)
    sucesso(coletaSelecionada.value ? 'Coleta atualizada' : 'Coleta agendada')
    formularioAberto.value = false
  } catch (erro) {
    tratar(erro)
  } finally {
    salvando.value = false
  }
}

async function excluir(coleta) {
  const confirmado = await confirmarExclusao(
    `Excluir a coleta de ${coleta.fornecedor_nome} em ${apiParaTela(coleta.data)}?`,
  )
  if (!confirmado) return

  try {
    await excluirColeta(coleta.id)
    sucesso('Coleta excluída')
  } catch (erro) {
    tratar(erro)
  }
}
</script>
