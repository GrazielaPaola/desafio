<template>
  <q-btn flat round dense icon="edit" color="primary" @click="emit('editar')">
    <q-tooltip>Editar</q-tooltip>
  </q-btn>
  <q-btn flat round dense icon="delete" color="negative" @click="emit('excluir')">
    <q-tooltip>Excluir</q-tooltip>
  </q-btn>
</template>

<script setup>
const emit = defineEmits(['editar', 'excluir'])
</script>
