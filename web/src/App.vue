<script setup lang="ts">
import { computed } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "./stores/auth";

const auth = useAuthStore();
const router = useRouter();

const isLogged = computed(() => !!auth.token);

async function doLogout() {
  await auth.logout();
  router.push({ name: "login" });
}
</script>

<template>
  <div class="min-h-screen">
    <!-- Top bar -->
    <header class="sticky top-0 z-10 border-b border-black/5 bg-white/70 backdrop-blur">
      <div class="mx-auto max-w-6xl px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="h-9 w-9 rounded-2xl bg-mpp-teal/15 flex items-center justify-center">
            <div class="h-4 w-4 rounded-full bg-mpp-teal"></div>
          </div>

          <div>
            <div class="font-extrabold tracking-tight text-mpp-ink">BDUS</div>
            <div class="text-xs text-mpp-ink/60 -mt-0.5">Mums pieder pasaule</div>
          </div>

          <nav class="ml-6 hidden md:flex items-center gap-4 text-sm">
            <router-link class="text-mpp-ink/70 hover:text-mpp-ink" :to="{ name: 'home' }">Sākums</router-link>
            <router-link class="text-mpp-ink/70 hover:text-mpp-ink" :to="{ name: 'about' }">Par</router-link>
          </nav>
        </div>

        <div class="flex items-center gap-2">
          <template v-if="!isLogged">
            <router-link class="btn-ghost" :to="{ name: 'login' }">Login</router-link>
            <router-link class="btn-primary" :to="{ name: 'register' }">Register</router-link>
          </template>

          <template v-else>
            <div class="hidden sm:block text-sm text-mpp-ink/70">
              {{ auth.user?.epasts || "Ielogojies" }}
            </div>
            <button class="btn-ghost" @click="doLogout">Logout</button>
          </template>
        </div>
      </div>
    </header>

    <!-- Background accent -->
    <div class="pointer-events-none fixed inset-0 -z-10">
      <div class="absolute -top-24 -left-24 h-80 w-80 rounded-full bg-mpp-mint/50 blur-3xl"></div>
      <div class="absolute top-20 -right-24 h-80 w-80 rounded-full bg-mpp-green/35 blur-3xl"></div>
      <div class="absolute bottom-0 left-1/3 h-80 w-80 rounded-full bg-mpp-teal/20 blur-3xl"></div>
    </div>

    <main class="mx-auto max-w-6xl px-4 py-10">
      <router-view />
    </main>
  </div>
</template>
