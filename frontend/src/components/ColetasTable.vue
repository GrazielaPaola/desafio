<template>
  <q-table
    v-model:pagination="paginacaoTabela"
    flat
    bordered
    row-key="id"
    :grid="$q.screen.lt.md"
    :rows="coletas"
    :columns="colunas"
    :loading="carregando"
    :rows-per-page-options="OPCOES_POR_PAGINA"
    rows-per-page-label="Por página"
    loading-label="Carregando coletas..."
    @request="aoSolicitar"
  >
    <template #body-cell-fornecedor_nome="props">
      <q-td :props="props">
        <div>{{ props.row.fornecedor_nome }}</div>
        <div class="text-caption text-grey-7">{{ props.row.fornecedor_cnpj }}</div>
      </q-td>
    </template>

    <template #body-cell-cliente_nome="props">
      <q-td :props="props">
        <div>{{ props.row.cliente_nome }}</div>
        <div class="text-caption text-grey-7">{{ props.row.cliente_cnpj }}</div>
      </q-td>
    </template>

    <template #body-cell-acoes="props">
      <q-td :props="props" class="text-right">
        <AcoesLinha @editar="emit('editar', props.row)" @excluir="emit('excluir', props.row)" />
      </q-td>
    </template>

    <template #item="props">
      <div class="col-12 col-sm-6 q-pa-xs">
        <q-card flat bordered>
          <q-card-section class="row items-center justify-between q-pb-none">
            <div class="text-subtitle1 text-weight-medium">{{ apiParaTela(props.row.data) }}</div>
            <q-badge color="primary" :label="props.row.placa_veiculo" />
          </q-card-section>
          <q-card-section class="q-gutter-y-sm">
            <div>
              <div class="text-caption text-grey-7">Fornecedor</div>
              <div>{{ props.row.fornecedor_nome }}</div>
              <div class="text-caption">{{ props.row.fornecedor_cnpj }}</div>
            </div>
            <div>
              <div class="text-caption text-grey-7">Cliente</div>
              <div>{{ props.row.cliente_nome }}</div>
              <div class="text-caption">{{ props.row.cliente_cnpj }}</div>
            </div>
            <div>
              <div class="text-caption text-grey-7">Motorista</div>
              <div>{{ props.row.motorista?.nome }}</div>
            </div>
          </q-card-section>
          <q-separator />
          <q-card-actions align="right">
            <AcoesLinha @editar="emit('editar', props.row)" @excluir="emit('excluir', props.row)" />
          </q-card-actions>
        </q-card>
      </div>
    </template>

    <template #no-data>
      <div class="full-width column items-center q-pa-lg text-grey-7">
        <q-icon name="local_shipping" size="48px" />
        <div class="q-mt-sm">Nenhuma coleta encontrada</div>
      </div>
    </template>
  </q-table>
</template>

<script setup>
import AcoesLinha from '@/components/AcoesLinha.vue'
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
  {
    name: 'data',
    label: 'Data',
    field: 'data',
    align: 'left',
    sortable: true,
    format: apiParaTela,
  },
  {
    name: 'fornecedor_nome',
    label: 'Fornecedor',
    field: 'fornecedor_nome',
    align: 'left',
    sortable: true,
  },
  { name: 'cliente_nome', label: 'Cliente', field: 'cliente_nome', align: 'left', sortable: true },
  {
    name: 'motorista',
    label: 'Motorista',
    field: (linha) => linha.motorista?.nome,
    align: 'left',
    sortable: true,
  },
  { name: 'placa_veiculo', label: 'Placa', field: 'placa_veiculo', align: 'left', sortable: true },
  { name: 'acoes', label: '', field: 'id', align: 'right' },
]
</script>
