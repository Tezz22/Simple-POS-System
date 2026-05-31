<template>
  <div
    class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs p-6 transition-all duration-200"
  >
    <!-- Area Judul Atas Card -->
    <div
      v-if="title || $slots['header-action']"
      class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-4 mb-5"
    >
      <h4 v-if="title" class="text-base font-bold text-text-primary dark:text-white tracking-tight">
        {{ title }}
      </h4>
      <!-- Slot Tombol Aksi di Pojok Kanan Atas -->
      <div class="flex items-center gap-2">
        <slot name="header-action"></slot>
      </div>
    </div>

    <!-- Area Konten Utama -->
    <div>
      <slot></slot>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String, // Judul di pojok kiri atas card (opsional)
})
</script>
