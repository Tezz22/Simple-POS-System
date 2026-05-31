<template>
  <div
    v-if="visible"
    :class="[
      'p-4 rounded-lg border flex items-start gap-3 transition-all duration-200',
      variantClasses[variant],
    ]"
  >
    <div class="flex-1 text-sm">
      <h5 v-if="title" class="font-bold mb-1">{{ title }}</h5>
      <slot></slot>
    </div>

    <button
      v-if="dismissible"
      @click="closeAlert"
      class="cursor-pointer p-0.5 rounded-sm hover:bg-black/5 dark:hover:bg-white/10 text-current transition-colors"
    >
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({
  variant: { type: String, default: 'info' }, // success, error, warning, info
  title: String,
  dismissible: { type: Boolean, default: false },
})

const visible = ref(true)

const closeAlert = () => {
  visible.value = false
}
</script>
