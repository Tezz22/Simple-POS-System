<template>
  <div class="space-y-6 ml-6">
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
          Rekaman seluruh nota penjualan kasir POS
        </p>
      </div>
    </div>

    <!-- Stats Cards — menggunakan DataCard dari components/ui -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
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
    <SectionCard>
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
    <div v-if="loading" class="flex justify-center py-16">
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
      <SectionCard>
        <BaseTable :columns="tableFields" :rows="transactions" :loading="loading">
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
                class="w-6 h-6 rounded-full bg-teal-100 dark:bg-teal-900/40 flex items-center justify-center text-[10px] font-black text-primary dark:text-primary-light shrink-0"
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
              <Icon icon="heroicons:shopping-bag" class="w-3.5 h-3.5" />
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
            <div class="flex items-center justify-end gap-1.5">
              <IconButton
                variant="secondary"
                size="sm"
                tooltip="Lihat Detail"
                @click="goToDetail(row)"
              >
                <Icon icon="heroicons:eye" class="w-4 h-4" />
              </IconButton>
              <IconButton variant="outline" size="sm" tooltip="Cetak Struk" @click="goToPrint(row)">
                <Icon icon="heroicons:printer" class="w-4 h-4" />
              </IconButton>
            </div>
          </template>
        </BaseTable>

        <!-- Pagination -->
        <div class="mt-4">
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
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { Icon } from '@iconify/vue'
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

const filters = ref({
  search: '',
  status: '',
})

const statusOptions = [
  { value: 'paid', label: 'Lunas' },
  { value: 'cancelled', label: 'Dibatalkan' },
]

const tableFields = [
  { key: 'invoice_number', label: 'No. Invoice', width: '160px' },
  { key: 'transaction_date', label: 'Waktu', width: '150px' },
  { key: 'cashier', label: 'Kasir', width: '140px' },
  { key: 'total_item', label: 'Item', width: '90px' },
  { key: 'grand_total', label: 'Total', width: '130px' },
  { key: 'status', label: 'Status', width: '100px' },
  { key: 'actions', label: '', width: '90px' },
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
  router.push(`/cashier/transaction-history/${transaction.id}/detail`)
}

const goToPrint = (transaction) => {
  router.push(`/cashier/transaction-history/${transaction.id}/receipts`)
}

// --- Helpers ---
const formatDate = (dateString) => {
  if (!dateString) return '-'
  return new Date(dateString).toLocaleDateString('id-ID', {
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
</script>
