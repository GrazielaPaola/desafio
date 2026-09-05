<template>
  <div class="empresa">
    <span v-if="rotulo" class="rotulo-miudo">{{ rotulo }}</span>
    <span class="empresa__nome">{{ nome }}</span>
    <span class="empresa__documento numero-tabular">{{ documento }}</span>
  </div>
</template>

<script setup>
defineProps({
  nome: { type: String, required: true },
  documento: { type: String, required: true },
  rotulo: { type: String, default: '' },
})
</script>

<style scoped>
.empresa {
  display: flex;
  flex-direction: column;
  gap: 3px;
  min-width: 0;
}

.empresa__nome {
  font-size: 13.5px;
  font-weight: 600;
}

.empresa__documento {
  font-size: 11.5px;
  font-weight: 500;
  color: var(--tinta-apagada);
}
</style>
