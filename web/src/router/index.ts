import { createRouter, createWebHistory } from "vue-router";
import Home from "../pages/Home.vue";
import Login from "../pages/Login.vue";
import Register from "../pages/Register.vue";
import About from "../pages/About.vue";
import { useAuthStore } from "../stores/auth";

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: "/", name: "home", component: Home },
    { path: "/about", name: "about", component: About },
    { path: "/login", name: "login", component: Login },
    { path: "/register", name: "register", component: Register },
  ],
});

// (optional) ja vēlāk taisi protected routes
router.beforeEach((to) => {
  const auth = useAuthStore();
  const protectedNames = ["home"]; // piem., vēlāk "parskats", "profil"
  if (protectedNames.includes(to.name as string) && !auth.token) {
    return { name: "login", query: { redirect: to.fullPath } };
  }
});

export default router;
