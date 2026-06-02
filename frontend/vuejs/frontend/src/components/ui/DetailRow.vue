<template>
  <div class="flex items-center justify-between py-2.5 text-xs border-b border-gray-100 dark:border-gray-800/60 last:border-0">
    <span class="font-medium text-textSecondary dark:text-gray-400">
      {{ label }}
    </span>

    <span class="font-bold text-textPrimary dark:text-white text-right">
      <slot>
        {{ value }}
      </slot>
    </span>
  </div>
</template>

<script setup>
defineProps({
  label: {
    type: String,
    required: true
  },
  value: {
    type: [String, Number],
    default: ''
  }
})
</script>