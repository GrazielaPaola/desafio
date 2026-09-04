<template>
  <q-page class="pagina">
    <CabecalhoPagina titulo="Agenda" :subtitulo="subtitulo" />

    <div class="barra">
      <div class="legenda">
        <span v-for="motorista in motoristasDaSemana" :key="motorista.id" class="chip-legenda">
          <span class="ponto-motorista" :style="{ background: corDoMotorista(motorista.id) }" />
          {{ motorista.nome }}
        </span>
      </div>

      <div class="barra__navegacao">
        <q-btn
          flat
          class="botao-icone"
          aria-label="Semana anterior"
          @click="irPara(semanaAnterior)"
        >
          <IconeSvg nome="anterior" :tamanho="15" :espessura="2.2" />
        </q-btn>
        <q-btn unelevated class="botao-escuro" label="Hoje" @click="irPara(semanaAtual)" />
        <q-btn flat class="botao-icone" aria-label="Próxima semana" @click="irPara(proximaSemana)">
          <IconeSvg nome="proximo" :tamanho="15" :espessura="2.2" />
        </q-btn>
      </div>
    </div>

    <div class="rolagem">
      <div class="semana">
        <div
          v-for="dia in dias"
          :key="dia.getTime()"
          class="dia"
          :class="{ 'dia--hoje': ehHoje(dia) }"
        >
          <div class="dia__cabecalho">
            <span class="dia__semana">{{ formatarDiaDaSemana(dia) }}</span>
            <span class="dia__data numero-tabular">{{ formatarDiaEMes(dia) }}</span>
          </div>

          <q-skeleton v-if="carregando" type="rect" height="52px" />

          <template v-else>
            <div
              v-for="coleta in coletasDoDia(dia)"
              :key="coleta.id"
              class="compromisso"
              :style="{ borderLeftColor: corDoMotorista(coleta.motorista_id) }"
            >
              <span class="compromisso__titulo">{{ coleta.fornecedor_nome }}</span>
              <span class="compromisso__motorista">{{ coleta.motorista.nome }}</span>
              <q-tooltip>
                {{ coleta.fornecedor_nome }} &rarr; {{ coleta.cliente_nome }}<br />
                {{ coleta.motorista.nome }} &middot; {{ coleta.placa_veiculo }}
              </q-tooltip>
            </div>

            <span v-if="coletasDoDia(dia).length === 0" class="dia__vazio">Sem coletas</span>
          </template>
        </div>
      </div>
    </div>
  </q-page>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import CabecalhoPagina from '@/components/CabecalhoPagina.vue'
import IconeSvg from '@/components/IconeSvg.vue'
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

const subtitulo = computed(
  () => `${formatarDataCompleta(inicioSemana.value)} a ${formatarDataCompleta(fimSemana.value)}`,
)

onMounted(() => carregar().catch(tratar))

function irPara(navegar) {
  navegar().catch(tratar)
}
</script>

<style scoped>
.barra {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 12px;
}

.legenda {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.barra__navegacao {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-left: auto;
}

.rolagem {
  overflow-x: auto;
  padding-bottom: 4px;
}

.semana {
  display: grid;
  grid-template-columns: repeat(7, minmax(0, 1fr));
  gap: 12px;
  min-width: 980px;
  align-items: stretch;
}

.dia {
  display: flex;
  flex-direction: column;
  gap: 12px;
  min-height: 250px;
  padding: 16px 14px;
  border: 1px solid var(--borda);
  border-radius: var(--raio-cartao);
  background: var(--superficie);
}

.dia--hoje {
  background: var(--escuro);
  border-color: var(--escuro);
}

.dia__cabecalho {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.dia__semana {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--tinta-apagada);
}

.dia--hoje .dia__semana {
  color: var(--ambar);
}

.dia__data {
  font-size: 21px;
  font-weight: 800;
  letter-spacing: -0.02em;
  color: var(--tinta);
}

.dia--hoje .dia__data {
  color: var(--claro);
}

.compromisso {
  display: flex;
  flex-direction: column;
  gap: 4px;
  padding: 11px 12px;
  border-radius: var(--raio-botao);
  border-left: 3px solid transparent;
  background: var(--claro);
}

.compromisso__titulo {
  font-size: 12.5px;
  font-weight: 700;
  line-height: 1.3;
  color: var(--tinta);
}

.compromisso__motorista {
  font-size: 11px;
  font-weight: 500;
  color: var(--tinta-tenue);
}

.dia__vazio {
  font-size: 12px;
  font-weight: 500;
  color: var(--tinta-vazia);
}

.dia--hoje .dia__vazio {
  color: #6b7484;
}
</style>
