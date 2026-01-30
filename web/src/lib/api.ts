import { http } from "./http";

export const api = {
  register(data: { vards: string; uzvards?: string; epasts: string; parole: string }) {
    return http.post("/register", data);
  },
  login(data: { epasts: string; parole: string }) {
    return http.post("/login", data);
  },
  me() {
    return http.get("/user");
  },
  logout() {
    return http.post("/logout");
  },
};
