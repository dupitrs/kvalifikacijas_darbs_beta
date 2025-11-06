// web/src/lib/http.ts
import axios from "axios";

export const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL ?? "http://localhost:8000",
  withCredentials: true, // vajadzīgs cookie (Sanctum) plūsmai
});

// Token (Bearer) plūsmai – ja izmantosi /auth/token-login
api.interceptors.request.use((config) => {
  const token = localStorage.getItem("bdus_token");
  if (token) config.headers.Authorization = `Bearer ${token}`;
  return config;
});
