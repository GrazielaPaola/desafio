<template>
  <q-dialog
    :model-value="modelValue"
    persistent
    @update:model-value="emit('update:modelValue', $event)"
  >
    <q-card class="cartao-dialogo" :style="{ maxWidth: largura }">
      <q-form greedy @submit="emit('enviar')">
        <div class="dialogo__cabecalho">
          <h2 class="dialogo__titulo">{{ titulo }}</h2>
          <q-btn flat class="botao-acao" aria-label="Fechar" @click="fechar">
            <IconeSvg nome="fechar" :tamanho="14" :espessura="2" />
          </q-btn>
        </div>

        <div class="dialogo__corpo">
          <div v-if="mensagemGeral" class="aviso-erro">{{ mensagemGeral }}</div>
          <slot />
        </div>

        <div class="dialogo__rodape">
          <q-btn
            flat
            class="botao-contorno"
            label="Cancelar"
            no-caps
            :disable="salvando"
            @click="fechar"
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
import IconeSvg from '@/components/IconeSvg.vue'

defineProps({
  modelValue: { type: Boolean, required: true },
  titulo: { type: String, required: true },
  mensagemGeral: { type: String, default: '' },
  salvando: { type: Boolean, default: false },
  largura: { type: String, default: '480px' },
})

const emit = defineEmits(['update:modelValue', 'enviar'])

function fechar() {
  emit('update:modelValue', false)
}
</script>
