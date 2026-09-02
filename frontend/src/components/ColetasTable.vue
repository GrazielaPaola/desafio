<template>
  <q-table
    v-model:pagination="paginacaoTabela"
    flat
    bordered
    row-key="id"
    :rows="coletas"
    :columns="colunas"
    :loading="carregando"
    :rows-per-page-options="OPCOES_POR_PAGINA"
    rows-per-page-label="Por página"
    loading-label="Carregando coletas..."
    @request="aoSolicitar"
  >
    <template #body-cell-fornecedor="props">
      <q-td :props="props">
        <div>{{ props.row.fornecedor_nome }}</div>
        <div class="text-caption text-grey-7">{{ props.row.fornecedor_cnpj }}</div>
      </q-td>
    </template>

    <template #body-cell-cliente="props">
      <q-td :props="props">
        <div>{{ props.row.cliente_nome }}</div>
        <div class="text-caption text-grey-7">{{ props.row.cliente_cnpj }}</div>
      </q-td>
    </template>

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
        <q-icon name="local_shipping" size="48px" />
        <div class="q-mt-sm">Nenhuma coleta agendada</div>
      </div>
    </template>
  </q-table>
</template>

<script setup>
import { OPCOES_POR_PAGINA, usePaginacaoTabela } from '@/composables/usePaginacaoTabela'
import { apiParaTela } from '@/utils/data'

const props = defineProps({
  coletas: { type: Array, required: true },
  carregando: { type: Boolean, default: false },
  paginacao: { type: Object, required: true },
})

const emit = defineEmits(['solicitar', 'editar', 'excluir'])

const { paginacaoTabela, aoSolicitar } = usePaginacaoTabela(props, emit)

const colunas = [
  { name: 'data', label: 'Data', field: 'data', align: 'left', format: apiParaTela },
  { name: 'fornecedor', label: 'Fornecedor', field: 'fornecedor_nome', align: 'left' },
  { name: 'cliente', label: 'Cliente', field: 'cliente_nome', align: 'left' },
  { name: 'motorista', label: 'Motorista', field: (linha) => linha.motorista?.nome, align: 'left' },
  { name: 'placa_veiculo', label: 'Placa', field: 'placa_veiculo', align: 'left' },
  { name: 'acoes', label: '', field: 'id', align: 'right' },
]
</script>
