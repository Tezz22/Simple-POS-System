<template>
  <label class="inline-flex items-center gap-2.5 cursor-pointer select-none group">
    <input
      type="checkbox"
      :checked="modelValue"
      :disabled="disabled"
      @change="handleChange"
      class="sr-only"
    />

    <div
      :class="[
        'w-5 h-5 rounded-lg border flex items-center justify-center transition-all duration-150 grow-0 shrink-0',
        modelValue
          ? 'bg-primary border-primary text-white dark:bg-primaryLight dark:border-primaryLight dark:text-gray-900'
          : 'bg-white dark:bg-gray-950 border-gray-250 dark:border-gray-800 group-hover:border-primary dark:group-hover:border-primaryLight',
        disabled
          ? 'opacity-50 cursor-not-allowed pointer-events-none bg-gray-50 dark:bg-gray-900'
          : '',
      ]"
    >
      <svg
        v-if="modelValue"
        class="w-3.5 h-3.5 stroke-[3]"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
      </svg>
    </div>

    <span
      v-if="label"
      class="text-xs font-semibold transition-colors"
      :class="[disabled ? 'text-gray-400' : 'text-textPrimary dark:text-gray-200']"
    >
      {{ label }}
    </span>
  </label>
</template>

<script setup>
const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
  label: {
    type: String,
    default: '',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['update:modelValue'])

const handleChange = (event) => {
  emit('update:modelValue', event.target.checked)
}
</script>
