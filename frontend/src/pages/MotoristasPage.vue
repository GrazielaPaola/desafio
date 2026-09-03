<template>
  <q-page padding>
    <div class="row items-center justify-between q-mb-md q-gutter-y-sm">
      <div class="text-h5">Motoristas</div>
      <q-btn color="primary" icon="add" label="Novo motorista" @click="novo" />
    </div>

    <div class="row q-mb-md">
      <div class="col-12 col-md-4">
        <CampoBusca
          :model-value="filtros.busca"
          label="Buscar por nome"
          @update:model-value="filtrar"
        />
      </div>
    </div>

    <MotoristasTable
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
import { onMounted, ref } from 'vue'
import { storeToRefs } from 'pinia'
import CampoBusca from '@/components/CampoBusca.vue'
import MotoristasTable from '@/components/MotoristasTable.vue'
import MotoristaForm from '@/components/MotoristaForm.vue'
import { useMotoristasStore } from '@/stores/motoristas'
import { useErrosApi } from '@/composables/useErrosApi'
import { useNotificacao } from '@/composables/useNotificacao'
import { useConfirmacao } from '@/composables/useConfirmacao'

const store = useMotoristasStore()
const { motoristas, carregando, paginacao, filtros } = storeToRefs(store)
const { errosCampo, mensagemGeral, limpar, tratar } = useErrosApi()
const { sucesso } = useNotificacao()
const { confirmarExclusao } = useConfirmacao()

const formularioAberto = ref(false)
const motoristaSelecionado = ref(null)
const salvando = ref(false)

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
  } catch (erro) {
    tratar(erro)
  }
}
</script>
