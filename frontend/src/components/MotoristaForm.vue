<template>
  <q-dialog
    :model-value="modelValue"
    persistent
    @update:model-value="emit('update:modelValue', $event)"
  >
    <q-card class="cartao-dialogo dialogo">
      <q-form @submit="enviar">
        <div class="dialogo__cabecalho">
          <h2 class="dialogo__titulo">{{ titulo }}</h2>
          <q-btn
            flat
            class="botao-acao"
            aria-label="Fechar"
            @click="emit('update:modelValue', false)"
          >
            <IconeSvg nome="fechar" :tamanho="14" :espessura="2" />
          </q-btn>
        </div>

        <div class="dialogo__corpo">
          <div v-if="mensagemGeral" class="aviso-erro">{{ mensagemGeral }}</div>

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
        </div>

        <div class="dialogo__rodape">
          <q-btn
            flat
            class="botao-contorno"
            label="Cancelar"
            no-caps
            :disable="salvando"
            @click="emit('update:modelValue', false)"
          />
          <q-btn
            type="submit"
            unelevated
            class="botao-destaque"
            label="Salvar"
            no-caps
            :loading="salvando"
          />
        </div>
      </q-form>
    </q-card>
  </q-dialog>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import IconeSvg from '@/components/IconeSvg.vue'
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
.dialogo {
  max-width: 480px;
}
</style>
