<template>
  <q-page padding>
    <div class="row items-center justify-between q-mb-md">
      <div class="text-h5">Visão geral</div>
      <q-btn color="primary" icon="local_shipping" label="Ver coletas" :to="{ name: 'coletas' }" />
    </div>

    <div v-if="carregando" class="row justify-center q-pa-xl">
      <q-spinner size="48px" color="primary" />
    </div>

    <template v-else-if="resumo">
      <div class="row q-col-gutter-md q-mb-lg">
        <div class="col-12 col-sm-6 col-md-3">
          <CartaoIndicador
            titulo="Coletas hoje"
            :valor="resumo.coletas_hoje"
            icone="today"
            cor="primary"
          />
        </div>
        <div class="col-12 col-sm-6 col-md-3">
          <CartaoIndicador
            :titulo="`Próximos ${resumo.dias_proximos} dias`"
            :valor="resumo.coletas_proximos_dias"
            icone="date_range"
            cor="secondary"
          />
        </div>
        <div class="col-12 col-sm-6 col-md-3">
          <CartaoIndicador
            titulo="Coletas futuras"
            :valor="resumo.coletas_futuras"
            icone="local_shipping"
            cor="deep-orange"
          />
        </div>
        <div class="col-12 col-sm-6 col-md-3">
          <CartaoIndicador
            titulo="Motoristas com coletas"
            :valor="resumo.motoristas_com_coletas"
            :legenda="`de ${resumo.total_motoristas} cadastrados`"
            icone="badge"
            cor="purple"
          />
        </div>
      </div>

      <q-card flat bordered>
        <q-card-section class="row items-center justify-between">
          <div class="text-subtitle1">Próximas coletas</div>
          <q-btn flat dense color="primary" label="Ver agenda" :to="{ name: 'agenda' }" />
        </q-card-section>
        <q-separator />

        <q-list v-if="resumo.proximas_coletas.length" separator>
          <q-item v-for="coleta in resumo.proximas_coletas" :key="coleta.id">
            <q-item-section avatar>
              <q-chip color="primary" text-color="white" dense>
                {{ apiParaTela(coleta.data) }}
              </q-chip>
            </q-item-section>
            <q-item-section>
              <q-item-label>{{ coleta.fornecedor_nome }} → {{ coleta.cliente_nome }}</q-item-label>
              <q-item-label caption>
                {{ coleta.motorista.nome }} · {{ coleta.placa_veiculo }}
              </q-item-label>
            </q-item-section>
          </q-item>
        </q-list>

        <q-card-section v-else class="text-center text-grey-7">
          Nenhuma coleta futura agendada
        </q-card-section>
      </q-card>
    </template>
  </q-page>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import CartaoIndicador from '@/components/CartaoIndicador.vue'
import { useErrosApi } from '@/composables/useErrosApi'
import { resumoService } from '@/services/resumoService'
import { apiParaTela } from '@/utils/data'

const resumo = ref(null)
const carregando = ref(false)
const { tratar } = useErrosApi()

onMounted(carregarResumo)

async function carregarResumo() {
  carregando.value = true

  try {
    resumo.value = await resumoService.obter()
  } catch (erro) {
    tratar(erro)
  } finally {
    carregando.value = false
  }
}
</script>
