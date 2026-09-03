<template>
  <div class="row q-col-gutter-sm items-center">
    <div class="col-12 col-md-4">
      <CampoBusca
        :model-value="modelValue.busca"
        label="Fornecedor, cliente ou CNPJ"
        @update:model-value="atualizar('busca', $event)"
      />
    </div>

    <div class="col-12 col-sm-4 col-md-3">
      <q-select
        :model-value="modelValue.motorista_id"
        :options="motoristas"
        option-value="id"
        option-label="nome"
        label="Motorista"
        emit-value
        map-options
        dense
        outlined
        clearable
        @update:model-value="atualizar('motorista_id', $event)"
      />
    </div>

    <div class="col-6 col-sm-4 col-md-2">
      <q-input
        :model-value="modelValue.data_inicio"
        type="date"
        label="De"
        stack-label
        dense
        outlined
        @update:model-value="atualizar('data_inicio', $event)"
      />
    </div>

    <div class="col-6 col-sm-4 col-md-2">
      <q-input
        :model-value="modelValue.data_fim"
        type="date"
        label="Até"
        stack-label
        dense
        outlined
        @update:model-value="atualizar('data_fim', $event)"
      />
    </div>

    <div class="col-12 col-md-1">
      <q-btn
        v-if="possuiFiltros"
        flat
        dense
        color="grey-8"
        icon="filter_alt_off"
        label="Limpar"
        @click="emit('update:modelValue', { ...FILTROS_INICIAIS_COLETAS })"
      />
    </div>
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
