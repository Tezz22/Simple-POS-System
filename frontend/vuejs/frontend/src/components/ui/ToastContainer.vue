<template>
  <div class="fixed bottom-4 right-4 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none">
    <TransitionGroup
      enter-active-class="transform ease-out duration-300 transition"
      enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
      enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
      leave-active-class="transition ease-in duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-for="toast in toastStore.toasts"
        :key="toast.id"
        :class="[
          'pointer-events-auto w-full p-4 rounded-xl shadow-lg border flex items-start gap-3 bg-white dark:bg-gray-800',
          typeClasses[toast.type],
        ]"
      >
        <div class="flex-1 text-sm font-semibold text-text-primary dark:text-white">
          {{ toast.message }}
        </div>
        <button
          @click="toastStore.remove(toast.id)"
          class="cursor-pointer text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
        >
          <svg
            class="h-4 w-4"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
          >
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup>
import { useToastStore } from '@/stores/toast'

const toastStore = useToastStore()

const typeClasses = {
  success: 'border-green-200 dark:border-green-900/50 border-l-4 border-l-success',
  error: 'border-red-200 dark:border-red-900/50 border-l-4 border-l-danger',
  warning: 'border-amber-200 dark:border-amber-900/50 border-l-4 border-l-warning',
  info: 'border-cyan-200 dark:border-cyan-900/50 border-l-4 border-l-info',
}
</script>
