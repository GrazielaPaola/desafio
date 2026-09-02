<template>
  <q-dialog
    :model-value="modelValue"
    persistent
    @update:model-value="emit('update:modelValue', $event)"
  >
    <q-card class="formulario">
      <q-form @submit="enviar">
        <q-card-section>
          <div class="text-h6">{{ titulo }}</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <q-banner v-if="mensagemGeral" dense rounded class="bg-negative text-white q-mb-md">
            {{ mensagemGeral }}
          </q-banner>

          <q-input
            v-model="formulario.nome"
            label="Nome"
            autofocus
            lazy-rules
            :rules="[obrigatorio, tamanhoMaximo(TAMANHO_MAXIMO_NOME)]"
            :error="Boolean(errosCampo.nome)"
            :error-message="errosCampo.nome"
          />
        </q-card-section>

        <q-card-actions align="right">
          <q-btn
            flat
            label="Cancelar"
            :disable="salvando"
            @click="emit('update:modelValue', false)"
          />
          <q-btn type="submit" color="primary" label="Salvar" :loading="salvando" />
        </q-card-actions>
      </q-form>
    </q-card>
  </q-dialog>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import { obrigatorio, tamanhoMaximo } from '@/utils/validacoes'

const props = defineProps({
  modelValue: { type: Boolean, required: true },
  motorista: { type: Object, default: null },
  errosCampo: { type: Object, default: () => ({}) },
  mensagemGeral: { type: String, default: '' },
  salvando: { type: Boolean, default: false },
})

const emit = defineEmits(['update:modelValue', 'salvar'])

const TAMANHO_MAXIMO_NOME = 255

const formulario = ref({ nome: '' })

const titulo = computed(() => (props.motorista ? 'Editar motorista' : 'Novo motorista'))

watch(
  () => props.modelValue,
  (aberto) => {
    if (aberto) formulario.value = { nome: props.motorista?.nome ?? '' }
  },
)

function enviar() {
  emit('salvar', { ...formulario.value })
}
</script>

<style scoped>
.formulario {
  width: 100%;
  max-width: 480px;
}
</style>
