<script setup lang="ts">
import { computed, onMounted } from "vue";
import { useAuthStore } from "../stores/auth";

const auth = useAuthStore();
const isLogged = computed(() => !!auth.token);

onMounted(async () => {
  // ja ir tokens, mēģinam paņemt user no /api/user
  if (auth.token && !auth.user) {
    try {
      await auth.fetchMe();
    } catch (e) {
      // ja tokens nederīgs, store pats var iztīrīt (vai tu to pieliksi store)
    }
  }
});
</script>

<template>
  <div class="p-6 max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold">BDUS</h1>
    <p class="mt-1 opacity-70">Home (tests: /api/user)</p>

    <div class="mt-6 rounded-2xl border p-4">
      <div class="font-medium">Statuss</div>
      <div class="mt-2 text-sm">
        <div>Logged in: <b>{{ isLogged ? "Jā" : "Nē" }}</b></div>
      </div>

      <div class="mt-4" v-if="isLogged">
        <div class="font-medium">Lietotājs no API</div>
        <pre class="mt-2 text-xs bg-gray-50 border rounded-xl p-3 overflow-auto">{{ auth.user }}</pre>
      </div>

      <div class="mt-4" v-else>
        <div class="text-sm">
          Ielogojies, lai redzētu <code>/api/user</code>.
        </div>
      </div>
    </div>
<<<<<<< HEAD
  </div>
=======

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

  <!-- Sākumsekcija -->
  <main class="px-4 py-10">
    <section class="max-w-5xl mx-auto grid md:grid-cols-2 gap-6 items-stretch">
      <div class="rounded-2xl bg-white p-6 shadow">
        <h1 class="text-2xl font-semibold text-neutral-800 mb-2">Brīvprātīgā darba uzskaites sistēma</h1>
        <p class="text-neutral-600">
          test
        </p>
      </div>

      <PublicSummaryCards />
    </section>
  </main>
>>>>>>> 5dddf220957272e841f9eeea7b33eefd02063c7d
</template>
