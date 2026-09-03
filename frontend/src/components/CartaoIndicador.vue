<template>
  <q-card flat bordered class="full-height">
    <q-card-section class="row items-center no-wrap">
      <q-avatar :color="cor" text-color="white" :icon="icone" size="48px" />
      <div class="q-ml-md">
        <div class="text-caption text-grey-7">{{ titulo }}</div>
        <div class="text-h4 text-weight-medium">{{ valor }}</div>
        <div v-if="legenda" class="text-caption text-grey-6">{{ legenda }}</div>
      </div>
    </q-card-section>
  </q-card>
</template>

<script setup>
defineProps({
  titulo: { type: String, required: true },
  valor: { type: Number, required: true },
  icone: { type: String, required: true },
  cor: { type: String, default: 'primary' },
  legenda: { type: String, default: '' },
})
</script>
