<template>
  <svg
    :width="tamanho"
    :height="tamanho"
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    :stroke-width="espessura"
    stroke-linecap="round"
    stroke-linejoin="round"
    aria-hidden="true"
    focusable="false"
  >
    <path v-for="(traco, indice) in tracos" :key="indice" :d="traco" />
  </svg>
</template>

<script setup>
import { computed } from 'vue'
import { TRACOS_ICONES } from '@/utils/icones'

const props = defineProps({
  nome: { type: String, required: true },
  tamanho: { type: [Number, String], default: 17 },
  espessura: { type: [Number, String], default: 1.9 },
})

const tracos = computed(() => TRACOS_ICONES[props.nome] ?? [])
</script>
