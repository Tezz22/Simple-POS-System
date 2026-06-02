<template>
  <div 
    v-if="visible" 
    :class="[
      'flex items-start gap-3 p-4 rounded-xl border text-sm transition-all duration-150',
      variantClasses[variant] || variantClasses.info
    ]"
  >
    <!-- Ikon Alert Dinamis -->
    <span class="text-base shrink-0 select-none">
      {{ iconClasses[variant] || 'ℹ️' }}
    </span>

    <!-- Konten Pesan -->
    <div class="flex-1 text-text-primary dark:text-gray-200 font-medium leading-relaxed">
      <slot></slot>
    </div>

    <!-- Tombol Tutup (Dismiss Button) -->
    <button 
      v-if="dismissible" 
      @click="handleClose" 
      class="cursor-pointer text-text-secondary hover:text-text-primary dark:text-gray-400 dark:hover:text-white p-0.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/5 transition-colors shrink-0"
    >
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  variant: {
    type: String,
    default: 'info' // Pilihan: success, error, warning, info
  },
  dismissible: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close'])
const visible = ref(true)

// Mapping class warna berdasarkan variant (Ini yang kurang/salah di file Anda)
const variantClasses = {
  success: 'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-950/20 dark:border-emerald-900/40 dark:text-emerald-400',
  error: 'bg-red-50 border-red-200 text-red-800 dark:bg-red-950/20 dark:border-red-900/40 dark:text-red-400',
  warning: 'bg-amber-50 border-amber-200 text-amber-800 dark:bg-amber-950/20 dark:border-amber-900/40 dark:text-amber-400',
  info: 'bg-blue-50 border-blue-200 text-blue-800 dark:bg-blue-950/20 dark:border-blue-900/40 dark:text-blue-400'
}

// Mapping emoji ikon pembantu
const iconClasses = {
  success: '✅',
  error: '🚨',
  warning: '⚠️',
  info: 'ℹ️'
}

const handleClose = () => {
  visible.value = false
  emit('close')
}
</script>