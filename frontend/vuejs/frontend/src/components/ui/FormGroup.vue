<template>
  <div class="mb-4 w-full">
    <div class="flex justify-between items-center mb-1.5">
      <label v-if="label" class="block text-sm font-semibold text-text-primary dark:text-gray-300">
        {{ label }}
        <span v-if="required" class="text-danger ml-0.5">*</span>
      </label>
    </div>

    <slot></slot>

    <p v-if="hint && !error" class="mt-1 text-xs text-text-secondary dark:text-gray-400">
      {{ hint }}
    </p>
    
    <p v-if="error" class="mt-1 text-xs font-medium text-danger">
      {{ error }}
    </p>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  hint: String,
  error: String,
  required: { type: Boolean, default: false }
})
</script>