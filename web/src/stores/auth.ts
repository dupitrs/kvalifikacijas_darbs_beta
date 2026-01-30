import { defineStore } from "pinia";
import { api } from "../lib/api";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: null as any,
    token: localStorage.getItem("token") || "",
    loading: false,
    error: "",
  }),

  actions: {
    async register(payload: { vards: string; uzvards?: string; epasts: string; parole: string }) {
      this.loading = true; this.error = "";
      try {
        const res = await api.register(payload);
        this.token = res.data.token;
        localStorage.setItem("token", this.token);
        this.user = res.data.user;
      } catch (e: any) {
        this.error = e?.response?.data?.message || "Register error";
        throw e;
      } finally {
        this.loading = false;
      }
    },

    async login(payload: { epasts: string; parole: string }) {
      this.loading = true; this.error = "";
      try {
        const res = await api.login(payload);
        this.token = res.data.token;
        localStorage.setItem("token", this.token);
        this.user = res.data.user;
      } catch (e: any) {
        this.error = e?.response?.data?.message || "Login error";
        throw e;
      } finally {
        this.loading = false;
      }
    },

    async fetchMe() {
      if (!this.token) return;
      const res = await api.me();
      this.user = res.data;
    },

    async logout() {
      try { await api.logout(); } catch {}
      this.token = "";
      this.user = null;
      localStorage.removeItem("token");
    },
  },
});
