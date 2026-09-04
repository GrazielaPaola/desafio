import { ref } from 'vue'

const aberto = ref(false)

export function useMenuLateral() {
  function alternar() {
    aberto.value = !aberto.value
  }

  function fechar() {
    aberto.value = false
  }

  return { aberto, alternar, fechar }
}
