<template>
  <DialogoFormulario
    :model-value="modelValue"
    :titulo="titulo"
    :mensagem-geral="mensagemGeral"
    :salvando="salvando"
    @update:model-value="emit('update:modelValue', $event)"
    @enviar="enviar"
  >
    <q-input
      v-model="formulario.nome"
      label="Nome"
      outlined
      autofocus
      lazy-rules
      :rules="[obrigatorio, tamanhoMaximo(TAMANHO_MAXIMO_NOME)]"
      :error="Boolean(errosCampo.nome)"
      :error-message="errosCampo.nome"
    />
  </DialogoFormulario>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import DialogoFormulario from '@/components/DialogoFormulario.vue'
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
