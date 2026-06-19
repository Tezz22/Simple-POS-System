<template>
  <div class="p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
      <div>
        <h1
          class="text-xl font-black text-text-primary dark:text-white tracking-tight flex items-center gap-2"
        >
          <Icon
            icon="heroicons:clipboard-document-list-solid"
            class="w-6 h-6 text-primary dark:text-primary-light"
          />
          Riwayat Transaksi
        </h1>
        <p class="text-xs text-text-secondary dark:text-gray-400 mt-0.5">
          Pantau dan audit seluruh rekaman penjualan kasir POS
        </p>
      </div>

      <!-- Tombol Cetak Laporan + dropdown opsi -->
      <div class="relative shrink-0" v-click-outside="closeReportMenu">
        <BaseButton variant="primary" size="md" @click="reportMenuOpen = !reportMenuOpen">
          <Icon icon="heroicons:document-arrow-down-solid" class="w-4 h-4 mr-1.5" />
          Cetak Laporan
          <Icon icon="heroicons:chevron-down-solid" class="w-3.5 h-3.5 ml-1.5" />
        </BaseButton>

        <Transition
          enter-active-class="transition duration-150 ease-out"
          enter-from-class="opacity-0 -translate-y-1"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="transition duration-100 ease-in"
          leave-from-class="opacity-100"
          leave-to-class="opacity-0"
        >
          <div
            v-if="reportMenuOpen"
            class="absolute right-0 mt-2 w-52 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 py-1.5 z-20"
          >
            <button
              type="button"
              class="w-full flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-text-primary dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700/60 transition-colors cursor-pointer"
              @click="printReportList"
            >
              <Icon
                icon="heroicons:printer-solid"
                class="w-4 h-4 text-text-secondary dark:text-gray-400"
              />
              Print Halaman Ini
            </button>
            <button
              type="button"
              class="w-full flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-text-primary dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700/60 transition-colors cursor-pointer"
              :disabled="exportingPdf"
              @click="exportReportPdf"
            >
              <Icon
                :icon="exportingPdf ? 'heroicons:arrow-path' : 'heroicons:document-text-solid'"
                :class="[
                  'w-4 h-4 text-text-secondary dark:text-gray-400',
                  exportingPdf && 'animate-spin',
                ]"
              />
              {{ exportingPdf ? 'Menyiapkan PDF...' : 'Export ke PDF' }}
            </button>
          </div>
        </Transition>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 print:hidden">
      <DataCard
        label="Total Transaksi"
        :value="totalRows"
        icon="heroicons:ticket-solid"
        color="info"
      />
      <DataCard
        label="Transaksi Lunas"
        :value="paidTransactions"
        icon="heroicons:check-circle-solid"
        color="success"
      />
      <DataCard
        label="Transaksi Batal"
        :value="cancelledTransactions"
        icon="heroicons:x-circle-solid"
        color="danger"
      />
      <DataCard
        label="Nilai Halaman Ini"
        :value="formatRupiah(totalRevenue)"
        icon="heroicons:banknotes-solid"
        color="primary"
      />
    </div>

    <!-- Filter Bar -->
    <SectionCard class="print:hidden">
      <div class="flex flex-col sm:flex-row gap-3 items-end">
        <div class="flex-1">
          <SearchInput
            v-model="filters.search"
            placeholder="Cari nomor invoice..."
            @input="onSearchInput"
          />
        </div>
        <div class="w-full sm:w-56">
          <BaseSelect
            v-model="filters.status"
            :options="statusOptions"
            placeholder="Semua Status"
            @change="onFilterChange"
          />
        </div>
        <BaseButton
          v-if="filters.search || filters.status"
          variant="ghost"
          size="md"
          @click="clearFilters"
        >
          <Icon icon="heroicons:x-mark" class="w-4 h-4 mr-1" />
          Reset
        </BaseButton>
      </div>
    </SectionCard>

    <!-- Loading -->
    <div v-if="loading" class="flex justify-center py-16 print:hidden">
      <LoadingSpinner size="lg" label="Memuat transaksi..." />
    </div>

    <!-- Empty State -->
    <EmptyState
      v-else-if="transactions.length === 0"
      title="Belum Ada Transaksi"
      description="Tidak ada rekaman penjualan yang cocok dengan filter saat ini."
      icon="🧾"
    >
      <template #action>
        <BaseButton v-if="filters.search || filters.status" variant="outline" @click="clearFilters">
          Hapus Filter
        </BaseButton>
      </template>
    </EmptyState>

    <!-- Tabel Transaksi -->
    <template v-else>
      <!-- Kop laporan, hanya tampil saat print -->
      <div class="hidden print:block mb-4">
        <h2 class="text-lg font-black">Laporan Riwayat Transaksi</h2>
        <p class="text-xs text-gray-600">Dicetak pada {{ formatDate(new Date()) }}</p>
      </div>

      <SectionCard id="report-table-area">
        <BaseTable :columns="tableFieldsForView" :rows="transactions" :loading="loading">
          <template #cell(invoice_number)="{ row }">
            <span class="font-mono font-bold text-primary dark:text-primary-light text-xs">
              {{ row.invoice_number }}
            </span>
          </template>

          <template #cell(transaction_date)="{ row }">
            <span class="text-xs text-text-secondary dark:text-gray-400">
              {{ formatDate(row.transaction_date) }}
            </span>
          </template>

          <template #cell(cashier)="{ row }">
            <div class="flex items-center gap-2">
              <div
                class="w-6 h-6 rounded-full bg-teal-100 dark:bg-teal-900/40 flex items-center justify-center text-[10px] font-black text-primary dark:text-primary-light shrink-0 print:hidden"
              >
                {{ (row.cashier?.name || 'S').charAt(0).toUpperCase() }}
              </div>
              <span class="text-xs font-medium text-text-primary dark:text-white">
                {{ row.cashier?.name || 'Sistem' }}
              </span>
            </div>
          </template>

          <template #cell(total_item)="{ row }">
            <span
              class="inline-flex items-center gap-1 text-xs font-semibold text-text-secondary dark:text-gray-300"
            >
              <Icon icon="heroicons:shopping-bag" class="w-3.5 h-3.5 print:hidden" />
              {{ row.total_item ?? row.items?.length ?? 0 }} item
            </span>
          </template>

          <template #cell(grand_total)="{ row }">
            <span class="text-xs font-black text-text-primary dark:text-white tracking-tight">
              {{ formatRupiah(row.grand_total) }}
            </span>
          </template>

          <template #cell(status)="{ row }">
            <BadgeStatus :status="row.status" size="sm" />
          </template>

          <template #cell(actions)="{ row }">
            <div class="flex items-center justify-end gap-1.5 print:hidden">
              <IconButton
                variant="secondary"
                size="sm"
                tooltip="Lihat Detail"
                @click="goToDetail(row)"
              >
                <Icon icon="heroicons:eye" class="w-4 h-4" />
              </IconButton>
            </div>
          </template>
        </BaseTable>

        <!-- Pagination -->
        <div class="mt-4 print:hidden">
          <TablePagination
            :current-page="currentPage"
            :total-items="totalRows"
            :per-page="perPage"
            @pageChange="onPageChange"
          />
        </div>
      </SectionCard>
    </template>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, onBeforeUnmount } from 'vue'
