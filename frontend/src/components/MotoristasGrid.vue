<template>
  <q-table
    v-model:pagination="paginacaoTabela"
    grid
    flat
    class="tabela"
    row-key="id"
    :rows="motoristas"
    :columns="colunas"
    :loading="carregando"
    :rows-per-page-options="OPCOES_POR_PAGINA"
    rows-per-page-label="Por página"
    loading-label="Carregando motoristas..."
    @request="aoSolicitar"
  >
    <template #item="props">
      <div class="celula-cartao">
        <div class="superficie cartao">
          <div class="cartao__topo">
            <span class="cartao__avatar" :style="{ background: corDoMotorista(props.row.id) }">
              {{ iniciaisDoNome(props.row.nome) }}
            </span>
            <div class="cartao__identidade">
              <span class="cartao__nome">{{ props.row.nome }}</span>
              <span class="cartao__placa">{{
                props.row.placa_veiculo ?? 'Sem placa vinculada'
              }}</span>
            </div>
            <AcoesLinha
              class="cartao__acoes"
              @editar="emit('editar', props.row)"
              @excluir="emit('excluir', props.row)"
            />
          </div>

          <div class="cartao__rodape">
            <div class="cartao__medida">
              <span class="rotulo-miudo">Coletas</span>
              <span class="cartao__valor numero-tabular">{{ props.row.total_coletas }}</span>
            </div>
            <div class="cartao__medida">
              <span class="rotulo-miudo">Próxima</span>
              <span class="cartao__proxima numero-tabular">
                {{ apiParaTela(props.row.proxima_coleta) || '—' }}
              </span>
            </div>
            <span class="chip-status cartao__situacao">{{ situacao(props.row) }}</span>
          </div>
        </div>
      </div>
    </template>

    <template #no-data>
      <div class="estado-vazio">
        <IconeSvg nome="cracha" :tamanho="40" :espessura="1.4" />
        <span>Nenhum motorista encontrado</span>
      </div>
    </template>
  </q-table>
</template>

<script setup>
import AcoesLinha from '@/components/AcoesLinha.vue'
import IconeSvg from '@/components/IconeSvg.vue'
import { OPCOES_POR_PAGINA, usePaginacaoTabela } from '@/composables/usePaginacaoTabela'
import { corDoMotorista, iniciaisDoNome } from '@/utils/cores'
import { apiParaTela } from '@/utils/data'

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
]

function situacao(motorista) {
  return motorista.proxima_coleta ? 'Ativo' : 'Sem agenda'
}
</script>

<style scoped>
.celula-cartao {
  display: flex;
  min-width: 0;
}

.cartao {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 18px;
  height: 100%;
  padding: 20px;
}

.cartao__topo {
  display: flex;
  align-items: center;
  gap: 13px;
}

.cartao__avatar {
  display: flex;
  flex: 0 0 auto;
  align-items: center;
  justify-content: center;
  width: 44px;
  height: 44px;
  border-radius: var(--raio-botao);
  color: var(--claro);
  font-size: 14px;
  font-weight: 800;
}

.cartao__identidade {
  display: flex;
  flex-direction: column;
  gap: 3px;
  min-width: 0;
}

.cartao__nome {
  font-size: 14.5px;
  font-weight: 700;
  letter-spacing: -0.01em;
  line-height: 1.25;
}

.cartao__placa {
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.05em;
  color: var(--tinta-tenue);
}

.cartao__acoes {
  margin-left: auto;
}

.cartao__rodape {
  display: flex;
  align-items: center;
  gap: 14px;
  padding-top: 16px;
  border-top: 1px solid var(--divisor);
}

.cartao__medida {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.cartao__valor {
  font-size: 20px;
  font-weight: 800;
  line-height: 1.1;
}

.cartao__proxima {
  font-size: 13.5px;
  font-weight: 700;
  line-height: 1.1;
}

.cartao__situacao {
  margin-left: auto;
}
</style>
