<template>
  <q-input
    :model-value="modelValue"
    :label="label"
    :debounce="ATRASO_MS"
    dense
    outlined
    clearable
    @update:model-value="emit('update:modelValue', $event ?? '')"
  >
    <template #prepend>
      <q-icon name="search" />
    </template>
  </q-input>
</template>

<script setup>
defineProps({
  modelValue: { type: String, default: '' },
  label: { type: String, default: 'Buscar' },
})

const emit = defineEmits(['update:modelValue'])

const ATRASO_MS = 400
</script>
