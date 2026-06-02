<template>
  <div class="flex flex-col gap-2">
    <div v-for="option in options" :key="option.value" class="inline-flex items-center">
      <label class="inline-flex items-center gap-2.5 cursor-pointer select-none group">
        <input
          type="radio"
          :value="option.value"
          :checked="modelValue === option.value"
          :disabled="disabled"
          @change="$emit('update:modelValue', option.value)"
          class="sr-only"
        />

        <div
          :class="[
            'w-5 h-5 rounded-full border flex items-center justify-center transition-all duration-150 grow-0 shrink-0',
            modelValue === option.value
              ? 'border-primary dark:border-primaryLight'
              : 'bg-white dark:bg-gray-950 border-gray-250 dark:border-gray-800 group-hover:border-primary dark:group-hover:border-primaryLight',
            disabled ? 'opacity-50 cursor-not-allowed bg-gray-50 dark:bg-gray-900' : '',
          ]"
        >
          <div
            v-if="modelValue === option.value"
            class="w-2.5 h-2.5 rounded-full bg-primary dark:bg-primaryLight animate-fade-in"
          ></div>
        </div>

        <span
          class="text-xs font-semibold transition-colors"
          :class="[disabled ? 'text-gray-400' : 'text-textPrimary dark:text-gray-200']"
        >
          {{ option.label }}
        </span>
      </label>
    </div>
  </div>
</template>

<script setup>
defineProps({
  modelValue: {
    type: [String, Number, Boolean],
    default: '',
  },
  options: {
    type: Array,
    required: true, // Format: [{ value: 'x', label: 'X' }]
  },
  disabled: {
    type: Boolean,
    default: false,
  },
})

defineEmits(['update:modelValue'])
</script>
