export const PALETA_MOTORISTAS = ['#e59e00', '#12796d', '#6b4ea8', '#2f6aa8', '#c2683a']

export function corDoMotorista(motoristaId) {
  const posicao = Number(motoristaId) - 1

  if (!Number.isFinite(posicao) || posicao < 0) {
    return PALETA_MOTORISTAS[0]
  }

  return PALETA_MOTORISTAS[posicao % PALETA_MOTORISTAS.length]
}

export function iniciaisDoNome(nome) {
  return String(nome ?? '')
    .split(/\s+/)
    .filter(Boolean)
    .slice(0, 2)
    .map((parte) => parte[0])
    .join('')
    .toUpperCase()
}
