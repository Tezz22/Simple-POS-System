<template>
  <div class="flex flex-col gap-1 w-full max-w-[120px] select-none font-sans">
    <div class="flex justify-between items-center text-[10px] font-bold">
      <span class="text-gray-400">Stok:</span>
      <span :class="textClass">{{ stock }} Porsi</span>
    </div>
    <div class="w-full h-1.5 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
      <div
        :class="['h-full rounded-full transition-all duration-300', barClass]"
        :style="{ width: `${Math.min((stock / Math.max(minStock * 3, 1)) * 100, 100)}%` }"
      ></div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  stock: { type: Number, required: true },
  minStock: { type: Number, default: 5 }, // Batas minimal sebelum dinyatakan menipis
})

const isHabis = computed(() => props.stock <= 0)
const isMenipis = computed(() => props.stock > 0 && props.stock <= props.minStock)

const textClass = computed(() => {
  if (isHabis.value) return 'text-red-500 font-black'
  if (isMenipis.value) return 'text-amber-500 font-bold'
  return 'text-green-600 dark:text-green-400 font-bold'
})

const barClass = computed(() => {
  if (isHabis.value) return 'bg-red-500'
  if (isMenipis.value) return 'bg-amber-500'
  return 'bg-green-500 dark:bg-green-400'
})
</script>
