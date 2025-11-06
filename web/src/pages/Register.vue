<script setup lang="ts">
import { ref } from "vue";
import { useAuth } from "@/stores/auth";
import { useRouter } from "vue-router";

const name = ref(""); const email = ref(""); const password = ref(""); const confirm = ref("");
const auth = useAuth(); const router = useRouter();

async function submit() {
  await auth.registerCookie({
    name: name.value, email: email.value,
    password: password.value, password_confirmation: confirm.value
  });
  router.push("/");
}
</script>

<template>
  <div class="min-h-screen grid place-items-center p-6 bg-gray-50">
    <form @submit.prevent="submit" class="bg-white p-6 rounded-2xl shadow w-full max-w-sm space-y-4">
      <h1 class="text-2xl font-bold">Register</h1>
      <label class="block"><span class="text-sm">Name</span>
        <input v-model="name" class="mt-1 w-full border rounded px-3 py-2" required />
      </label>
      <label class="block"><span class="text-sm">Email</span>
        <input v-model="email" type="email" class="mt-1 w-full border rounded px-3 py-2" required />
      </label>
      <label class="block"><span class="text-sm">Password</span>
        <input v-model="password" type="password" class="mt-1 w-full border rounded px-3 py-2" required />
      </label>
      <label class="block"><span class="text-sm">Confirm</span>
        <input v-model="confirm" type="password" class="mt-1 w-full border rounded px-3 py-2" required />
      </label>
      <button class="w-full rounded-xl py-2 border">Create account</button>
    </form>
  </div>
</template>
