<template>
  <span
    :class="[
      'inline-flex items-center font-semibold rounded-full tracking-wide',
      sizeClasses[size],
      statusClasses[status],
    ]"
  >
    <span :class="['w-1.5 h-1.5 rounded-full mr-1.5', dotClasses[status]]"></span>
    <slot>{{ statusText[status] || status }}</slot>
  </span>
</template>

<script setup>
defineProps({
  status: { type: String, required: true }, // paid, draft, cancelled, active, inactive
  size: { type: String, default: 'md' }, // sm, md
})

const sizeClasses = {
  sm: 'px-2 py-0.5 text-[11px]',
  md: 'px-2.5 py-1 text-xs',
}

// Mapping Warna Latar & Teks Utama (Sesuai Panduan PDF)
const statusClasses = {
  paid: 'bg-green-50 text-green-700 border border-green-200 dark:bg-green-900/20 dark:text-green-400 dark:border-green-800/30',
  draft:
    'bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
  cancelled:
    'bg-red-50 text-red-700 border border-red-200 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800/30',
  active:
    'bg-teal-50 text-teal-700 border border-teal-200 dark:bg-teal-900/20 dark:text-teal-400 dark:border-teal-800/30',
  inactive:
    'bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
}

// Mapping Warna Titik Sensor
const dotClasses = {
  paid: 'bg-success',
  draft: 'bg-text-secondary',
  cancelled: 'bg-danger',
  active: 'bg-primary',
  inactive: 'bg-text-secondary',
}

// Terjemahan teks otomatis ke bahasa Indonesia
const statusText = {
  paid: 'Lunas',
  draft: 'Draf',
  cancelled: 'Dibatalkan',
  active: 'Aktif',
  inactive: 'Nonaktif',
}
</script>
