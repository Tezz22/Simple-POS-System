<template>
  <div
    class="p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-700 flex items-center justify-between transition-all duration-200 hover:shadow-md"
  >
    <div class="space-y-2">
      <!-- Judul/Kategori Data -->
      <span
        class="text-xs font-bold uppercase tracking-wider text-text-secondary dark:text-gray-400"
      >
        {{ label }}
      </span>
      <!-- Angka Ukuran Statistik -->
      <h3 class="text-2xl font-black tracking-tight text-text-primary dark:text-white">
        {{ value }}
      </h3>

      <!-- Informasi Tren Naik Turun Penjualan -->
      <div v-if="trend" class="flex items-center gap-1 text-xs font-semibold">
        <span :class="trend.direction === 'up' ? 'text-success' : 'text-danger'">
          {{ trend.direction === 'up' ? '▲' : '▼' }} {{ trend.value }}%
        </span>
        <span class="text-text-secondary dark:text-gray-400 font-normal">vs bulan lalu</span>
      </div>
    </div>

    <!-- Lingkaran Ikon Dekoratif -->
    <div :class="['p-3.5 rounded-xl text-white font-bold text-xl', colorClasses[color]]">
      <slot name="icon">
        <span>{{ icon || '📊' }}</span>
        <!-- Default Emoji/Icon placeholder -->
      </slot>
    </div>
  </div>
</template>

<script setup>
defineProps({
  label: { type: String, required: true }, // Contoh: "Total Pendapatan"
  value: { type: [String, Number], required: true }, // Contoh: "Rp 12.500.000"
  icon: String, // Menampung representasi icon atau emoji
  color: { type: String, default: 'primary' }, // primary, success, warning, danger, info
  trend: {
    type: Object,
    default: null, // Format: { value: 12, direction: 'up' }
  },
})

const colorClasses = {
  primary: 'bg-primary dark:bg-primary-light dark:text-gray-900',
  success: 'bg-success',
  warning: 'bg-warning',
  danger: 'bg-danger',
  info: 'bg-info',
}
</script>
