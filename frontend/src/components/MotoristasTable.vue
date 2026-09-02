<template>
  <q-table
    v-model:pagination="paginacaoTabela"
    flat
    bordered
    row-key="id"
    :rows="motoristas"
    :columns="colunas"
    :loading="carregando"
    :rows-per-page-options="OPCOES_POR_PAGINA"
    rows-per-page-label="Por página"
    loading-label="Carregando motoristas..."
    @request="aoSolicitar"
  >
    <template #body-cell-acoes="props">
      <q-td :props="props" class="text-right">
        <q-btn flat round dense icon="edit" color="primary" @click="emit('editar', props.row)">
          <q-tooltip>Editar</q-tooltip>
        </q-btn>
        <q-btn flat round dense icon="delete" color="negative" @click="emit('excluir', props.row)">
          <q-tooltip>Excluir</q-tooltip>
        </q-btn>
      </q-td>
    </template>

    <template #no-data>
      <div class="full-width column items-center q-pa-lg text-grey-7">
        <q-icon name="badge" size="48px" />
        <div class="q-mt-sm">Nenhum motorista cadastrado</div>
      </div>
    </template>
  </q-table>
</template>

<script setup>
import { OPCOES_POR_PAGINA, usePaginacaoTabela } from '@/composables/usePaginacaoTabela'

const props = defineProps({
  motoristas: { type: Array, required: true },
  carregando: { type: Boolean, default: false },
  paginacao: { type: Object, required: true },
})

const emit = defineEmits(['solicitar', 'editar', 'excluir'])

const { paginacaoTabela, aoSolicitar } = usePaginacaoTabela(props, emit)

const colunas = [
  { name: 'nome', label: 'Nome', field: 'nome', align: 'left' },
  { name: 'total_coletas', label: 'Coletas', field: 'total_coletas', align: 'center' },
  { name: 'acoes', label: '', field: 'id', align: 'right' },
]
</script>
