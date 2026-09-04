<template>
  <section class="painel-escuro carga">
    <h2 class="carga__titulo">Carga por motorista</h2>

    <div v-for="motorista in motoristas" :key="motorista.id" class="carga__item">
      <div class="carga__linha">
        <span class="carga__nome">{{ motorista.nome }}</span>
        <span class="carga__contagem numero-tabular">{{ motorista.coletas_futuras }}</span>
      </div>
      <div class="carga__trilha">
        <div
          class="carga__barra"
          :style="{
            width: proporcao(motorista.coletas_futuras),
            background: corDoMotorista(motorista.id),
          }"
        />
      </div>
    </div>

    <p v-if="motoristas.length === 0" class="carga__vazio">Nenhum motorista cadastrado</p>

    <q-btn
      class="botao-destaque carga__acao"
      unelevated
      label="Ver todas as coletas"
      :to="{ name: 'coletas' }"
    />
  </section>
</template>

<script setup>
import { computed } from 'vue'
import { corDoMotorista } from '@/utils/cores'

const props = defineProps({
  motoristas: { type: Array, required: true },
})

const maiorCarga = computed(() =>
  Math.max(1, ...props.motoristas.map((motorista) => motorista.coletas_futuras)),
)

function proporcao(coletas) {
  return `${Math.round((coletas / maiorCarga.value) * 100)}%`
}
</script>

<style scoped>
.carga {
  display: flex;
  flex-direction: column;
  gap: 18px;
  padding: 24px;
}

.carga__titulo {
  margin: 0;
  font-size: 16px;
  font-weight: 700;
  letter-spacing: -0.01em;
  color: var(--claro);
}

.carga__item {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.carga__linha {
  display: flex;
  align-items: baseline;
  gap: 10px;
}

.carga__nome {
  font-size: 13px;
  font-weight: 600;
  color: var(--claro-texto);
}

.carga__contagem {
  margin-left: auto;
  font-size: 13px;
  font-weight: 700;
  color: var(--claro);
}

.carga__trilha {
  height: 7px;
  border-radius: 6px;
  background: var(--escuro-trilha);
  overflow: hidden;
}

.carga__barra {
  height: 100%;
  border-radius: 6px;
  transition: width 220ms ease;
}

.carga__vazio {
  margin: 0;
  color: var(--claro-fraco);
  font-size: 13px;
  font-weight: 500;
}

.carga__acao {
  margin-top: 6px;
  width: 100%;
}
</style>
