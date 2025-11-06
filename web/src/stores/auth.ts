// web/src/stores/auth.ts
import { defineStore } from "pinia";
import { api } from "@/lib/http";

export const useAuth = defineStore("auth", {
  state: () => ({ user: null as any }),
  actions: {
    async getUser() {
      const { data } = await api.get("/api/user");
      this.user = data;
      return data;
    },
    // Cookie (Sanctum + Breeze API)
    async loginCookie(payload: { email: string; password: string }) {
      await api.get("/sanctum/csrf-cookie");
      await api.post("/login", payload);
      return this.getUser();
    },
    async registerCookie(payload: { name:string; email:string; password:string; password_confirmation:string }) {
      await api.get("/sanctum/csrf-cookie");
      await api.post("/register", payload);
      return this.getUser();
    },
    async logoutCookie() {
      await api.post("/logout");
      this.user = null;
    },
    // Token (Bearer) — alternatīva
    async loginToken(payload: { email: string; password: string }) {
      const { data } = await api.post("/api/auth/token-login", payload);
      localStorage.setItem("bdus_token", data.token);
      this.user = data.user;
      return data.user;
    },
    async logoutToken() {
      await api.post("/api/auth/token-logout");
      localStorage.removeItem("bdus_token");
      this.user = null;
    },
  },
});
