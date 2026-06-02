<template>
  <div class="w-full">
    <label
      v-if="label"
      class="block text-sm font-semibold mb-1.5 text-text-primary dark:text-gray-300"
    >
      {{ label }}
    </label>

    <select
      :value="modelValue"
      @change="$emit('update:modelValue', $event.target.value)"
      :disabled="disabled"
      :class="[
        'w-full px-3 py-2 border rounded-lg focus:outline-hidden focus:ring-2 transition-all duration-200 text-sm bg-white text-text-primary dark:bg-gray-800 dark:text-white appearance-none cursor-pointer',
        error
          ? 'border-danger focus:ring-red-200'
          : 'border-gray-300 focus:ring-primary/20 focus:border-primary dark:border-gray-600 dark:focus:border-primary-light',
      ]"
    >
      <option v-if="placeholder" value="" disabled selected>{{ placeholder }}</option>
      <option v-for="opt in options" :key="opt.value" :value="opt.value">
        {{ opt.label }}
      </option>
    </select>

    <p v-if="error" class="mt-1 text-xs font-medium text-danger">
      {{ error }}
    </p>
  </div>
</template>

<script setup>
defineProps({
  modelValue: [String, Number],
  label: String,
  placeholder: String,
  error: String,
  disabled: { type: Boolean, default: false },
  options: {
    type: Array,
    default: () => [], // Struktur: [{ value: 'admin', label: 'Administrator' }]
  },
})

defineEmits(['update:modelValue'])
</script>
