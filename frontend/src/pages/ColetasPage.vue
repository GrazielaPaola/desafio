<template>
  <q-page padding>
    <div class="row items-center justify-between q-mb-md q-gutter-y-sm">
      <div class="text-h5">Coletas</div>
      <div class="q-gutter-sm">
        <q-btn
          outline
          color="primary"
          icon="download"
          label="Exportar CSV"
          :loading="exportando"
          @click="exportarCsv"
        />
        <q-btn color="primary" icon="add" label="Nova coleta" @click="nova" />
      </div>
    </div>

    <FiltrosColetas
      class="q-mb-md"
      :model-value="filtros"
      :motoristas="opcoesMotoristas"
      @update:model-value="filtrar"
    />

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
import { exportFile } from 'quasar'
import ColetasTable from '@/components/ColetasTable.vue'
import ColetaForm from '@/components/ColetaForm.vue'
import FiltrosColetas from '@/components/FiltrosColetas.vue'
import { useColetas } from '@/composables/useColetas'
import { useMotoristasStore } from '@/stores/motoristas'
import { useErrosApi } from '@/composables/useErrosApi'
import { useNotificacao } from '@/composables/useNotificacao'
import { useConfirmacao } from '@/composables/useConfirmacao'
import { apiParaTela } from '@/utils/data'

const NOME_ARQUIVO_CSV = 'coletas.csv'
const TIPO_CSV = 'text/csv'

const {
  coletas,
  carregando,
  paginacao,
  filtros,
  carregar,
  aplicarFiltros,
  salvar: salvarColeta,
  excluir: excluirColeta,
  exportar,
} = useColetas()
const motoristasStore = useMotoristasStore()
const { opcoes: opcoesMotoristas } = storeToRefs(motoristasStore)
const { errosCampo, mensagemGeral, limpar, tratar } = useErrosApi()
const { sucesso, erro: notificarErro } = useNotificacao()
const { confirmarExclusao } = useConfirmacao()

const formularioAberto = ref(false)
const coletaSelecionada = ref(null)
const salvando = ref(false)
const exportando = ref(false)

onMounted(() => {
  carregarPagina()
  motoristasStore.carregarOpcoes().catch(tratar)
})

function carregarPagina(pagina) {
  carregar(pagina).catch(tratar)
}

function filtrar(novosFiltros) {
  aplicarFiltros(novosFiltros).catch(tratar)
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

async function exportarCsv() {
  exportando.value = true

  try {
    const arquivo = await exportar()
    const salvo = exportFile(NOME_ARQUIVO_CSV, arquivo, { mimeType: TIPO_CSV })
    if (salvo !== true) notificarErro('O navegador bloqueou o download do arquivo.')
  } catch (erro) {
    tratar(erro)
  } finally {
    exportando.value = false
  }
}
</script>