import { useRouter } from 'vue-router'
import { Icon } from '@iconify/vue'
import { jsPDF } from 'jspdf'
import autoTable from 'jspdf-autotable'
import api from '@/services/api'

import SearchInput from '@/components/ui/SearchInput.vue'
import BaseSelect from '@/components/ui/BaseSelect.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import BaseTable from '@/components/ui/BaseTable.vue'
import BadgeStatus from '@/components/ui/BadgeStatus.vue'
import IconButton from '@/components/ui/IconButton.vue'
import TablePagination from '@/components/ui/TablePagination.vue'
import DataCard from '@/components/ui/DataCard.vue'
import SectionCard from '@/components/ui/SectionCard.vue'

const router = useRouter()

const loading = ref(false)
const transactions = ref([])
const currentPage = ref(1)
const totalRows = ref(0)
const perPage = ref(10)

const reportMenuOpen = ref(false)
const exportingPdf = ref(false)

const filters = ref({
  search: '',
  status: '',
})

const statusOptions = [
  { value: 'paid', label: 'Lunas' },
  { value: 'cancelled', label: 'Dibatalkan' },
]

// Kolom aksi tetap dideklarasikan, tapi tombol di dalamnya hanya "Lihat Detail"
// (tombol cetak struk per-baris dihapus sesuai kebutuhan versi admin)
const tableFieldsForView = [
  { key: 'invoice_number', label: 'No. Invoice', width: '160px' },
  { key: 'transaction_date', label: 'Waktu', width: '150px' },
  { key: 'cashier', label: 'Kasir', width: '140px' },
  { key: 'total_item', label: 'Item', width: '90px' },
  { key: 'grand_total', label: 'Total', width: '130px' },
  { key: 'status', label: 'Status', width: '100px' },
  { key: 'actions', label: '', width: '70px' },
]

