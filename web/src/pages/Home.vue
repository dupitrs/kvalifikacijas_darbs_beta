<!-- web/src/pages/Home.vue -->
<script setup lang="ts">
import { useAuth } from '@/stores/auth'
import { defineAsyncComponent } from 'vue'

const auth = useAuth()
const PublicSummaryCards = defineAsyncComponent(
  () => import('@/pages/ficas/PublicSummaryCards.vue')
)
</script>



<template>
  <!-- Top bar -->
  <header class="w-full flex items-center justify-between px-4 py-3 border-b bg-white/90 sticky top-0 z-10">
    <div class="flex items-center gap-2">
      <div class="h-8 w-8 rounded-full bg-emerald-700/15 grid place-items-center">
        <span class="text-emerald-700 font-bold text-sm">MPP</span>
      </div>
      <span class="font-semibold text-neutral-800">Mums pieder pasaule · BDUS2</span>
    </div>

    <nav class="flex items-center gap-3">
      <template v-if="!auth.user">
        <router-link to="/login" class="px-3 py-1.5 rounded-xl border hover:bg-neutral-50">Login</router-link>
        <router-link to="/register" class="px-3 py-1.5 rounded-xl bg-emerald-700 text-white hover:opacity-90">Register</router-link>
      </template>
      <template v-else>
        <span class="text-sm text-neutral-600">Sveiks, <strong>{{ auth.user.name }}</strong></span>
        <button class="px-3 py-1.5 rounded-xl border hover:bg-neutral-50" @click="auth.logout">Izrakstīties</button>
      </template>
    </nav>
  </header>

  <!-- Hero / īsā informācija -->
  <main class="px-4 py-10">
    <section class="max-w-5xl mx-auto grid md:grid-cols-2 gap-6 items-stretch">
      <div class="rounded-2xl bg-white p-6 shadow">
        <h1 class="text-2xl font-semibold text-neutral-800 mb-2">Brīvprātīgā darba uzskaites sistēma</h1>
        <p class="text-neutral-600">
          Šeit varēsi reģistrēties, pieteikties uz sludinājumiem, ievadīt nostrādātās stundas
          un saņemt apliecinājumus. Ja vēl neesi ielogojies, izmanto augšā <em>Login</em> vai <em>Register</em>.
        </p>
        <ul class="mt-4 space-y-2 text-neutral-700">
          <li>• Pārskati stundas un aktivitātes</li>
          <li>• Skaties pieejamos sludinājumus</li>
          <li>• Ģenerē apliecinājumu PDF</li>
        </ul>
      </div>

      <!-- Publiska statistika (no API /api/public/summary) -->
      <PublicSummaryCards />
    </section>
  </main>
</template>

<script lang="ts">
export default {
  components: {
    PublicSummaryCards: (await import('@/pages/ficas/PublicSummaryCards.vue')).default
  }
}
</script>
