<template>
  <div class="flex items-center justify-center gap-1.5 select-none py-2">
    <button
      type="button"
      :disabled="currentPage === 1"
      @click="changePage(currentPage - 1)"
      class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 text-textSecondary dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-850 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer transition-colors"
    >
      ◀
    </button>

    <template v-for="(page, idx) in visiblePages" :key="idx">
      <span
        v-if="page === '...'"
        class="w-8 h-8 flex items-center justify-center text-xs font-bold text-gray-400"
      >
        •••
      </span>

      <button
        v-else
        type="button"
        @click="changePage(page)"
        :class="[
          'w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-all cursor-pointer border',
          currentPage === page
            ? 'bg-primary border-primary text-white dark:bg-primaryLight dark:border-primaryLight dark:text-gray-900 shadow-xs'
            : 'bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-800 text-textSecondary dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-850',
        ]"
      >
        {{ page }}
      </button>
    </template>

    <button
      type="button"
      :disabled="currentPage === totalPages"
      @click="changePage(currentPage + 1)"
      class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 text-textSecondary dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-850 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer transition-colors"
    >
      ▶
    </button>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  currentPage: {
    type: Number,
    required: true,
  },
  totalPages: {
    type: Number,
    required: true,
  },
  maxVisible: {
    type: Number,
    default: 5, // Batas maksimal tombol angka yang tampil berjejer
  },
})

const emit = defineEmits(['pageChange'])

// Logika algoritma pembatas angka halaman (Smart Pagination dengan '...')
const visiblePages = computed(() => {
  const pages = []
  const total = props.totalPages
  const current = props.currentPage
  const max = props.maxVisible

  if (total <= max) {
    for (let i = 1; i <= total; i++) pages.push(i)
  } else {
    pages.push(1)

    let start = Math.max(2, current - 1)
    let end = Math.min(total - 1, current + 1)

    if (current <= 2) {
      end = max - 1
    } else if (current >= total - 1) {
      start = total - (max - 2)
    }

    if (start > 2) pages.push('...')

    for (let i = start; i <= end; i++) {
      pages.push(i)
    }

    if (end < total - 1) pages.push('...')

    pages.push(total)
  }
  return pages
})

const changePage = (page) => {
  if (page === '...' || page < 1 || page > props.totalPages) return
  emit('pageChange', page)
}
</script>
