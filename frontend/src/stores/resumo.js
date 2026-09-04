import { defineStore } from 'pinia'
import { ref } from 'vue'
import { resumoService } from '@/services/resumoService'

export const useResumoStore = defineStore('resumo', () => {
  const resumo = ref(null)
  const carregando = ref(false)

  async function carregar() {
    carregando.value = true

    try {
      resumo.value = await resumoService.obter()
    } finally {
      carregando.value = false
    }
  }

  return { resumo, carregando, carregar }
})
