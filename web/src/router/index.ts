// web/src/router/index.ts
import { createRouter, createWebHistory } from 'vue-router'
import { useAuth } from '@/stores/auth'

const Home = () => import('@/pages/Home.vue')
const About = () => import('@/pages/About.vue')
const Login = () => import('@/pages/Login.vue')
const Register = () => import('@/pages/Register.vue')

// web/src/router/index.ts (fragment)
const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', name: 'home', component: () => import('@/pages/Home.vue') }, // publiska
    { path: '/about', name: 'about', component: () => import('@/pages/About.vue'), meta: { requiresAuth: true } },
    { path: '/login', name: 'login', component: () => import('@/pages/Login.vue'), meta: { guestOnly: true } },
    { path: '/register', name: 'register', component: () => import('@/pages/Register.vue'), meta: { guestOnly: true } },
  ],
})

router.beforeEach(async (to) => {
  const auth = useAuth()
  if (auth.token && !auth.user) { try { await auth.fetchUser() } catch {} }
  if (to.meta.requiresAuth && !auth.token) return { name: 'login' }
  if (to.meta.guestOnly && auth.token)  return { name: 'home' }
  return true
})

export default router
