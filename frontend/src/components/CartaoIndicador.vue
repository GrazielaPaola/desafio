<template>
  <div class="superficie indicador">
    <div class="indicador__topo">
      <span class="indicador__rotulo">{{ titulo }}</span>
      <span class="indicador__icone" :style="{ background: tonalidade, color: tinta }">
        <IconeSvg :nome="icone" :tamanho="16" />
      </span>
    </div>
    <div class="indicador__base">
      <span class="indicador__valor numero-tabular">{{ valor }}</span>
      <span v-if="nota" class="indicador__nota">{{ nota }}</span>
    </div>
  </div>
</template>

<script setup>
import IconeSvg from '@/components/IconeSvg.vue'

defineProps({
  titulo: { type: String, required: true },
  valor: { type: [Number, String], required: true },
  icone: { type: String, required: true },
  tonalidade: { type: String, required: true },
  tinta: { type: String, required: true },
  nota: { type: String, default: '' },
})
</script>

<style scoped>
.indicador {
  display: flex;
  flex-direction: column;
  gap: 16px;
  padding: 20px 22px;
}

.indicador__topo {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
}

.indicador__rotulo {
  font-size: 12.5px;
  font-weight: 600;
  color: var(--tinta-fraca);
}

.indicador__icone {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 30px;
  height: 30px;
  border-radius: var(--raio-acao);
}

.indicador__base {
  display: flex;
  align-items: baseline;
  gap: 8px;
}

.indicador__valor {
  font-size: 38px;
  font-weight: 800;
  letter-spacing: -0.03em;
  line-height: 1;
}

.indicador__nota {
  font-size: 12.5px;
  font-weight: 500;
  color: var(--tinta-apagada);
}
</style>
