<template>
  <svg
    :width="largura"
    :height="largura * PROPORCAO"
    viewBox="0 0 120 80"
    fill="none"
    stroke-linecap="round"
    stroke-linejoin="round"
    role="img"
    aria-label="OlfLog"
  >
    <path d="M58 24h30a14 14 0 0 1 14 14v8a14 14 0 0 1-14 14H58" class="logo__marca" />
    <path d="M10 29h44" class="logo__linha" />
    <path d="M36 43h36" class="logo__linha" />
    <path d="M44 57h20" class="logo__linha" />
  </svg>
</template>

<script setup>
defineProps({
  largura: { type: Number, default: 44 },
})

const PROPORCAO = 80 / 120
</script>

<style scoped>
.logo__marca {
  stroke: var(--ambar);
  stroke-width: 10;
}

.logo__linha {
  stroke: var(--claro);
  stroke-width: 8;
}
</style>
