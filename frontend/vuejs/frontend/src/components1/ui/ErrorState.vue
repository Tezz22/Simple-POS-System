<template>
  <div
    class="flex flex-col items-center justify-center text-center p-6 bg-red-50/40 dark:bg-red-950/10 rounded-2xl border border-red-100 dark:border-red-900/40 max-w-sm mx-auto select-none"
  >
    <span class="text-3xl mb-2">⚠️</span>
    <h3 class="text-xs font-black uppercase tracking-wider text-red-800 dark:text-red-400">
      Gagal Memuat Data
    </h3>
    <p class="text-[11px] text-red-700/80 dark:text-red-300/70 mt-1 leading-relaxed font-medium">
      {{ message }}
    </p>

    <button
      type="button"
      @click="$emit('retry')"
      class="mt-4 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition-colors cursor-pointer active:scale-95 shadow-sm"
    >
      🔄 Coba Lagi
    </button>
  </div>
</template>

<script setup>
defineProps({
  message: {
    type: String,
    default: 'Terjadi gangguan koneksi pada sistem API server.',
  },
})

defineEmits(['retry'])
</script>
