<template>
  <div
    class="space-y-2.5 border-t border-gray-100 dark:border-gray-700 pt-4 text-xs font-medium text-text-secondary dark:text-gray-400"
  >
    <div class="flex justify-between items-center">
      <span>Subtotal</span>
      <span class="text-text-primary dark:text-white font-semibold">{{
        formatRupiah(subtotal)
      }}</span>
    </div>

    <div class="flex justify-between items-center">
      <span>PPN (11%)</span>
      <span class="text-text-primary dark:text-white font-semibold">{{ formatRupiah(tax) }}</span>
    </div>

    <div
      class="flex justify-between items-center border-t border-dashed border-gray-200 dark:border-gray-700 pt-3 mt-1"
    >
      <span class="text-sm font-bold text-text-primary dark:text-white">Total Tagihan</span>
      <span class="text-base font-black text-primary dark:text-primary-light tracking-tight">
        {{ formatRupiah(total) }}
      </span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  subtotal: { type: Number, default: 0 },
})

// Logika kalkulasi bisnis otomatis
const tax = computed(() => props.subtotal * 0.11)
const total = computed(() => props.subtotal + tax.value)

const formatRupiah = (angka) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(angka)
}
</script>
