<template>
  <section class="superficie superficie--secao proximas">
    <div class="proximas__topo">
      <h2 class="titulo-secao">Próximas coletas</h2>
      <q-btn flat class="botao-link" label="Ver agenda" :to="{ name: 'agenda' }" />
    </div>

    <div v-for="coleta in coletas" :key="coleta.id" class="proximas__linha">
      <div class="proximas__data">
        <span class="proximas__dia numero-tabular">{{ diaDe(coleta.data) }}</span>
        <span class="proximas__mes">{{ mesDe(coleta.data) }}</span>
      </div>
      <div class="proximas__descricao">
        <span class="proximas__rota">
          {{ coleta.fornecedor_nome }} &rarr; {{ coleta.cliente_nome }}
        </span>
        <span class="proximas__meta">
          {{ coleta.motorista.nome }} &middot; {{ coleta.placa_veiculo }}
        </span>
      </div>
      <span class="ponto-motorista" :style="{ background: corDoMotorista(coleta.motorista_id) }" />
    </div>

    <p v-if="coletas.length === 0" class="proximas__vazio">Nenhuma coleta futura agendada</p>
  </section>
</template>

<script setup>
import { corDoMotorista } from '@/utils/cores'
import { diaDe, mesDe } from '@/utils/data'

defineProps({
  coletas: { type: Array, required: true },
})
</script>

<style scoped>
.proximas {
  padding: 22px 24px 14px;
}

.proximas__topo {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 6px;
}

.proximas__topo .botao-link {
  margin-left: auto;
}

.proximas__linha {
  display: flex;
  align-items: center;
  gap: 18px;
  padding: 15px 0;
  border-top: 1px solid var(--divisor);
}

.proximas__data {
  display: flex;
  flex: 0 0 60px;
  flex-direction: column;
  align-items: center;
  gap: 2px;
  padding: 8px 0;
  border-radius: var(--raio-botao);
  background: var(--superficie-suave);
}

.proximas__dia {
  font-size: 17px;
  font-weight: 800;
  line-height: 1;
}

.proximas__mes {
  font-size: 10.5px;
  font-weight: 600;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  color: var(--tinta-tenue);
}

.proximas__descricao {
  display: flex;
  flex-direction: column;
  gap: 4px;
  min-width: 0;
}

.proximas__rota {
  font-size: 14.5px;
  font-weight: 700;
  letter-spacing: -0.01em;
}

.proximas__meta {
  font-size: 12.5px;
  font-weight: 500;
  color: var(--tinta-tenue);
}

.proximas__linha .ponto-motorista {
  width: 9px;
  height: 9px;
  margin-left: auto;
}

.proximas__vazio {
  margin: 0;
  padding: 26px 0 20px;
  border-top: 1px solid var(--divisor);
  text-align: center;
  color: var(--tinta-vazia);
  font-size: 13.5px;
  font-weight: 500;
}
</style>
