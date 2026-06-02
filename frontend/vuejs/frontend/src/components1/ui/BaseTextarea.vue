<template>
  <div class="w-full flex flex-col relative">
    <textarea
      :value="modelValue"
      @input="handleInput"
      :rows="rows"
      :maxlength="maxlength"
      :disabled="disabled"
      :placeholder="placeholder"
      :class="[
        'w-full px-4 py-3 rounded-xl border text-xs font-medium transition-all duration-150 outline-none resize-y min-h-[80px]',
        disabled
          ? 'bg-gray-50 dark:bg-gray-900 text-gray-400 border-gray-200 dark:border-gray-800 cursor-not-allowed'
          : 'bg-white dark:bg-gray-950 border-gray-250 dark:border-gray-800 text-textPrimary dark:text-gray-100 focus:border-primary dark:focus:border-primaryLight focus:ring-2 focus:ring-primary/10 dark:focus:ring-primaryLight/10',
      ]"
    ></textarea>

    <div
      v-if="showCount && maxlength"
      class="absolute bottom-2.5 right-3 text-[10px] font-bold tracking-wide pointer-events-none select-none transition-colors"
      :class="[
        karakterTersisa <= 10
          ? 'text-red-500 dark:text-red-400 font-black animate-pulse'
          : 'text-textSecondary dark:text-gray-500',
      ]"
    >
      {{ modelValue?.length || 0 }}/{{ maxlength }}
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
  rows: {
    type: Number,
    default: 3,
  },
  maxlength: {
    type: [Number, String],
    default: null,
  },
  placeholder: {
    type: String,
    default: '',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  showCount: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['update:modelValue'])

// Menghitung sisa karakter untuk memberikan feedback warna jika teks hampir penuh
const karakterTersisa = computed(() => {
  if (!props.maxlength) return 999
  return props.maxlength - (props.modelValue?.length || 0)
})

const handleInput = (event) => {
  emit('update:modelValue', event.target.value)
}
</script>
