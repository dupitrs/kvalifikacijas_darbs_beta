<script setup lang="ts"></script>

<template>
  <main class="min-h-screen flex items-center justify-center p-6 bg-gray-50">
    <div class="max-w-3xl w-full grid md:grid-cols-[1fr_200px] gap-6">
      <section class="p-8 bg-white rounded-2xl shadow">
        <h1 class="text-3xl font-bold mb-4">BDUS2</h1>
        <p class="text-gray-600">
          Laipni lūgti brīvprātīgā darba uzskaites sistēmā. Šī ir minimāla sākumlapa ar īsu ievadu.
        </p>
      </section>

      <aside class="p-6 bg-white rounded-2xl shadow h-fit">
        <h2 class="text-xl font-semibold mb-3">Sākt</h2>
        <nav class="flex flex-col gap-2">
          <RouterLink class="underline" to="/login">Login</RouterLink>
          <RouterLink class="underline" to="/register">Register</RouterLink>
        </nav>
      </aside>
    </div>
  </main>
</template>
