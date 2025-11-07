// web/src/stores/auth.ts
import { defineStore } from 'pinia'
import api from '@/lib/api'

type User = { id:number; name:string; email:string; role?:string }

export const useAuth = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('bdus_token') as string | null,
    user: null as User | null,
    loading: false,
    error: '' as string,
  }),
  actions: {
    async fetchUser() {
      if (!this.token) { this.user = null; return }
      const { data } = await api.get('/api/user')
      this.user = data
    },
    async login(email: string, password: string) {
      this.loading = true; this.error = ''
      try {
        const { data } = await api.post('/login', { email, password })
        this.token = data.token
        localStorage.setItem('bdus_token', data.token)
        await this.fetchUser()
      } catch (e:any) {
        this.error = e?.response?.data?.message ?? 'Neizdevās pieteikties'
        this.token = null; localStorage.removeItem('bdus_token')
        this.user = null
        throw e
      } finally { this.loading = false }
    },
    async register(payload: { name:string; email:string; password:string; password_confirmation:string }) {
      this.loading = true; this.error = ''
      try {
        const { data } = await api.post('/register', payload)
        this.token = data.token
        localStorage.setItem('bdus_token', data.token)
        await this.fetchUser()
      } catch (e:any) {
        this.error = e?.response?.data?.message ?? 'Neizdevās reģistrēties'
        this.token = null; localStorage.removeItem('bdus_token')
        this.user = null
        throw e
      } finally { this.loading = false }
    },
    async logout() {
      try { await api.post('/logout') } catch {}
      this.user = null
      this.token = null
      localStorage.removeItem('bdus_token')
    },
  },
})
