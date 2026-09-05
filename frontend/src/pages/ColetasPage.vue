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
        <q-btn unelevated class="botao-destaque" no-caps @click="abrirFormulario()">
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
      @editar="abrirFormulario"
      @excluir="excluirItem"
    />

    <ColetaForm
      v-model="formularioAberto"
      :coleta="selecionado"
      :motoristas="opcoesMotoristas"
      :erros-campo="errosCampo"
      :mensagem-geral="mensagemGeral"
      :salvando="salvando"
      @salvar="salvarItem"
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
import { useCadastro } from '@/composables/useCadastro'
import { useColetas } from '@/composables/useColetas'
import { useNotificacao } from '@/composables/useNotificacao'
import { useMotoristasStore } from '@/stores/motoristas'
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
  salvar,
  excluir,
  exportar,
} = useColetas()
const motoristasStore = useMotoristasStore()
const { opcoes: opcoesMotoristas } = storeToRefs(motoristasStore)
const { erro: notificarErro } = useNotificacao()

const {
  formularioAberto,
  selecionado,
  salvando,
  errosCampo,
  mensagemGeral,
  tratar,
  abrirFormulario,
  salvarItem,
  excluirItem,
} = useCadastro({
  salvar,
  excluir,
  mensagens: {
    criado: 'Coleta agendada',
    atualizado: 'Coleta atualizada',
    excluido: 'Coleta excluída',
    confirmarExclusao: (coleta) =>
      `Excluir a coleta de ${coleta.fornecedor_nome} em ${apiParaTela(coleta.data)}?`,
  },
})

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
