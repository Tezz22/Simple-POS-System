<template>
  <button
    :type="type"
    :disabled="disabled || loading"
    :class="[
      'inline-flex items-center justify-center font-semibold rounded-lg transition-colors duration-200 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed',
      variantClasses[variant],
      sizeClasses[size],
      wFull ? 'w-full' : '',
    ]"
  >
    <svg
      v-if="loading"
      class="animate-spin -ml-1 mr-2 h-4 w-4 text-current"
      fill="none"
      viewBox="0 0 24 24"
    >
      <circle
        class="opacity-25"
        cx="12"
        cy="12"
        r="10"
        stroke="currentColor"
        stroke-width="4"
      ></circle>
      <path
        class="opacity-75"
        fill="currentColor"
        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
      ></path>
    </svg>

    <slot>{{ loading ? 'Memuat...' : 'Kirim' }}</slot>
  </button>
</template>

<script setup>
defineProps({
  type: { type: String, default: 'button' },
  variant: { type: String, default: 'primary' }, // primary, secondary, danger, ghost, outline
  size: { type: String, default: 'md' }, // sm, md, lg
  loading: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
  wFull: { type: Boolean, default: false },
})

const variantClasses = {
  primary:
    'bg-primary hover:bg-primary-hover text-white dark:bg-primary-light dark:hover:bg-primary dark:text-gray-900',
  secondary:
    'bg-gray-200 hover:bg-gray-300 text-text-primary dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white',
  danger: 'bg-danger hover:bg-red-600 text-white',
  outline:
    'border border-gray-300 hover:bg-gray-50 text-text-primary dark:border-gray-600 dark:hover:bg-gray-800 dark:text-white',
  ghost: 'hover:bg-gray-100 text-text-primary dark:hover:bg-gray-800 dark:text-white',
}

const sizeClasses = {
  sm: 'px-3 py-1.5 text-xs',
  md: 'px-4 py-2 text-sm',
  lg: 'px-5 py-2.5 text-base',
}
</script>
