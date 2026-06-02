<template>
  <Transition name="fade">
    <div
      v-if="show"
      :class="[
        'fixed inset-0 z-50 flex flex-col items-center justify-center p-4 transition-all duration-200',
        blur ? 'backdrop-blur-sm bg-black/40' : 'bg-black/50',
      ]"
    >
      <div
        class="bg-white dark:bg-gray-900 rounded-2xl px-6 py-5 shadow-2xl flex flex-col items-center gap-3 border border-gray-100 dark:border-gray-800 text-center max-w-xs"
      >
        <svg
          class="animate-spin h-8 w-8 text-primary dark:text-primaryLight"
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
          />
          <path
            class="opacity-75"
            fill="currentColor"
            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
          />
        </svg>

        <p class="text-xs font-bold text-textPrimary dark:text-gray-100 leading-normal">
          {{ message }}
        </p>
      </div>
    </div>
  </Transition>
</template>

<script setup>
defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  message: {
    type: String,
    default: 'Memuat data...',
  },
  blur: {
    type: Boolean,
    default: false,
  },
})
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
