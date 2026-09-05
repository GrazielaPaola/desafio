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
      <q-btn unelevated class="botao-destaque barra__acao" no-caps @click="abrirFormulario()">
        <IconeSvg nome="mais" :tamanho="15" :espessura="2.4" class="q-mr-sm" />
        Novo motorista
      </q-btn>
    </div>

    <MotoristasGrid
      :motoristas="motoristas"
      :carregando="carregando"
      :paginacao="paginacao"
      @solicitar="carregarPagina"
      @editar="abrirFormulario"
      @excluir="excluirItem"
    />

    <MotoristaForm
      v-model="formularioAberto"
      :motorista="selecionado"
      :erros-campo="errosCampo"
      :mensagem-geral="mensagemGeral"
      :salvando="salvando"
      @salvar="salvarItem"
    />
  </q-page>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import CabecalhoPagina from '@/components/CabecalhoPagina.vue'
import CampoBusca from '@/components/CampoBusca.vue'
import IconeSvg from '@/components/IconeSvg.vue'
import MotoristasGrid from '@/components/MotoristasGrid.vue'
import MotoristaForm from '@/components/MotoristaForm.vue'
import { useCadastro } from '@/composables/useCadastro'
import { useMotoristasStore } from '@/stores/motoristas'

const store = useMotoristasStore()
const { motoristas, carregando, paginacao, filtros } = storeToRefs(store)

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
  salvar: store.salvar,
  excluir: store.excluir,
  mensagens: {
    criado: 'Motorista cadastrado',
    atualizado: 'Motorista atualizado',
    excluido: 'Motorista excluído',
    confirmarExclusao: (motorista) => `Excluir o motorista ${motorista.nome}?`,
  },
})

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
