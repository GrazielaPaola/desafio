<template>
  <div class="controle busca">
    <IconeSvg nome="busca" :tamanho="16" class="busca__icone" />
    <q-input
      :model-value="modelValue"
      :placeholder="rotulo"
      :debounce="ATRASO_MS"
      borderless
      dense
      class="busca__campo"
      @update:model-value="emit('update:modelValue', $event ?? '')"
    />
  </div>
</template>

<script setup>
import IconeSvg from '@/components/IconeSvg.vue'

defineProps({
  modelValue: { type: String, default: '' },
  rotulo: { type: String, default: 'Buscar' },
})

const emit = defineEmits(['update:modelValue'])

const ATRASO_MS = 400
</script>

<style scoped>
.busca {
  min-width: 0;
}

.busca__icone {
  flex: 0 0 auto;
  color: var(--tinta-apagada);
}

.busca__campo {
  flex: 1;
  min-width: 0;
}

.busca__campo :deep(.q-field__control) {
  height: 44px;
}

.busca__campo :deep(.q-field__native) {
  font-size: 13.5px;
  font-weight: 500;
  color: var(--tinta);
  padding: 0;
}

.busca__campo :deep(.q-field__native::placeholder) {
  color: var(--tinta-apagada);
}
</style>
