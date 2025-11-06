import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/Home.vue'
const About = () => import('../pages/About.vue')

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: "/", name: "home", component: () => import("@/pages/Home.vue") },
    { path: "/about", name: "about", component: () => import("@/pages/About.vue") },
    { path: "/login", name: "login", component: () => import("@/pages/Login.vue") },
    { path: "/register", name: "register", component: () => import("@/pages/Register.vue") },
  ],
})

export default router
