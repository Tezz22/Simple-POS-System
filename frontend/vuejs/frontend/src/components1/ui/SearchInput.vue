<template>
  <div class="relative w-full flex items-center">
    <span class="absolute left-4 text-gray-400 select-none text-base pointer-events-none">
      🔍
    </span>

    <input
      type="text"
      :value="modelValue"
      @input="handleInput"
      :placeholder="placeholder"
      :disabled="disabled"
      class="w-full pl-11 pr-10 py-3 bg-white dark:bg-gray-950 border border-gray-250 dark:border-gray-800 rounded-xl text-xs font-medium text-textPrimary dark:text-gray-100 outline-none transition-all focus:border-primary dark:focus:border-primaryLight focus:ring-2 focus:ring-primary/10 dark:focus:ring-primaryLight/10 disabled:opacity-50 disabled:bg-gray-50 dark:disabled:bg-gray-900"
    />

    <button
      v-if="modelValue && !disabled"
      type="button"
      @click="clearInput"
      class="absolute right-3.5 p-0.5 rounded-md text-gray-400 hover:text-textPrimary dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 cursor-pointer transition-colors"
    >
      <svg
        class="h-3.5 w-3.5"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="2.5"
      >
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>
</template>

<script setup>
const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
  placeholder: {
    type: String,
    default: 'Cari sesuatu...',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['update:modelValue'])

const handleInput = (event) => {
  emit('update:modelValue', event.target.value)
}

const clearInput = () => {
  emit('update:modelValue', '')
}
</script>
