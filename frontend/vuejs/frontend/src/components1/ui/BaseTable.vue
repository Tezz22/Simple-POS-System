<template>
  <div
    class="w-full overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 shadow-xs"
  >
    <table class="w-full text-left border-collapse text-sm text-text-primary dark:text-gray-200">
      <!-- Header Tabel -->
      <thead
        class="bg-gray-50 dark:bg-gray-800 text-xs font-bold uppercase tracking-wider text-text-secondary dark:text-gray-400 border-b border-gray-200 dark:border-gray-700"
      >
        <tr>
          <slot name="header">
            <th
              v-for="col in columns"
              :key="col.key"
              :style="{ width: col.width }"
              :class="[
                'px-6 py-3.5',
                col.sortable
                  ? 'cursor-pointer select-none hover:bg-gray-100 dark:hover:bg-gray-700'
                  : '',
              ]"
              @click="col.sortable && $emit('sort', col.key)"
            >
              <div class="flex items-center gap-1.5">
                {{ col.label }}
                <span v-if="col.sortable" class="text-gray-400"
                  >import { ArrowUpDown } from 'lucide-vue-next' ↕️</span
                >
              </div>
            </th>
          </slot>
        </tr>
      </thead>

      <!-- Body Tabel -->
      <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
        <!-- Keadaan Loading (Skeleton Rows) -->
        <template v-if="loading">
          <tr v-for="i in 3" :key="'skeleton-' + i" class="animate-pulse">
            <td v-for="col in columns" :key="'cell-' + col.key" class="px-6 py-4">
              <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded-sm w-3/4"></div>
            </td>
          </tr>
        </template>

        <!-- Keadaan Data Kosong -->
        <tr v-else-if="rows.length === 0">
          <td
            :colspan="columns.length"
            class="px-6 py-12 text-center text-text-secondary dark:text-gray-400"
          >
            <div class="flex flex-col items-center justify-center gap-2">
              <span class="text-3xl">📦</span>
              <p class="font-medium text-sm">Tidak ada data untuk ditampilkan</p>
            </div>
          </td>
        </tr>

        <!-- Render Baris Data Asli -->
        <template v-else>
          <tr
            v-for="(row, rowIndex) in rows"
            :key="row.id || rowIndex"
            class="hover:bg-gray-50/70 dark:hover:bg-gray-800/40 transition-colors duration-150"
          >
            <td v-for="col in columns" :key="col.key" class="px-6 py-4 whitespace-nowrap">
              <!-- Gunakan slot dinamis per kolom jika butuh kustomisasi visual -->
              <slot :name="`cell(${col.key})`" :row="row" :value="row[col.key]">
                {{ row[col.key] }}
              </slot>
            </td>
          </tr>
        </template>
      </tbody>
    </table>
  </div>
</template>

<script setup>
defineProps({
  columns: {
    type: Array,
    required: true, // Format: [{ key: 'name', label: 'Nama Menu', sortable: true, width: '200px' }]
  },
  rows: {
    type: Array,
    required: true, // Array objek data dari backend
  },
  loading: {
    type: Boolean,
    default: false, // Mengaktifkan efek skeleton loading
  },
})

defineEmits(['sort']) // Memicu sorting ketika header di-klik
</script>
