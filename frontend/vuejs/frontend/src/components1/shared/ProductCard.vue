<template>
  <div
    :class="[
      'bg-white dark:bg-gray-800 rounded-2xl border transition-all duration-200 overflow-hidden flex flex-col justify-between select-none shadow-xs',
      isOutofStock
        ? 'opacity-60 border-gray-200 dark:border-gray-700'
        : 'border-gray-100 hover:border-primary/30 dark:border-gray-700 dark:hover:border-primary-light/30 hover:shadow-md',
    ]"
  >
    <div class="relative bg-gray-100 dark:bg-gray-700 pt-[75%] w-full overflow-hidden shrink-0">
      <img
        v-if="product.image_url"
        :src="product.image_url"
        :alt="product.name"
        class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 hover:scale-105"
      />
      <div
        v-else
        class="absolute inset-0 flex items-center justify-center text-4xl bg-teal-50 dark:bg-teal-950/30 text-primary dark:text-primary-light font-bold"
      >
        🍛
      </div>

      <div
        v-if="isOutofStock"
        class="absolute inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center"
      >
        <span
          class="bg-danger text-white text-xs font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-md"
        >
          Habis
        </span>
      </div>
    </div>

    <div class="p-4 flex-1 flex flex-col justify-between gap-3">
      <div class="space-y-1">
        <span
          class="text-[10px] font-bold uppercase tracking-wider text-primary dark:text-primary-light bg-teal-50 dark:bg-teal-950/40 px-2 py-0.5 rounded-sm"
        >
          {{ product.category?.name || 'Menu' }}
        </span>
        <h4 class="text-sm font-bold text-text-primary dark:text-white line-clamp-2 leading-snug">
          {{ product.name }}
        </h4>
      </div>

      <div class="flex items-center justify-between gap-2 mt-auto">
        <div class="space-y-0.5">
          <p class="text-xs text-text-secondary dark:text-gray-400">Harga</p>
          <p class="text-sm font-black text-primary dark:text-primary-light tracking-tight">
            {{ formatRupiah(product.price) }}
          </p>
        </div>

        <button
          type="button"
          :disabled="isOutofStock"
          @click="$emit('addToCart', product)"
          class="cursor-pointer p-2 rounded-xl bg-primary hover:bg-primary-hover text-white dark:bg-primary-light dark:hover:bg-primary dark:text-gray-900 disabled:bg-gray-200 dark:disabled:bg-gray-700 disabled:text-gray-400 transition-colors duration-150 shadow-xs"
          title="Tambah ke Keranjang"
        >
          <svg
            class="h-4 w-4"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2.5"
          >
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  product: {
    type: Object,
    required: true, // Struktur data: { id, name, price, stock, image_url, category: { name } }
  },
})

defineEmits(['addToCart'])

const isOutofStock = computed(() => props.product.stock <= 0)

const formatRupiah = (angka) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(angka)
}
</script>