// --- Computed Stats ---
const paidTransactions = computed(
  () => transactions.value.filter((t) => t.status === 'paid').length,
)
const cancelledTransactions = computed(
  () => transactions.value.filter((t) => t.status === 'cancelled').length,
)
const totalRevenue = computed(() =>
  transactions.value
    .filter((t) => t.status === 'paid')
    .reduce((sum, t) => sum + Number(t.grand_total || 0), 0),
)

// --- API ---
const fetchTransactions = async () => {
  loading.value = true
  try {
    const response = await api.get('/cashier/transactions', {
      params: {
        page: currentPage.value,
        search: filters.value.search || undefined,
        status: filters.value.status || undefined,
      },
    })
    const resData = response.data.data
    transactions.value = resData.data ?? resData
    totalRows.value = resData.total ?? resData.length
    perPage.value = resData.per_page ?? 10
  } catch (err) {
    console.error('Gagal memuat riwayat transaksi:', err)
  } finally {
    loading.value = false
  }
}

// --- Event Handlers ---
let searchTimer = null
const onSearchInput = () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    currentPage.value = 1
    fetchTransactions()
  }, 400)
}

const onFilterChange = () => {
  currentPage.value = 1
  fetchTransactions()
}

const onPageChange = (page) => {
  currentPage.value = page
  fetchTransactions()
}

const clearFilters = () => {
  filters.value.search = ''
  filters.value.status = ''
  currentPage.value = 1
  fetchTransactions()
}

const goToDetail = (transaction) => {
  router.push(`/admin/transaction-history/${transaction.id}/detail`)
}

// --- Cetak Laporan ---
const closeReportMenu = () => {
  reportMenuOpen.value = false
}

const printReportList = () => {
  reportMenuOpen.value = false
  window.print()
}

const exportReportPdf = () => {
  const doc = new jsPDF({ orientation: 'landscape' })

  doc.setFontSize(14)
  doc.text('Laporan Riwayat Transaksi', 14, 16)

  autoTable(doc, {
    head: [['Invoice', 'Kasir', 'Total']],
    body: transactions.value.map(t => [
      t.invoice_number,
      t.cashier?.name ?? 'Sistem',
      formatRupiah(t.grand_total),
    ]),
  })

  doc.save('laporan-transaksi.pdf')
}

// --- Klik di luar dropdown menutup menu ---
const vClickOutside = {
  mounted(el, binding) {
    el._clickOutsideHandler = (event) => {
      if (!(el === event.target || el.contains(event.target))) {
        binding.value(event)
      }
    }
    document.addEventListener('click', el._clickOutsideHandler)
  },
  unmounted(el) {
    document.removeEventListener('click', el._clickOutsideHandler)
  },
}

// --- Helpers ---
const formatDate = (dateInput) => {
  if (!dateInput) return '-'
  return new Date(dateInput).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

const formatRupiah = (angka) =>
  new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(angka || 0)

onMounted(fetchTransactions)

onBeforeUnmount(() => {
  clearTimeout(searchTimer)
})
</script>

<style scoped>
@media print {
  .print\:hidden {
    display: none !important;
  }
  .print\:block {
    display: block !important;
  }
}
</style>
