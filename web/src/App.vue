<template>
  <div class="min-h-screen">
    <nav class="p-4 flex gap-4 border-b">
      <router-link to="/" class="text-indigo-600 underline">Sākumlapa</router-link>
      <router-link to="/about" class="text-indigo-600 underline">Par projektu</router-link>
    </nav>
    <router-view />
  </div>
</template>
