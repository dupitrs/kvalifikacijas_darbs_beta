<script setup lang="ts">
import { ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useAuth } from '@/stores/auth'

const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const show = ref(false)

const auth = useAuth()
const router = useRouter()

const submit = async () => {
  try {
    await auth.register({ name: name.value, email: email.value, password: password.value, password_confirmation: password_confirmation.value })
    router.push({ name: 'home' })
  } catch {/* auth.error already set */}
}
</script>

<template>
  <div class="min-h-screen grid place-items-center px-4"
       style="background: radial-gradient(60% 80% at 50% 0%, #ffffff 0%, #faf7f0 60%, #efe8db 100%);">
    <div class="flex flex-col items-center gap-6 w-full">
      <!-- Top brand -->
      <div class="text-center">
        <div class="mx-auto mb-3 h-12 w-12 rounded-full bg-[color:var(--mpp-primary,#4F7942)]/15 grid place-items-center">
          <span class="text-[color:var(--mpp-primary,#4F7942)] font-bold text-xl">MPP</span>
        </div>
        <h1 class="text-2xl font-semibold text-[color:var(--mpp-dark,#2B2A28)]">Mums pieder pasaule</h1>
        <p class="text-neutral-600">Brīvprātīgā darba uzskaite</p>
      </div>

      <!-- Card -->
      <div class="w-full max-w-md bg-white shadow rounded-2xl p-6">
        <h2 class="text-xl font-semibold mb-4">Reģistrēties</h2>

        <form class="space-y-4" @submit.prevent="submit">
          <div>
            <label class="block mb-1 text-sm">Vārds, uzvārds</label>
            <input v-model="name" type="text" required
                   class="w-full rounded-2xl border px-4 py-2 outline-none focus:ring focus:ring-[color:var(--mpp-primary,#4F7942)]/20"
                   placeholder="Vārds Uzvārds" />
          </div>

          <div>
            <label class="block mb-1 text-sm">E-pasts</label>
            <input v-model="email" type="email" required
                   class="w-full rounded-2xl border px-4 py-2 outline-none focus:ring focus:ring-[color:var(--mpp-primary,#4F7942)]/20"
                   placeholder="piemers@epasts.lv" />
          </div>

          <div>
            <label class="block mb-1 text-sm">Parole</label>
            <div class="relative">
              <input :type="show ? 'text' : 'password'" v-model="password" required minlength="6"
                     class="w-full rounded-2xl border px-4 py-2 pr-20 outline-none focus:ring focus:ring-[color:var(--mpp-primary,#4F7942)]/20" />
              <button type="button"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-[color:var(--mpp-secondary,#C5A572)]"
                      @click="show = !show">
                {{ show ? 'Slēpt' : 'Rādīt' }}
              </button>
            </div>
          </div>

          <div>
            <label class="block mb-1 text-sm">Parole atkārtoti</label>
            <input :type="show ? 'text' : 'password'" v-model="password_confirmation" required minlength="6"
                   class="w-full rounded-2xl border px-4 py-2 outline-none focus:ring focus:ring-[color:var(--mpp-primary,#4F7942)]/20" />
          </div>

          <button class="w-full inline-flex items-center justify-center px-4 py-2 rounded-2xl font-semibold
                         bg-[color:var(--mpp-primary,#4F7942)] text-white hover:opacity-90"
                  :disabled="auth.loading">
            {{ auth.loading ? 'Reģistrē…' : 'Reģistrēties' }}
          </button>

          <p v-if="auth.error" class="text-red-600 text-sm">{{ auth.error }}</p>
        </form>

        <div class="mt-4 text-sm">
          Jau esi reģistrējies?
          <RouterLink class="font-semibold text-[color:var(--mpp-secondary,#C5A572)] underline"
                      :to="{ name: 'login' }">Ieiet</RouterLink>
        </div>
      </div>

      <p class="text-xs text-neutral-500">© {{ new Date().getFullYear() }} MPP</p>
    </div>
  </div>
</template>
