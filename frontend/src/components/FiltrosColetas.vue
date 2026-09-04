<template>
  <div class="filtros">
    <CampoBusca
      class="filtros__busca"
      :model-value="modelValue.busca"
      rotulo="Fornecedor, cliente ou CNPJ"
      @update:model-value="atualizar('busca', $event)"
    />

    <q-select
      class="filtros__motorista"
      :model-value="modelValue.motorista_id"
      :options="motoristas"
      option-value="id"
      option-label="nome"
      label="Todos os motoristas"
      emit-value
      map-options
      outlined
      dense
      clearable
      @update:model-value="atualizar('motorista_id', $event)"
    />

    <div class="controle filtros__periodo">
      <span class="controle__rotulo">De</span>
      <input
        class="controle__data"
        type="date"
        :value="modelValue.data_inicio"
        @change="atualizar('data_inicio', $event.target.value)"
      />
      <span class="controle__separador" />
      <span class="controle__rotulo">Até</span>
      <input
        class="controle__data"
        type="date"
        :value="modelValue.data_fim"
        @change="atualizar('data_fim', $event.target.value)"
      />
    </div>

    <q-btn
      v-if="possuiFiltros"
      flat
      class="botao-link filtros__limpar"
      label="Limpar"
      @click="emit('update:modelValue', { ...FILTROS_INICIAIS_COLETAS })"
    />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import CampoBusca from '@/components/CampoBusca.vue'
import { FILTROS_INICIAIS_COLETAS } from '@/composables/useColetas'

const props = defineProps({
  modelValue: { type: Object, required: true },
  motoristas: { type: Array, default: () => [] },
})

const emit = defineEmits(['update:modelValue'])

const possuiFiltros = computed(() =>
  Object.values(props.modelValue).some(
    (valor) => valor !== '' && valor !== null && valor !== undefined,
  ),
)

function atualizar(campo, valor) {
  emit('update:modelValue', { ...props.modelValue, [campo]: valor ?? '' })
}
</script>

<style scoped>
.filtros {
  display: contents;
}

.filtros__busca {
  flex: 1 1 280px;
  min-width: 220px;
}

.filtros__motorista {
  flex: 0 0 200px;
}

.filtros__motorista :deep(.q-field__control) {
  height: 46px;
  border-radius: var(--raio-controle);
  background: var(--superficie);
  font-size: 13.5px;
  font-weight: 600;
  color: var(--tinta-suave);
}

.filtros__motorista :deep(.q-field__control:before) {
  border-color: var(--borda-forte);
}

.filtros__periodo {
  flex: 0 0 auto;
  gap: 8px;
  padding: 0 14px;
}

.filtros__limpar {
  flex: 0 0 auto;
}
</style>
