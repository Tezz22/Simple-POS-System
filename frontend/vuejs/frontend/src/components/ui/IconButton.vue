<template>
  <div class="relative inline-block group">
    <button
      :type="type"
      :disabled="disabled || loading"
      @click="$emit('click', $event)"
      :class="[
        'flex items-center justify-center rounded-xl transition-all duration-150 cursor-pointer shrink-0 select-none',
        sizeClasses[size],
        variantClasses[variant],
        disabled || loading ? 'opacity-50 cursor-not-allowed pointer-events-none' : '',
      ]"
    >
      <svg v-if="loading" class="animate-spin h-4 w-4 text-current" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
        <path
          class="opacity-75"
          fill="currentColor"
          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
        />
      </svg>

      <span v-else class="text-base leading-none">
        <slot>{{ icon }}</slot>
      </span>
    </button>

    <div
      v-if="tooltip"
      class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2.5 py-1 bg-gray-900 dark:bg-gray-800 text-white text-[10px] font-bold rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-150 whitespace-nowrap z-50 shadow-md border border-gray-800 dark:border-gray-700"
    >
      {{ tooltip }}
      <div
        class="absolute top-full left-1/2 -translate-x-1/2 -mt-1 border-4 border-transparent border-t-gray-900 dark:border-t-gray-800"
      ></div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  icon: {
    type: String,
    default: '',
  },
  variant: {
    type: String,
    default: 'primary', // Pilihan: primary, secondary, outline, danger, ghost
  },
  size: {
    type: String,
    default: 'md', // Pilihan: sm, md, lg
  },
  tooltip: {
    type: String,
    default: '', // Jika diisi, otomatis memunculkan tooltip melayang di atasnya
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  loading: {
    type: Boolean,
    default: false,
  },
  type: {
    type: String,
    default: 'button',
  },
})

defineEmits(['click'])

// Mapping ukuran padding dan dimensi tombol
const sizeClasses = {
  sm: 'w-8 h-8 text-xs',
  md: 'w-10 h-10 text-sm',
  lg: 'w-12 h-12 text-base',
}

// Mapping warna varian agar sinkron dengan BaseButton toko kita
const variantClasses = {
  primary: 'bg-primary text-white hover:bg-teal-700 active:scale-95 shadow-xs',
  secondary:
    'bg-gray-100 dark:bg-gray-800 text-textPrimary dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700/80 active:scale-95',
  outline:
    'bg-transparent border border-gray-200 dark:border-gray-700 text-textSecondary dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 active:scale-95',
  danger: 'bg-red-500 text-white hover:bg-red-600 active:scale-95 shadow-xs',
  ghost:
    'bg-transparent text-textSecondary dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800/60 active:scale-95',
}
</script>
