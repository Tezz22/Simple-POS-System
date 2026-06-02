<template>
  <div
    class="flex flex-col sm:flex-row items-center justify-between gap-4 px-6 py-4 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 rounded-b-xl text-sm"
  >
    <!-- Info baris data -->
    <div class="text-text-secondary dark:text-gray-400 text-xs sm:text-sm">
      Menampilkan
      <span class="font-bold text-text-primary dark:text-white">{{ startItem }}</span> sampai
      <span class="font-bold text-text-primary dark:text-white">{{ endItem }}</span> dari
      <span class="font-bold text-text-primary dark:text-white">{{ totalItems }}</span> data
    </div>

    <!-- Tombol Navigasi Navigasi -->
    <div class="flex items-center gap-2">
      <button
        type="button"
        :disabled="currentPage === 1"
        @click="$emit('pageChange', currentPage - 1)"
        class="cursor-pointer px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 font-medium text-text-primary dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-40 disabled:cursor-not-allowed transition duration-200"
      >
        Sebelumnya
      </button>

      <span class="text-xs font-semibold text-text-secondary px-2">
        Halaman {{ currentPage }} dari {{ totalPages }}
      </span>

      <button
        type="button"
        :disabled="currentPage === totalPages || totalPages === 0"
        @click="$emit('pageChange', currentPage + 1)"
        class="cursor-pointer px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 font-medium text-text-primary dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-40 disabled:cursor-not-allowed transition duration-200"
      >
        Selanjutnya
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  currentPage: { type: Number, required: true }, // Halaman aktif saat ini
  totalItems: { type: Number, required: true }, // Total seluruh data di database
  perPage: { type: Number, default: 10 }, // Jumlah data per halaman
})

defineEmits(['pageChange']) // Mengirim info perubahan nomor halaman

const totalPages = computed(() => Math.ceil(props.totalItems / props.perPage))
const startItem = computed(() =>
  props.totalItems === 0 ? 0 : (props.currentPage - 1) * props.perPage + 1,
)
const endItem = computed(() => Math.min(props.currentPage * props.perPage, props.totalItems))
</script>
