<template>
  <q-page class="nao-encontrada">
    <span class="nao-encontrada__codigo numero-tabular">404</span>
    <h1 class="nao-encontrada__titulo">Página não encontrada</h1>
    <p class="nao-encontrada__texto">O endereço acessado não existe ou foi movido.</p>
    <q-btn
      unelevated
      class="botao-destaque"
      label="Voltar para a visão geral"
      no-caps
      :to="{ name: 'inicio' }"
    />
  </q-page>
</template>

<script setup></script>

<style scoped>
.nao-encontrada {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 12px;
  min-height: 100vh;
  padding: 40px 20px;
  text-align: center;
}

.nao-encontrada__codigo {
  font-size: 96px;
  font-weight: 800;
  letter-spacing: -0.04em;
  line-height: 1;
  color: var(--ambar);
}

.nao-encontrada__titulo {
  margin: 8px 0 0;
  font-size: 22px;
  font-weight: 700;
}

.nao-encontrada__texto {
  margin: 0 0 12px;
  font-size: 13.5px;
  font-weight: 500;
  color: var(--tinta-fraca);
}
</style>
