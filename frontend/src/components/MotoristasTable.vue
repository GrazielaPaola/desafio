<template>
  <q-table
    v-model:pagination="paginacaoTabela"
    flat
    bordered
    row-key="id"
    :grid="$q.screen.lt.sm"
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
        <AcoesLinha @editar="emit('editar', props.row)" @excluir="emit('excluir', props.row)" />
      </q-td>
    </template>

    <template #item="props">
      <div class="col-12 q-pa-xs">
        <q-card flat bordered>
          <q-card-section class="row items-center justify-between">
            <div>
              <div class="text-subtitle1">{{ props.row.nome }}</div>
              <div class="text-caption text-grey-7">{{ props.row.total_coletas }} coleta(s)</div>
            </div>
            <div>
              <AcoesLinha
                @editar="emit('editar', props.row)"
                @excluir="emit('excluir', props.row)"
              />
            </div>
          </q-card-section>
        </q-card>
      </div>
    </template>

    <template #no-data>
      <div class="full-width column items-center q-pa-lg text-grey-7">
        <q-icon name="badge" size="48px" />
        <div class="q-mt-sm">Nenhum motorista encontrado</div>
      </div>
    </template>
  </q-table>
</template>

<script setup>
import AcoesLinha from '@/components/AcoesLinha.vue'
import { OPCOES_POR_PAGINA, usePaginacaoTabela } from '@/composables/usePaginacaoTabela'

const props = defineProps({
  motoristas: { type: Array, required: true },
  carregando: { type: Boolean, default: false },
  paginacao: { type: Object, required: true },
})

const emit = defineEmits(['solicitar', 'editar', 'excluir'])

const { paginacaoTabela, aoSolicitar } = usePaginacaoTabela(props, emit)

const colunas = [
  { name: 'nome', label: 'Nome', field: 'nome', align: 'left', sortable: true },
  {
    name: 'total_coletas',
    label: 'Coletas',
    field: 'total_coletas',
    align: 'center',
    sortable: true,
  },
  { name: 'acoes', label: '', field: 'id', align: 'right' },
]
</script>
