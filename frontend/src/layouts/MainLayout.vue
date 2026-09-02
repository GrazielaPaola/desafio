<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn flat dense round icon="menu" aria-label="Menu" @click="alternarMenu" />
        <q-toolbar-title>Agendamento de Coletas</q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-drawer v-model="menuAberto" show-if-above bordered>
      <q-list>
        <q-item-label header>Navegação</q-item-label>
        <q-item
          v-for="item in itensMenu"
          :key="item.rota"
          :to="{ name: item.rota }"
          exact
          clickable
          v-ripple
        >
          <q-item-section avatar>
            <q-icon :name="item.icone" />
          </q-item-section>
          <q-item-section>{{ item.titulo }}</q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref } from 'vue'

const itensMenu = [
  { titulo: 'Coletas', icone: 'local_shipping', rota: 'coletas' },
  { titulo: 'Motoristas', icone: 'badge', rota: 'motoristas' },
]

const menuAberto = ref(false)

function alternarMenu() {
  menuAberto.value = !menuAberto.value
}
</script>
