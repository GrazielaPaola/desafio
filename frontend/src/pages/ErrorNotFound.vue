<template>
  <div class="fullscreen bg-primary text-white text-center q-pa-md flex flex-center">
    <div>
      <div class="text-h1">404</div>
      <div class="text-h5 q-mt-md">Página não encontrada</div>
      <q-btn
        class="q-mt-xl"
        color="white"
        text-color="primary"
        unelevated
        :to="{ name: 'coletas' }"
        label="Voltar ao início"
        no-caps
      />
    </div>
  </div>
</template>

<script setup></script>
