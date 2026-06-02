<template>
  <div class="w-full">
    <div class="flex border-b border-gray-200 dark:border-gray-700 overflow-x-auto no-scrollbar">
      <button
        v-for="tab in tabs"
        :key="tab.value"
        type="button"
        @click="$emit('update:modelValue', tab.value)"
        :class="[
          'cursor-pointer px-5 py-3 text-sm font-semibold border-b-2 whitespace-nowrap transition-colors duration-150',
          modelValue === tab.value
            ? 'border-primary text-primary dark:border-primary-light dark:text-primary-light'
            : 'border-transparent text-text-secondary dark:text-gray-400 hover:text-text-primary dark:hover:text-white hover:border-gray-300 dark:hover:border-gray-600',
        ]"
      >
        {{ tab.label }}
      </button>
    </div>

    <div class="py-4">
      <slot :activeTab="modelValue"></slot>
    </div>
  </div>
</template>

<script setup>
defineProps({
  modelValue: { type: String, required: true }, // Tab value yang aktif saat ini
  tabs: {
    type: Array,
    required: true, // Format: [{ value: 'makanan', label: 'Makanan' }, { value: 'minuman', label: 'Minuman' }]
  },
})

defineEmits(['update:modelValue'])
</script>

<style scoped>
/* Sembunyikan scrollbar bawaan agar navigasi rapi */
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
