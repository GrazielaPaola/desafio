import axios from 'axios'

const API_URL_PADRAO = 'http://localhost:8000/api'

export const api = axios.create({
  baseURL: import.meta.env.QCLI_API_URL || API_URL_PADRAO,
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
})
