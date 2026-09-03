<template>
  <q-page padding>
    <div class="row items-center justify-between q-mb-md q-gutter-y-sm">
      <div>
        <div class="text-h5">Agenda</div>
        <div class="text-caption text-grey-7">
          {{ formatarDataCompleta(inicioSemana) }} a {{ formatarDataCompleta(fimSemana) }}
        </div>
      </div>
      <q-btn-group outline>
        <q-btn outline color="primary" icon="chevron_left" @click="semanaAnterior">
          <q-tooltip>Semana anterior</q-tooltip>
        </q-btn>
        <q-btn outline color="primary" label="Hoje" @click="semanaAtual" />
        <q-btn outline color="primary" icon="chevron_right" @click="proximaSemana">
          <q-tooltip>Próxima semana</q-tooltip>
        </q-btn>
      </q-btn-group>
    </div>

    <div v-if="motoristasDaSemana.length" class="row q-gutter-xs q-mb-md">
      <q-chip
        v-for="motorista in motoristasDaSemana"
        :key="motorista.id"
        :color="corDoMotorista(motorista.id)"
        text-color="white"
        dense
      >
        {{ motorista.nome }}
      </q-chip>
    </div>

    <div class="row q-col-gutter-sm">
      <div v-for="dia in dias" :key="dia.getTime()" class="col-12 col-sm-6 col-md">
        <q-card flat bordered class="full-height" :class="{ 'agenda-hoje': ehHoje(dia) }">
          <q-card-section class="q-py-sm">
            <div class="text-caption text-grey-7">{{ formatarDiaDaSemana(dia) }}</div>
            <div class="text-subtitle1 text-weight-medium">{{ formatarDiaEMes(dia) }}</div>
          </q-card-section>
          <q-separator />
          <q-card-section class="q-py-sm column q-gutter-y-xs">
            <q-skeleton v-if="carregando" type="QChip" />
            <template v-else>
              <q-chip
                v-for="coleta in coletasDoDia(dia)"
                :key="coleta.id"
                :color="corDoMotorista(coleta.motorista_id)"
                text-color="white"
                dense
                class="agenda-coleta"
              >
                {{ coleta.fornecedor_nome }}
                <q-tooltip>
                  {{ coleta.fornecedor_nome }} → {{ coleta.cliente_nome }}<br />
                  {{ coleta.motorista.nome }} · {{ coleta.placa_veiculo }}
                </q-tooltip>
              </q-chip>
              <div v-if="coletasDoDia(dia).length === 0" class="text-caption text-grey-5">
                Sem coletas
              </div>
            </template>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script setup>
import { onMounted } from 'vue'
import { useAgenda } from '@/composables/useAgenda'
import { useErrosApi } from '@/composables/useErrosApi'
import { ehHoje, formatarDataCompleta, formatarDiaDaSemana, formatarDiaEMes } from '@/utils/data'

const {
  inicioSemana,
  fimSemana,
  dias,
  carregando,
  motoristasDaSemana,
  carregar,
  coletasDoDia,
  corDoMotorista,
  semanaAnterior,
  proximaSemana,
  semanaAtual,
} = useAgenda()

const { tratar } = useErrosApi()

onMounted(() => carregar().catch(tratar))
</script>

<style scoped>
.agenda-hoje {
  border-color: var(--q-primary);
  border-width: 2px;
}

.agenda-coleta {
  max-width: 100%;
}
</style>
