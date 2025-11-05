import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/Home.vue'
const About = () => import('../pages/About.vue')

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', component: Home },
    { path: '/about', component: About },
  ],
})

export default router
