<script setup lang="ts">
import { reactive } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";

const router = useRouter();
const auth = useAuthStore();

const form = reactive({
  vards: "",
  uzvards: "",
  epasts: "",
  personas_kods: "",
  adrese: "",
  parole: "",
  parole_confirmation: "",
});

async function submit() {
  auth.error = "";

  await auth.register({
    vards: form.vards.trim(),
    uzvards: form.uzvards.trim(),
    epasts: form.epasts.trim().toLowerCase(),
    personas_kods: form.personas_kods.trim(),
    adrese: form.adrese.trim(),
    parole: form.parole,
    parole_confirmation: form.parole_confirmation,
  });

  router.push({ name: "home" });
}
</script>

<template>
  <div class="grid lg:grid-cols-2 gap-8 items-center">
    <!-- Left promo -->
    <section class="card p-8 lg:p-10">
      <div class="inline-flex items-center gap-2 rounded-full bg-mpp-teal/10 px-3 py-1 text-sm font-semibold text-mpp-teal">
        BDUS • brīvprātīgā darba uzskaite
      </div>

      <h1 class="mt-4 text-3xl lg:text-4xl font-extrabold tracking-tight text-mpp-ink">
        Reģistrējies un sāc krāt punktus
      </h1>

      <p class="mt-3 text-mpp-ink/70 leading-relaxed">
        Profils ir vajadzīgs, lai vēlāk var ģenerēt apliecinājumus par brīvprātīgo darbu.
        Telefons nav obligāts — to varēsi pievienot profilā.
      </p>

      <div class="mt-6 flex gap-3">
        <div class="h-2 w-16 rounded-full bg-mpp-teal"></div>
        <div class="h-2 w-10 rounded-full bg-mpp-orange"></div>
        <div class="h-2 w-8 rounded-full bg-mpp-red"></div>
      </div>
    </section>

    <!-- Form -->
    <section class="card p-6 lg:p-8">
      <h2 class="text-xl font-extrabold text-mpp-ink">Reģistrācija</h2>
      <p class="text-sm text-mpp-ink/60 mt-1">Aizpildi obligātos laukus.</p>

      <form class="mt-6 space-y-4" @submit.prevent="submit">
        <div class="grid sm:grid-cols-2 gap-4">
          <div>
            <div class="label">Vārds</div>
            <input v-model="form.vards" class="input" required />
          </div>
          <div>
            <div class="label">Uzvārds</div>
            <input v-model="form.uzvards" class="input" required />
          </div>
        </div>

        <div>
          <div class="label">E-pasts</div>
          <input v-model="form.epasts" type="email" class="input" required />
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
          <div>
            <div class="label">Personas kods</div>
            <input v-model="form.personas_kods" class="input" required />
          </div>
          <div>
            <div class="label">Adrese</div>
            <input v-model="form.adrese" class="input" required />
          </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
          <div>
            <div class="label">Parole</div>
            <input v-model="form.parole" type="password" class="input" required />
          </div>
          <div>
            <div class="label">Parole atkārtoti</div>
            <input v-model="form.parole_confirmation" type="password" class="input" required />
          </div>
        </div>

        <div v-if="auth.error" class="rounded-xl border border-mpp-red/20 bg-mpp-red/5 p-3 text-sm text-mpp-ink">
          {{ auth.error }}
        </div>

        <button class="btn-primary w-full" type="submit" :disabled="auth.loading">
          {{ auth.loading ? "Reģistrē..." : "Reģistrēties" }}
        </button>

        <div class="text-sm text-mpp-ink/70">
          Ir jau konts?
          <router-link class="font-semibold text-mpp-teal hover:underline" :to="{ name: 'login' }">Ielogoties</router-link>
        </div>
      </form>
    </section>
  </div>
</template>
