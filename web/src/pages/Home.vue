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
  </div>
</template>
