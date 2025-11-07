// web/src/lib/api.ts
import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  withCredentials: false, // jo lietosim Bearer tokenu, nevis cookies
})

api.interceptors.request.use((config) => {
  const token = localStorage.getItem('bdus_token')
  if (token) config.headers.Authorization = `Bearer ${token}`
  return config
})

export default api
