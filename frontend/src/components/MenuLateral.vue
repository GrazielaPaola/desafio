<template>
  <div class="menu">
    <div class="menu__marca">
      <span class="menu__logo">O</span>
      <span class="menu__identidade">
        <span class="menu__produto">OlfLog</span>
        <span class="menu__segmento">Coletas</span>
      </span>
    </div>

    <nav class="menu__navegacao">
      <span class="menu__titulo">Navegação</span>
      <router-link
        v-for="item in ITENS"
        :key="item.rota"
        :to="{ name: item.rota }"
        class="item-menu"
        exact-active-class="item-menu--ativo"
        @click="fechar"
      >
        <span class="menu__icone"><IconeSvg :nome="item.icone" :tamanho="18" /></span>
        <span>{{ item.titulo }}</span>
      </router-link>
    </nav>

    <div class="menu__resumo">
      <span class="menu__resumo-rotulo">Semana atual</span>
      <span class="menu__resumo-valor numero-tabular">{{ coletasDaSemana }}</span>
      <span class="menu__resumo-nota">coletas programadas</span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import IconeSvg from '@/components/IconeSvg.vue'
import { useMenuLateral } from '@/composables/useMenuLateral'
import { useResumoStore } from '@/stores/resumo'

const ITENS = [
  { titulo: 'Visão geral', icone: 'painel', rota: 'inicio' },
  { titulo: 'Coletas', icone: 'caminhao', rota: 'coletas' },
  { titulo: 'Agenda', icone: 'calendario', rota: 'agenda' },
  { titulo: 'Motoristas', icone: 'cracha', rota: 'motoristas' },
]

const { fechar } = useMenuLateral()
const { resumo } = storeToRefs(useResumoStore())

const coletasDaSemana = computed(() => resumo.value?.coletas_semana ?? 0)
</script>

<style scoped>
.menu {
  display: flex;
  flex-direction: column;
  gap: 34px;
  height: 100%;
  padding: 22px 18px;
}

.menu__marca {
  display: flex;
  align-items: center;
  gap: 11px;
  padding: 6px 10px;
}

.menu__logo {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 34px;
  height: 34px;
  border-radius: 11px;
  background: var(--ambar);
  color: var(--escuro);
  font-size: 15px;
  font-weight: 800;
}

.menu__identidade {
  display: flex;
  flex-direction: column;
  gap: 1px;
}

.menu__produto {
  color: var(--claro);
  font-size: 15px;
  font-weight: 700;
  letter-spacing: -0.01em;
}

.menu__segmento {
  color: var(--claro-fraco);
  font-size: 11px;
  font-weight: 500;
  letter-spacing: 0.06em;
  text-transform: uppercase;
}

.menu__navegacao {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.menu__titulo {
  padding: 0 12px 6px;
  color: var(--claro-tenue);
  font-size: 10.5px;
  font-weight: 700;
  letter-spacing: 0.12em;
  text-transform: uppercase;
}

.menu__icone {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 18px;
  height: 18px;
}

.menu__resumo {
  display: flex;
  flex-direction: column;
  margin-top: auto;
  padding: 16px;
  border-radius: 18px;
  background: var(--escuro-suave);
}

.menu__resumo-rotulo {
  margin-bottom: 8px;
  color: var(--claro-rotulo);
  font-size: 11.5px;
  font-weight: 600;
  letter-spacing: 0.04em;
  text-transform: uppercase;
}

.menu__resumo-valor {
  color: var(--claro);
  font-size: 26px;
  font-weight: 800;
  line-height: 1;
}

.menu__resumo-nota {
  margin-top: 4px;
  color: var(--claro-fraco);
  font-size: 12.5px;
  font-weight: 500;
}
</style>
