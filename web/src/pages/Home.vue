<script setup lang="ts">
import { computed } from "vue";
import { useAuthStore } from "../stores/auth";

const auth = useAuthStore();
const isLogged = computed(() => !!auth.token);
</script>

<template>
  <div class="max-w-3xl mx-auto">
    <div class="card p-8">
      <div class="inline-flex items-center gap-2 rounded-full bg-mpp-teal/10 px-3 py-1 text-sm font-semibold text-mpp-teal">
        BDUS • brīvprātīgā darba uzskaite
      </div>

      <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-mpp-ink">
        Sākums
      </h1>

      <p class="mt-2 text-mpp-ink/70">
        Šeit vēlāk būs sludinājumi, darbi, pārskats, misijas un balvas.
      </p>

      <div class="mt-6 rounded-2xl border border-black/10 bg-white p-4">
        <template v-if="isLogged">
          <div class="text-sm text-mpp-ink/70">Tu esi ielogojies kā:</div>
          <div class="mt-1 font-semibold text-mpp-ink">{{ auth.user?.epasts }}</div>
        </template>

        <template v-else>
          <div class="text-sm text-mpp-ink/70">
            Tu neesi ielogojies. Spied augšā “Ienākt” vai “Reģistrēties”.
          </div>
        </template>
      </div>
    </div>
  </div>
</template>
