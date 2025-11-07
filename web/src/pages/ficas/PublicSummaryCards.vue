<!-- web/src/pages/components/PublicSummaryCards.vue -->
<script setup lang="ts">
import { onMounted, ref } from 'vue'
import api from '@/lib/api'

type Summary = {
  users: number
  sludinajumi: number
  pieteikumi: number
  parskati: number
  apliecinajumi: number
}

const data = ref<Summary | null>(null)
const loading = ref(true)
const error = ref('')

onMounted(async () => {
  try {
    const res = await api.get('/api/public/summary')
    data.value = res.data
  } catch (e: any) {
    error.value = e?.response?.data?.message || 'Neizdevās ielādēt datus'
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div class="rounded-2xl bg-white p-6 shadow flex flex-col">
    <h2 class="text-xl font-semibold text-neutral-800 mb-4">Publiskā statistika</h2>

    <div v-if="loading">Ielādē…</div>
    <div v-else-if="error" class="text-red-600 text-sm">{{ error }}</div>

    <div v-else class="grid grid-cols-2 md:grid-cols-3 gap-4">
      <div class="rounded-xl border p-4">
        <div class="text-sm text-neutral-500">Lietotāji</div>
        <div class="text-2xl font-semibold">{{ data?.users ?? 0 }}</div>
      </div>
      <div class="rounded-xl border p-4">
        <div class="text-sm text-neutral-500">Sludinājumi</div>
        <div class="text-2xl font-semibold">{{ data?.sludinajumi ?? 0 }}</div>
      </div>
      <div class="rounded-xl border p-4">
        <div class="text-sm text-neutral-500">Pieteikumi</div>
        <div class="text-2xl font-semibold">{{ data?.pieteikumi ?? 0 }}</div>
      </div>
      <div class="rounded-xl border p-4">
        <div class="text-sm text-neutral-500">Pārskati</div>
        <div class="text-2xl font-semibold">{{ data?.parskati ?? 0 }}</div>
      </div>
      <div class="rounded-xl border p-4">
        <div class="text-sm text-neutral-500">Apliecinājumi</div>
        <div class="text-2xl font-semibold">{{ data?.apliecinajumi ?? 0 }}</div>
      </div>
    </div>
  </div>
</template>
