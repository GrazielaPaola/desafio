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

          <div class="row q-col-gutter-md">
            <div class="col-12 col-sm-6">
              <q-input
                v-model="formulario.data"
                label="Data do agendamento"
                placeholder="DD/MM/AAAA"
                outlined
                lazy-rules
                :mask="MASCARA_DATA"
                :rules="[obrigatorio, dataValida, dataFutura]"
                :error="Boolean(errosCampo.data)"
                :error-message="errosCampo.data"
              >
                <template #append>
                  <q-icon name="event" class="cursor-pointer">
                    <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                      <q-date
                        v-model="formulario.data"
                        :mask="FORMATO_TELA"
                        :options="dataPermitidaNoCalendario"
                      >
                        <div class="row items-center justify-end">
                          <q-btn v-close-popup label="Fechar" color="primary" flat />
                        </div>
                      </q-date>
                    </q-popup-proxy>
                  </q-icon>
                </template>
              </q-input>
            </div>

            <div class="col-12 col-sm-6">
              <q-select
                v-model="formulario.motorista_id"
                label="Motorista"
                option-value="id"
                option-label="nome"
                outlined
                emit-value
                map-options
                lazy-rules
                :options="motoristas"
                :rules="[obrigatorio]"
                :error="Boolean(errosCampo.motorista_id)"
                :error-message="errosCampo.motorista_id"
              />
            </div>

            <div class="col-12 col-sm-6">
              <q-input
                v-model="formulario.fornecedor_nome"
                label="Nome do fornecedor"
                outlined
                lazy-rules
                :rules="[obrigatorio, tamanhoMaximo(TAMANHO_MAXIMO_NOME)]"
                :error="Boolean(errosCampo.fornecedor_nome)"
                :error-message="errosCampo.fornecedor_nome"
              />
            </div>

            <div class="col-12 col-sm-6">
              <q-input
                v-model="formulario.fornecedor_cnpj"
                label="CNPJ do fornecedor"
                placeholder="00.000.000/0000-00"
                outlined
                lazy-rules
                :mask="MASCARA_CNPJ"
                :rules="[obrigatorio, cnpjValido]"
                :error="Boolean(errosCampo.fornecedor_cnpj)"
                :error-message="errosCampo.fornecedor_cnpj"
              />
            </div>

            <div class="col-12 col-sm-6">
              <q-input
                v-model="formulario.cliente_nome"
                label="Nome do cliente"
                outlined
                lazy-rules
                :rules="[obrigatorio, tamanhoMaximo(TAMANHO_MAXIMO_NOME)]"
                :error="Boolean(errosCampo.cliente_nome)"
                :error-message="errosCampo.cliente_nome"
              />
            </div>

            <div class="col-12 col-sm-6">
              <q-input
                v-model="formulario.cliente_cnpj"
                label="CNPJ do cliente"
                placeholder="00.000.000/0000-00"
                outlined
                lazy-rules
                :mask="MASCARA_CNPJ"
                :rules="[obrigatorio, cnpjValido]"
                :error="Boolean(errosCampo.cliente_cnpj)"
                :error-message="errosCampo.cliente_cnpj"
              />
            </div>

            <div class="col-12 col-sm-6">
              <q-input
                :model-value="formulario.placa_veiculo"
                label="Placa do veículo"
                placeholder="ABC-1234 ou ABC1D23"
                outlined
                lazy-rules
                :maxlength="TAMANHO_MAXIMO_PLACA"
                :rules="[obrigatorio, placaValida]"
                :error="Boolean(errosCampo.placa_veiculo)"
                :error-message="errosCampo.placa_veiculo"
                @update:model-value="atualizarPlaca"
              />
            </div>
          </div>
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
import { MASCARA_CNPJ } from '@/utils/cnpj'
import {
  FORMATO_TELA,
  MASCARA_DATA,
  apiParaTela,
  dataPermitidaNoCalendario,
  telaParaApi,
} from '@/utils/data'
import {
  cnpjValido,
  dataFutura,
  dataValida,
  obrigatorio,
  placaValida,
  tamanhoMaximo,
} from '@/utils/validacoes'

const props = defineProps({
  modelValue: { type: Boolean, required: true },
  coleta: { type: Object, default: null },
  motoristas: { type: Array, default: () => [] },
  errosCampo: { type: Object, default: () => ({}) },
  mensagemGeral: { type: String, default: '' },
  salvando: { type: Boolean, default: false },
})

const emit = defineEmits(['update:modelValue', 'salvar'])

const TAMANHO_MAXIMO_NOME = 255
const TAMANHO_MAXIMO_PLACA = 8

const formulario = ref(formularioVazio())

const titulo = computed(() => (props.coleta ? 'Editar coleta' : 'Nova coleta'))

watch(
  () => props.modelValue,
  (aberto) => {
    if (aberto) formulario.value = props.coleta ? formularioDe(props.coleta) : formularioVazio()
  },
)

function formularioVazio() {
  return {
    data: '',
    fornecedor_nome: '',
    fornecedor_cnpj: '',
    cliente_nome: '',
    cliente_cnpj: '',
    motorista_id: null,
    placa_veiculo: '',
  }
}

function formularioDe(coleta) {
  return {
    data: apiParaTela(coleta.data),
    fornecedor_nome: coleta.fornecedor_nome,
    fornecedor_cnpj: coleta.fornecedor_cnpj,
    cliente_nome: coleta.cliente_nome,
    cliente_cnpj: coleta.cliente_cnpj,
    motorista_id: coleta.motorista_id,
    placa_veiculo: coleta.placa_veiculo,
  }
}

function atualizarPlaca(valor) {
  formulario.value.placa_veiculo = String(valor ?? '').toUpperCase()
}

function enviar() {
  emit('salvar', { ...formulario.value, data: telaParaApi(formulario.value.data) })
}
</script>

<style scoped>
.dialogo {
  max-width: 720px;
}
</style>
