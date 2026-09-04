<template>
  <q-layout view="lHh LpR lFr" class="menu-lateral">
    <q-drawer v-model="aberto" show-if-above :width="LARGURA_MENU" :breakpoint="BREAKPOINT_MENU">
      <MenuLateral />
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { onMounted } from 'vue'
import MenuLateral from '@/components/MenuLateral.vue'
import { BREAKPOINT_MENU, LARGURA_MENU, useMenuLateral } from '@/composables/useMenuLateral'
import { useErrosApi } from '@/composables/useErrosApi'
import { useResumoStore } from '@/stores/resumo'

const { aberto } = useMenuLateral()
const resumoStore = useResumoStore()
const { tratar } = useErrosApi()

onMounted(() => resumoStore.carregar().catch(tratar))
</script>
