<template>
  <img :src="origem" :width="largura" alt="OlfLog" class="logo" draggable="false" />
</template>

<script setup>
import { computed } from 'vue'
import logoCompleto from '@/assets/logo-olflog.svg'
import simbolo from '@/assets/simbolo-olflog.svg'

const props = defineProps({
  variante: { type: String, default: 'completa' },
  largura: { type: Number, default: 180 },
})

const origem = computed(() => (props.variante === 'simbolo' ? simbolo : logoCompleto))
</script>

<style scoped>
.logo {
  display: block;
  max-width: 100%;
  height: auto;
  user-select: none;
}
</style>
