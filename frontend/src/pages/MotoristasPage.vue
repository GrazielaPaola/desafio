<template>
  <q-page class="pagina">
    <CabecalhoPagina titulo="Motoristas" :subtitulo="subtitulo" />

    <div class="barra">
      <CampoBusca
        class="barra__busca"
        :model-value="filtros.busca"
        rotulo="Buscar por nome"
        @update:model-value="filtrar"
      />
      <q-btn unelevated class="botao-destaque barra__acao" no-caps @click="novo">
        <IconeSvg nome="mais" :tamanho="15" :espessura="2.4" class="q-mr-sm" />
        Novo motorista
      </q-btn>
    </div>

    <MotoristasGrid
      :motoristas="motoristas"
      :carregando="carregando"
      :paginacao="paginacao"
      @solicitar="carregarPagina"
      @editar="editar"
      @excluir="excluir"
    />

    <MotoristaForm
      v-model="formularioAberto"
      :motorista="motoristaSelecionado"
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
import CabecalhoPagina from '@/components/CabecalhoPagina.vue'
import CampoBusca from '@/components/CampoBusca.vue'
import IconeSvg from '@/components/IconeSvg.vue'
import MotoristasGrid from '@/components/MotoristasGrid.vue'
import MotoristaForm from '@/components/MotoristaForm.vue'
import { useMotoristasStore } from '@/stores/motoristas'
import { useResumoStore } from '@/stores/resumo'
import { useErrosApi } from '@/composables/useErrosApi'
import { useNotificacao } from '@/composables/useNotificacao'
import { useConfirmacao } from '@/composables/useConfirmacao'

const store = useMotoristasStore()
const { motoristas, carregando, paginacao, filtros } = storeToRefs(store)
const resumoStore = useResumoStore()
const { errosCampo, mensagemGeral, limpar, tratar } = useErrosApi()
const { sucesso } = useNotificacao()
const { confirmarExclusao } = useConfirmacao()

const formularioAberto = ref(false)
const motoristaSelecionado = ref(null)
const salvando = ref(false)

const subtitulo = computed(() => {
  const total = paginacao.value.rowsNumber
  return total === 1 ? '1 motorista cadastrado' : `${total} motoristas cadastrados`
})

onMounted(() => carregarPagina())

function carregarPagina(pagina) {
  store.carregar(pagina).catch(tratar)
}

function filtrar(busca) {
  store.aplicarFiltros({ busca }).catch(tratar)
}

function novo() {
  abrirFormulario(null)
}

function editar(motorista) {
  abrirFormulario(motorista)
}

function abrirFormulario(motorista) {
  motoristaSelecionado.value = motorista
  limpar()
  formularioAberto.value = true
}

async function salvar(dados) {
  salvando.value = true
  limpar()

  try {
    await store.salvar(dados, motoristaSelecionado.value?.id)
    sucesso(motoristaSelecionado.value ? 'Motorista atualizado' : 'Motorista cadastrado')
    formularioAberto.value = false
    resumoStore.carregar().catch(tratar)
  } catch (erro) {
    tratar(erro)
  } finally {
    salvando.value = false
  }
}

async function excluir(motorista) {
  const confirmado = await confirmarExclusao(`Excluir o motorista ${motorista.nome}?`)
  if (!confirmado) return

  try {
    await store.excluir(motorista.id)
    sucesso('Motorista excluído')
    resumoStore.carregar().catch(tratar)
  } catch (erro) {
    tratar(erro)
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

.barra__busca {
  flex: 1 1 300px;
  max-width: 420px;
}

.barra__acao {
  margin-left: auto;
}
</style>
