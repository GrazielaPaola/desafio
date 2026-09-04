<template>
  <q-page class="pagina">
    <CabecalhoPagina titulo="Visão geral" :subtitulo="subtitulo" />

    <div v-if="carregando && !resumo" class="row justify-center q-pa-xl">
      <q-spinner size="42px" color="primary" />
    </div>

    <template v-else-if="resumo">
      <div class="indicadores">
        <CartaoIndicador
          v-for="indicador in indicadores"
          :key="indicador.titulo"
          v-bind="indicador"
        />
      </div>

      <div class="conteudo">
        <ProximasColetas :coletas="resumo.proximas_coletas" />
        <CargaMotoristas :motoristas="resumo.carga_motoristas" />
      </div>
    </template>
  </q-page>
</template>

<script setup>
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import CabecalhoPagina from '@/components/CabecalhoPagina.vue'
import CartaoIndicador from '@/components/CartaoIndicador.vue'
import ProximasColetas from '@/components/ProximasColetas.vue'
import CargaMotoristas from '@/components/CargaMotoristas.vue'
import { useResumoStore } from '@/stores/resumo'
import { adicionarDias, formatarDataCompleta, formatarDiaEMes, inicioDaSemana } from '@/utils/data'

const DIAS_ATE_O_FIM_DA_SEMANA = 6

const { resumo, carregando } = storeToRefs(useResumoStore())

const subtitulo = computed(() => {
  const inicio = inicioDaSemana(new Date())
  const fim = adicionarDias(inicio, DIAS_ATE_O_FIM_DA_SEMANA)
  return `Operação de coletas — semana de ${formatarDiaEMes(inicio)} a ${formatarDataCompleta(fim)}`
})

const indicadores = computed(() => [
  {
    titulo: 'Coletas hoje',
    valor: resumo.value.coletas_hoje,
    icone: 'calendario',
    tonalidade: '#f5eddc',
    tinta: '#a97a00',
    nota: formatarDiaEMes(new Date()),
  },
  {
    titulo: `Próximos ${resumo.value.dias_proximos} dias`,
    valor: resumo.value.coletas_proximos_dias,
    icone: 'caminhao',
    tonalidade: '#e2efec',
    tinta: '#12796d',
    nota: 'agendadas',
  },
  {
    titulo: 'Coletas futuras',
    valor: resumo.value.coletas_futuras,
    icone: 'painel',
    tonalidade: '#f0ece0',
    tinta: '#7d7a72',
    nota: 'no total',
  },
  {
    titulo: 'Motoristas com coletas',
    valor: resumo.value.motoristas_com_coletas,
    icone: 'cracha',
    tonalidade: '#ebe6f3',
    tinta: '#6b4ea8',
    nota: `de ${resumo.value.total_motoristas} cadastrados`,
  },
])
</script>

<style scoped>
.indicadores {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 16px;
}

.conteudo {
  display: grid;
  grid-template-columns: minmax(0, 1.55fr) minmax(0, 1fr);
  gap: 16px;
  align-items: start;
}

@media (max-width: 1023px) {
  .conteudo {
    grid-template-columns: minmax(0, 1fr);
  }
}
</style>
