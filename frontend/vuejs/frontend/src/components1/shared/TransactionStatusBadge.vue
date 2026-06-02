<template>
  <span 
    :class="[
      'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider select-none w-fit',
      statusClasses[status] || 'bg-gray-100 text-gray-600'
    ]"
  >
    <span class="w-1.5 h-1.5 rounded-full bg-current animate-pulse"></span>
    {{ statusLabel }}
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  status: {
    type: String,
    required: true // paid, draft, pending, cancelled
  }
})

const statusLabel = computed(() => {
  const labels = { paid: 'Lunas', draft: 'Draft Nota', pending: 'Tunda', cancelled: 'Batal' }
  return labels[props.status] || props.status
})

const statusClasses = {
  paid: 'bg-green-50 dark:bg-green-950/20 text-green-600 dark:text-green-400 border border-green-200/40',
  draft: 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-300/20',
  pending: 'bg-amber-50 dark:bg-amber-950/20 text-amber-600 dark:text-amber-400 border border-amber-200/40',
  cancelled: 'bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400 border border-red-200/40'
}
</script>