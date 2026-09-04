import { ref } from 'vue'

export const LARGURA_MENU = 244
export const BREAKPOINT_MENU = 1023

function menuCabeAoLado() {
  return window.innerWidth > BREAKPOINT_MENU
}

const aberto = ref(menuCabeAoLado())

export function useMenuLateral() {
  function alternar() {
    aberto.value = !aberto.value
  }

  function fecharQuandoSobrepoe() {
    if (!menuCabeAoLado()) aberto.value = false
  }

  return { aberto, alternar, fecharQuandoSobrepoe }
}
