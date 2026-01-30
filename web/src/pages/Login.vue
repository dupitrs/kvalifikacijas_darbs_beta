<script setup lang="ts">
import { reactive } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useAuthStore } from "../stores/auth";

const router = useRouter();
const route = useRoute();
const auth = useAuthStore();

const form = reactive({ epasts: "", parole: "" });

async function submit() {
  auth.error = "";
  await auth.login({
    epasts: form.epasts.trim().toLowerCase(),
    parole: form.parole,
  });

  const redirect = (route.query.redirect as string) || "/";
  router.push(redirect);
}
</script>

<template>
  <div class="mx-auto max-w-md">
    <section class="card p-6 lg:p-8">
      <div class="inline-flex items-center gap-2 rounded-full bg-mpp-mint/40 px-3 py-1 text-sm font-semibold text-mpp-teal">
        Welcome back
      </div>

      <h1 class="mt-3 text-2xl font-extrabold text-mpp-ink">Ielogošanās</h1>
      <p class="text-sm text-mpp-ink/60 mt-1">Ievadi e-pastu un paroli.</p>

      <form class="mt-6 space-y-4" @submit.prevent="submit">
        <div>
          <div class="label">E-pasts</div>
          <input v-model="form.epasts" type="email" class="input" required />
        </div>

        <div>
          <div class="label">Parole</div>
          <input v-model="form.parole" type="password" class="input" required />
        </div>

        <div v-if="auth.error" class="rounded-xl border border-mpp-red/20 bg-mpp-red/5 p-3 text-sm text-mpp-ink">
          {{ auth.error }}
        </div>

        <button class="btn-primary w-full" type="submit" :disabled="auth.loading">
          {{ auth.loading ? "Ielogojas..." : "Ielogoties" }}
        </button>

        <div class="text-sm text-mpp-ink/70">
          Nav konta?
          <router-link class="font-semibold text-mpp-teal hover:underline" :to="{ name: 'register' }">Reģistrēties</router-link>
        </div>
      </form>
    </section>
  </div>
</template>
