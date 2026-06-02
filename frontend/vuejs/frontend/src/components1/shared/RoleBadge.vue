<template>
  <span
    :class="[
      'inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider select-none w-fit border',
      role === 'admin'
        ? 'bg-purple-50 dark:bg-purple-950/20 text-purple-600 dark:text-purple-400 border-purple-200/40'
        : 'bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 border-blue-200/40',
    ]"
  >
    🛡️ {{ role === 'admin' ? 'Administrator' : 'Staf Kasir' }}
  </span>
</template>

<script setup>
defineProps({
  role: {
    type: String,
    required: true, // admin atau kasir
  },
})
</script>
