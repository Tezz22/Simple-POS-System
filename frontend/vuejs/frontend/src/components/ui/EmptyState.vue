<template>
  <div class="flex flex-col items-center justify-center text-center p-8 max-w-sm mx-auto my-6">
    <!-- Icon Ilustrasi Besar -->
    <div class="text-5xl text-gray-300 dark:text-gray-600 mb-4 animate-bounce-slow">
      <slot name="icon">
        <span>{{ icon || '📂' }}</span>
      </slot>
    </div>

    <!-- Judul Pesan Kosong -->
    <h4 class="text-base font-bold text-text-primary dark:text-white mb-1">
      {{ title }}
    </h4>

    <!-- Penjelasan Deskripsi Tambahan -->
    <p
      v-if="description"
      class="text-xs text-text-secondary dark:text-gray-400 mb-5 leading-relaxed"
    >
      {{ description }}
    </p>

    <!-- Tombol Aksi Tambah Data Baru (Slot) -->
    <div v-if="$slots.action" class="w-full flex justify-center">
      <slot name="action"></slot>
    </div>
  </div>
</template>

<script setup>
defineProps({
  icon: String, // Nama representasi emoji atau icon besar
  title: { type: String, default: 'Data Belum Tersedia' }, // Judul utama
  description: String, // Penjelasan atau ajakan bertindak (opsional)
})
</script>

<style scoped>
.animate-bounce-slow {
  animation: bounce 3s infinite;
}
@keyframes bounce {
  0%,
  100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-6px);
  }
}
</style>
