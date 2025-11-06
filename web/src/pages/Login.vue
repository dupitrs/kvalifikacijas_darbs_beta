<script setup lang="ts">
import { ref } from "vue";
import { useAuth } from "@/stores/auth";
import { useRouter } from "vue-router";

const email = ref(""); const password = ref("");
const mode = ref<"cookie"|"token">("cookie");
const auth = useAuth(); const router = useRouter();

async function submit() {
  if (mode.value === "cookie") await auth.loginCookie({ email: email.value, password: password.value });
  else await auth.loginToken({ email: email.value, password: password.value });
  router.push("/");
}
</script>

<template>
  <div class="min-h-screen grid place-items-center p-6 bg-gray-50">
    <form @submit.prevent="submit" class="bg-white p-6 rounded-2xl shadow w-full max-w-sm space-y-4">
      <h1 class="text-2xl font-bold">Login</h1>
      <label class="block">
        <span class="text-sm">Email</span>
        <input v-model="email" type="email" class="mt-1 w-full border rounded px-3 py-2" required />
      </label>
      <label class="block">
        <span class="text-sm">Password</span>
        <input v-model="password" type="password" class="mt-1 w-full border rounded px-3 py-2" required />
      </label>
      <div class="flex items-center gap-2 text-sm">
        <span>Mode:</span>
        <select v-model="mode" class="border rounded px-2 py-1">
          <option value="cookie">Sanctum (cookie)</option>
          <option value="token">Token (Bearer)</option>
        </select>
      </div>
      <button class="w-full rounded-xl py-2 border">Sign in</button>
    </form>
  </div>
</template>
