<template>
  <q-page class="pagina">
    <CabecalhoPagina titulo="Coletas" :subtitulo="subtitulo" />

    <div class="barra">
      <FiltrosColetas
        :model-value="filtros"
        :motoristas="opcoesMotoristas"
        @update:model-value="filtrar"
      />

      <div class="barra__acoes">
        <q-btn flat class="botao-contorno" :loading="exportando" no-caps @click="exportarCsv">
          <IconeSvg nome="download" :tamanho="15" :espessura="2" class="q-mr-sm" />
          Exportar CSV
        </q-btn>
        <q-btn unelevated class="botao-destaque" no-caps @click="nova">
          <IconeSvg nome="mais" :tamanho="15" :espessura="2.4" class="q-mr-sm" />
          Nova coleta
        </q-btn>
      </div>
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
import { computed, onMounted, ref } from 'vue'
import { storeToRefs } from 'pinia'
import { exportFile } from 'quasar'
import CabecalhoPagina from '@/components/CabecalhoPagina.vue'
import ColetasTable from '@/components/ColetasTable.vue'
import ColetaForm from '@/components/ColetaForm.vue'
import FiltrosColetas from '@/components/FiltrosColetas.vue'
import IconeSvg from '@/components/IconeSvg.vue'
import { useColetas } from '@/composables/useColetas'
import { useMotoristasStore } from '@/stores/motoristas'
import { useResumoStore } from '@/stores/resumo'
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
const resumoStore = useResumoStore()
const { errosCampo, mensagemGeral, limpar, tratar } = useErrosApi()
const { sucesso, erro: notificarErro } = useNotificacao()
const { confirmarExclusao } = useConfirmacao()

const formularioAberto = ref(false)
const coletaSelecionada = ref(null)
const salvando = ref(false)
const exportando = ref(false)

const subtitulo = computed(() => {
  const total = paginacao.value.rowsNumber
  return total === 1 ? '1 coleta encontrada' : `${total} coletas encontradas`
})

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
    resumoStore.carregar().catch(tratar)
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
    resumoStore.carregar().catch(tratar)
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

<style scoped>
.barra {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 12px;
}

.barra__acoes {
  display: flex;
  gap: 10px;
  margin-left: auto;
}
</style>
