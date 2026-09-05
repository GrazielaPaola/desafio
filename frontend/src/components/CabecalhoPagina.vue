<template>
  <header class="cabecalho">
    <q-btn v-if="!aberto" flat class="botao-icone" aria-label="Abrir menu" @click="alternar">
      <IconeSvg nome="menu" />
    </q-btn>

    <div class="cabecalho__texto">
      <h1 class="titulo-tela">{{ titulo }}</h1>
      <p class="subtitulo-tela">{{ subtitulo }}</p>
    </div>

    <div class="cabecalho__lateral">
      <q-btn flat class="botao-icone" aria-label="Notificações">
        <IconeSvg nome="sino" />
      </q-btn>

      <div class="cartao-usuario">
        <span class="cartao-usuario__inicial">{{ iniciaisDoNome(USUARIO.nome) }}</span>
        <span class="cartao-usuario__dados">
          <span class="cartao-usuario__nome">{{ USUARIO.nome }}</span>
          <span class="cartao-usuario__papel">{{ USUARIO.papel }}</span>
        </span>
      </div>
    </div>
  </header>
</template>

<script setup>
import IconeSvg from '@/components/IconeSvg.vue'
import { useMenuLateral } from '@/composables/useMenuLateral'
import { iniciaisDoNome } from '@/utils/cores'

defineProps({
  titulo: { type: String, required: true },
  subtitulo: { type: String, default: '' },
})

const USUARIO = { nome: 'Graziela Paola', papel: 'Operações' }

const { aberto, alternar } = useMenuLateral()
</script>

<style scoped>
.cabecalho {
  display: flex;
  align-items: center;
  gap: 20px;
}

.cabecalho__texto {
  display: flex;
  flex-direction: column;
  gap: 4px;
  min-width: 0;
}

.cabecalho__lateral {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-left: auto;
}

.cartao-usuario {
  display: flex;
  align-items: center;
  gap: 11px;
  padding: 6px 14px 6px 6px;
  border: 1px solid var(--borda-forte);
  border-radius: 16px;
  background: var(--superficie);
}

.cartao-usuario__inicial {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 30px;
  height: 30px;
  border-radius: var(--raio-acao);
  background: var(--escuro);
  color: var(--ambar);
  font-size: 12px;
  font-weight: 700;
}

.cartao-usuario__dados {
  display: flex;
  flex-direction: column;
  line-height: 1.25;
}

.cartao-usuario__nome {
  font-size: 12.5px;
  font-weight: 700;
}

.cartao-usuario__papel {
  font-size: 11px;
  font-weight: 500;
  color: var(--tinta-tenue);
}

@media (max-width: 599px) {
  .cartao-usuario__dados {
    display: none;
  }
}
</style>
