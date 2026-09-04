<template>
  <q-table
    v-model:pagination="paginacaoTabela"
    flat
    class="tabela"
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
    <template #body-cell-data="props">
      <q-td :props="props">
        <span class="celula-data numero-tabular">{{ apiParaTela(props.row.data) }}</span>
      </q-td>
    </template>

    <template #body-cell-fornecedor_nome="props">
      <q-td :props="props">
        <div class="celula-empresa">
          <span class="celula-empresa__nome">{{ props.row.fornecedor_nome }}</span>
          <span class="celula-empresa__documento numero-tabular">
            {{ props.row.fornecedor_cnpj }}
          </span>
        </div>
      </q-td>
    </template>

    <template #body-cell-cliente_nome="props">
      <q-td :props="props">
        <div class="celula-empresa">
          <span class="celula-empresa__nome">{{ props.row.cliente_nome }}</span>
          <span class="celula-empresa__documento numero-tabular">
            {{ props.row.cliente_cnpj }}
          </span>
        </div>
      </q-td>
    </template>

    <template #body-cell-motorista="props">
      <q-td :props="props">
        <div class="celula-motorista">
          <span
            class="ponto-motorista"
            :style="{ background: corDoMotorista(props.row.motorista_id) }"
          />
          <span class="celula-motorista__nome">{{ props.row.motorista?.nome }}</span>
        </div>
      </q-td>
    </template>

    <template #body-cell-placa_veiculo="props">
      <q-td :props="props">
        <span class="chip-placa">{{ props.row.placa_veiculo }}</span>
      </q-td>
    </template>

    <template #body-cell-acoes="props">
      <q-td :props="props">
        <AcoesLinha @editar="emit('editar', props.row)" @excluir="emit('excluir', props.row)" />
      </q-td>
    </template>

    <template #item="props">
      <div class="celula-cartao">
        <div class="superficie cartao-coleta">
          <div class="cartao-coleta__topo">
            <span class="cartao-coleta__data numero-tabular">
              {{ apiParaTela(props.row.data) }}
            </span>
            <span class="chip-placa">{{ props.row.placa_veiculo }}</span>
          </div>

          <div class="cartao-coleta__bloco">
            <span class="rotulo-miudo">Fornecedor</span>
            <span class="cartao-coleta__nome">{{ props.row.fornecedor_nome }}</span>
            <span class="cartao-coleta__documento numero-tabular">
              {{ props.row.fornecedor_cnpj }}
            </span>
          </div>

          <div class="cartao-coleta__bloco">
            <span class="rotulo-miudo">Cliente</span>
            <span class="cartao-coleta__nome">{{ props.row.cliente_nome }}</span>
            <span class="cartao-coleta__documento numero-tabular">
              {{ props.row.cliente_cnpj }}
            </span>
          </div>

          <div class="cartao-coleta__rodape">
            <div class="celula-motorista">
              <span
                class="ponto-motorista"
                :style="{ background: corDoMotorista(props.row.motorista_id) }"
              />
              <span class="celula-motorista__nome">{{ props.row.motorista?.nome }}</span>
            </div>
            <AcoesLinha @editar="emit('editar', props.row)" @excluir="emit('excluir', props.row)" />
          </div>
        </div>
      </div>
    </template>

    <template #no-data>
      <div class="estado-vazio">
        <IconeSvg nome="caminhao" :tamanho="40" :espessura="1.4" />
        <span>Nenhuma coleta encontrada</span>
      </div>
    </template>
  </q-table>
</template>

<script setup>
import AcoesLinha from '@/components/AcoesLinha.vue'
import IconeSvg from '@/components/IconeSvg.vue'
import { OPCOES_POR_PAGINA, usePaginacaoTabela } from '@/composables/usePaginacaoTabela'
import { corDoMotorista } from '@/utils/cores'
import { apiParaTela } from '@/utils/data'

const props = defineProps({
  coletas: { type: Array, required: true },
  carregando: { type: Boolean, default: false },
  paginacao: { type: Object, required: true },
})

const emit = defineEmits(['solicitar', 'editar', 'excluir'])

const { paginacaoTabela, aoSolicitar } = usePaginacaoTabela(props, emit)

const colunas = [
  { name: 'data', label: 'Data', field: 'data', align: 'left', sortable: true },
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

<style scoped>
.celula-data {
  font-size: 13px;
  font-weight: 700;
}

.celula-empresa {
  display: flex;
  flex-direction: column;
  gap: 3px;
  min-width: 0;
}

.celula-empresa__nome {
  font-size: 13.5px;
  font-weight: 600;
}

.celula-empresa__documento {
  font-size: 11.5px;
  font-weight: 500;
  color: var(--tinta-apagada);
}

.celula-motorista {
  display: flex;
  align-items: center;
  gap: 9px;
  min-width: 0;
}

.celula-motorista__nome {
  font-size: 13px;
  font-weight: 600;
  color: var(--tinta-media);
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.celula-cartao {
  display: flex;
  min-width: 0;
}

.cartao-coleta {
  display: flex;
  flex-direction: column;
  gap: 14px;
  width: 100%;
  padding: 18px;
}

.cartao-coleta__topo {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
}

.cartao-coleta__data {
  font-size: 16px;
  font-weight: 800;
}

.cartao-coleta__bloco {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.cartao-coleta__nome {
  font-size: 13.5px;
  font-weight: 600;
}

.cartao-coleta__documento {
  font-size: 11.5px;
  font-weight: 500;
  color: var(--tinta-apagada);
}

.cartao-coleta__rodape {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  padding-top: 14px;
  border-top: 1px solid var(--divisor);
}
</style>
