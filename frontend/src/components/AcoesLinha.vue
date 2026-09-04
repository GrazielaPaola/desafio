<template>
  <div class="acoes">
    <q-btn flat class="botao-acao botao-acao--editar" aria-label="Editar" @click="emit('editar')">
      <IconeSvg nome="editar" :tamanho="14" :espessura="2" />
      <q-tooltip>Editar</q-tooltip>
    </q-btn>
    <q-btn
      flat
      class="botao-acao botao-acao--excluir"
      aria-label="Excluir"
      @click="emit('excluir')"
    >
      <IconeSvg nome="excluir" :tamanho="14" :espessura="2" />
      <q-tooltip>Excluir</q-tooltip>
    </q-btn>
  </div>
</template>

<script setup>
import IconeSvg from '@/components/IconeSvg.vue'

const emit = defineEmits(['editar', 'excluir'])
</script>

<style scoped>
.acoes {
  display: flex;
  gap: 6px;
  justify-content: flex-end;
}
</style>
