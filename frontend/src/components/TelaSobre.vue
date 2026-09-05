<template>
  <q-dialog
    :model-value="modelValue"
    maximized
    transition-show="fade"
    transition-hide="fade"
    @update:model-value="emit('update:modelValue', $event)"
  >
    <div class="sobre" @click="emit('update:modelValue', false)">
      <div class="sobre__marca">
        <LogoOlfLog :largura="150" />
        <span class="sobre__produto">OlfLog</span>
        <span class="sobre__segmento">Agendamento de Coletas</span>
      </div>

      <div class="estrada" aria-hidden="true">
        <span
          v-for="(posicao, indice) in POSICOES_CAIXAS"
          :key="posicao"
          class="caixa"
          :class="`caixa--${indice + 1}`"
          :style="{ left: `${posicao}%` }"
        />

        <svg class="caminhao" viewBox="0 0 100 60" width="110" height="66">
          <rect x="2" y="10" width="58" height="36" rx="5" class="caminhao__bau" />
          <rect x="60" y="22" width="30" height="24" rx="5" class="caminhao__cabine" />
          <rect x="66" y="26" width="14" height="10" rx="2" class="caminhao__vidro" />
          <rect x="88" y="34" width="6" height="6" rx="1" class="caminhao__farol" />
          <g class="caminhao__roda caminhao__roda--traseira">
            <circle cx="18" cy="48" r="8" class="caminhao__pneu" />
            <circle cx="18" cy="48" r="3" class="caminhao__aro" />
          </g>
          <g class="caminhao__roda caminhao__roda--dianteira">
            <circle cx="76" cy="48" r="8" class="caminhao__pneu" />
            <circle cx="76" cy="48" r="3" class="caminhao__aro" />
          </g>
        </svg>

        <span class="estrada__faixa" />
      </div>

      <div class="sobre__creditos">
        <span class="sobre__autora">Desenvolvido por Graziela Paola</span>
        <span class="sobre__contexto">Teste técnico · Desenvolvedora Full Stack Júnior</span>
        <div class="sobre__stack">
          <span v-for="tecnologia in STACK" :key="tecnologia" class="sobre__tecnologia">
            {{ tecnologia }}
          </span>
        </div>
      </div>

      <span class="sobre__dica">Clique em qualquer lugar para voltar</span>
    </div>
  </q-dialog>
</template>

<script setup>
import LogoOlfLog from '@/components/LogoOlfLog.vue'

defineProps({
  modelValue: { type: Boolean, required: true },
})

const emit = defineEmits(['update:modelValue'])

const POSICOES_CAIXAS = [24, 50, 76]
const STACK = ['Vue 3 + Quasar', 'Laravel 13', 'MySQL 8', 'PHPUnit + Vitest']
</script>

<style scoped>
.sobre {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 44px;
  width: 100%;
  min-height: 100%;
  padding: 40px 24px;
  background: var(--escuro);
  color: var(--claro);
  cursor: pointer;
  user-select: none;
}

.sobre__marca {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
  animation: surgir 700ms ease-out both;
}

.sobre__produto {
  margin-top: 8px;
  font-size: 34px;
  font-weight: 800;
  letter-spacing: -0.02em;
}

.sobre__segmento {
  color: var(--claro-rotulo);
  font-size: 13px;
  font-weight: 600;
  letter-spacing: 0.12em;
  text-transform: uppercase;
}

.estrada {
  position: relative;
  width: min(720px, 100%);
  height: 96px;
  overflow: hidden;
}

.estrada__faixa {
  position: absolute;
  left: 0;
  right: 0;
  bottom: 14px;
  height: 3px;
  background: repeating-linear-gradient(90deg, var(--claro-tenue) 0 28px, transparent 28px 48px);
  animation: rolar-faixa 900ms linear infinite;
}

.caminhao {
  position: absolute;
  bottom: 12px;
  left: -130px;
  animation: atravessar 6s linear infinite;
}

.caminhao__bau {
  fill: var(--ambar);
}

.caminhao__cabine {
  fill: var(--claro);
}

.caminhao__vidro {
  fill: var(--escuro-trilha);
}

.caminhao__farol {
  fill: var(--ambar);
}

.caminhao__pneu {
  fill: var(--escuro-trilha);
  stroke: var(--claro-fraco);
  stroke-width: 2;
}

.caminhao__aro {
  fill: var(--claro-fraco);
}

.caminhao__roda {
  animation: girar 700ms linear infinite;
}

.caminhao__roda--traseira {
  transform-origin: 18px 48px;
}

.caminhao__roda--dianteira {
  transform-origin: 76px 48px;
}

.caixa {
  position: absolute;
  bottom: 18px;
  width: 16px;
  height: 16px;
  border: 2px solid var(--ambar);
  border-radius: 4px;
  background: var(--escuro-suave);
  transform: translateX(-50%);
}

.caixa--1 {
  animation: coletar-1 6s linear infinite;
}

.caixa--2 {
  animation: coletar-2 6s linear infinite;
}

.caixa--3 {
  animation: coletar-3 6s linear infinite;
}

.sobre__creditos {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  text-align: center;
  animation: surgir 700ms ease-out 250ms both;
}

.sobre__autora {
  font-size: 18px;
  font-weight: 700;
}

.sobre__contexto {
  color: var(--claro-fraco);
  font-size: 13.5px;
  font-weight: 500;
}

.sobre__stack {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 8px;
  margin-top: 10px;
}

.sobre__tecnologia {
  padding: 7px 12px;
  border-radius: 10px;
  background: var(--escuro-suave);
  color: var(--claro-texto);
  font-size: 12px;
  font-weight: 600;
}

.sobre__dica {
  position: absolute;
  bottom: 22px;
  color: var(--claro-tenue);
  font-size: 12px;
  font-weight: 500;
}

@keyframes atravessar {
  from {
    transform: translateX(0);
  }

  to {
    transform: translateX(calc(min(720px, 100vw) + 260px));
  }
}

@keyframes girar {
  to {
    transform: rotate(360deg);
  }
}

@keyframes rolar-faixa {
  to {
    background-position-x: -48px;
  }
}

@keyframes surgir {
  from {
    opacity: 0;
    transform: translateY(10px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes coletar-1 {
  0%,
  33% {
    opacity: 1;
    transform: translateX(-50%) scale(1);
  }

  36%,
  100% {
    opacity: 0;
    transform: translateX(-50%) scale(0.4);
  }
}

@keyframes coletar-2 {
  0%,
  53% {
    opacity: 1;
    transform: translateX(-50%) scale(1);
  }

  56%,
  100% {
    opacity: 0;
    transform: translateX(-50%) scale(0.4);
  }
}

@keyframes coletar-3 {
  0%,
  73% {
    opacity: 1;
    transform: translateX(-50%) scale(1);
  }

  76%,
  100% {
    opacity: 0;
    transform: translateX(-50%) scale(0.4);
  }
}

@media (prefers-reduced-motion: reduce) {
  .caminhao,
  .caminhao__roda,
  .estrada__faixa,
  .caixa,
  .sobre__marca,
  .sobre__creditos {
    animation: none;
  }

  .caminhao {
    left: calc(50% - 55px);
  }
}
</style>
