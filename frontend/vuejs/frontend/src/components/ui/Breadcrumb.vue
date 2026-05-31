<template>
  <nav
    class="flex text-xs font-medium text-text-secondary dark:text-gray-400"
    aria-label="Breadcrumb"
  >
    <ol class="inline-flex items-center space-x-1 md:space-x-2">
      <li v-for="(item, index) in items" :key="index" class="inline-flex items-center">
        <span v-if="index > 0" class="mx-1.5 md:mx-2 text-gray-300 dark:text-gray-600 text-sm"
          >/</span
        >

        <span
          v-if="index === items.length - 1"
          class="text-text-primary dark:text-white font-semibold truncate max-w-[150px] sm:max-w-none"
        >
          {{ item.label }}
        </span>

        <RouterLink
          v-else
          :to="item.to || '#'"
          class="hover:text-primary dark:hover:text-primary-light transition-colors"
        >
          {{ item.label }}
        </RouterLink>
      </li>
    </ol>
  </nav>
</template>

<script setup>
defineProps({
  items: {
    type: Array,
    required: true, // Format: [{ label: 'Dashboard', to: '/admin/dashboard' }, { label: 'Produk' }]
  },
})
</script>
