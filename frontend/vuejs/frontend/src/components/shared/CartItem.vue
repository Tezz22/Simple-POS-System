<template>
  <div
    class="flex items-center gap-3 py-3 border-b border-gray-100 dark:border-gray-700/60 last:border-0"
  >
    <div class="flex-1 min-w-0 space-y-0.5">
      <h5 class="text-xs font-bold text-text-primary dark:text-white truncate">
        {{ item.name }}
      </h5>
      <p class="text-[11px] font-medium text-text-secondary dark:text-gray-400">
        {{ formatRupiah(item.price) }} x {{ item.quantity }}
      </p>
    </div>

    <div
      class="flex items-center border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden shrink-0 bg-gray-50 dark:bg-gray-800"
    >
      <button
        type="button"
        @click="$emit('decreaseQty', item.id)"
        class="cursor-pointer px-2 py-1 text-xs hover:bg-gray-200 dark:hover:bg-gray-700 text-text-secondary dark:text-gray-300 font-bold transition-colors"
      >
        −
      </button>
      <span
        class="px-2 text-xs font-bold text-text-primary dark:text-white min-w-[24px] text-center"
      >
        {{ item.quantity }}
      </span>
      <button
        type="button"
        @click="$emit('increaseQty', item.id)"
        class="cursor-pointer px-2 py-1 text-xs hover:bg-gray-200 dark:hover:bg-gray-700 text-text-secondary dark:text-gray-300 font-bold transition-colors"
      >
        +
      </button>
    </div>

    <div
      class="text-xs font-black text-text-primary dark:text-white shrink-0 min-w-[70px] text-right tracking-tight"
    >
      {{ formatRupiah(item.selling_price * item.quantity) }}
    </div>

    <button
      type="button"
      @click="$emit('removeItem', item.id)"
      class="cursor-pointer text-gray-400 hover:text-danger p-1 rounded-md hover:bg-red-50 dark:hover:bg-red-950/20 transition-colors shrink-0"
      title="Hapus Item"
    >
      <svg
        class="h-3.5 w-3.5"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="2"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
        />
      </svg>
    </button>
  </div>
</template>

<script setup>
defineProps({
  item: {
    type: Object,
    required: true, // Struktur data: { id, name, price, qty }
  },
})

defineEmits(['increaseQty', 'decreaseQty', 'removeItem'])

const formatRupiah = (angka) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(angka)
}
</script>
