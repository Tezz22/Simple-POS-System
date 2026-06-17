<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <div class="px-4 sm:px-6 lg:px-8 py-8 max-w-7xl mx-auto space-y-6">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div class="space-y-1">
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <Icon
              icon="heroicons:document-text-solid"
              class="w-8 h-8 text-teal-600 dark:text-teal-400"
            />
            Riwayat Transaksi Penjualan
          </h1>
          <p class="text-sm text-gray-600 dark:text-gray-400">
            Pantau, cari, dan audit seluruh rekaman nota pembayaran kasir POS yang telah selesai.
          </p>
        </div>

        <button
          @click="exportReport"
          class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 dark:from-teal-500 dark:to-teal-600 dark:hover:from-teal-600 dark:hover:to-teal-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105 active:scale-95 lg:whitespace-nowrap"
          title="Ekspor dokumen laporan transaksi"
        >
          <Icon icon="heroicons:document-arrow-down-solid" class="w-5 h-5" />
          <span>Ekspor Laporan</span>
        </button>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div
          class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4"
        >
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Total Transaksi</p>
              <p class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ totalTransactions }}
              </p>
            </div>
            <Icon icon="heroicons:ticket-solid" class="w-8 h-8 text-blue-500 opacity-20" />
          </div>
        </div>
        <div
          class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4"
        >
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Transaksi Sukses</p>
              <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                {{ paidTransactions }}
              </p>
            </div>
            <Icon icon="heroicons:check-circle-solid" class="w-8 h-8 text-green-500 opacity-20" />
          </div>
        </div>
        <div
          class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4"
        >
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Transaksi Batal</p>
              <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                {{ cancelledTransactions }}
              </p>
            </div>
            <Icon icon="heroicons:x-circle-solid" class="w-8 h-8 text-red-500 opacity-20" />
          </div>
        </div>
        <div
          class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4"
        >
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Nilai Halaman Ini</p>
              <p class="text-2xl font-bold text-teal-600 dark:text-teal-400">
                Rp {{ totalRevenue.toLocaleString('id-ID') }}
              </p>
            </div>
            <Icon icon="heroicons:banknotes-solid" class="w-8 h-8 text-teal-500 opacity-20" />
          </div>
        </div>
      </div>

      <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 space-y-4"
      >
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
          <div>
            <SearchInput
              v-model="filters.search"
              placeholder="Cari nomor invoice / nota..."
              @input="fetchTransactions"
              class="w-full"
            />
          </div>
          <div>
            <BaseSelect
              v-model="filters.status"
              :options="statusOptions"
              placeholder="Semua Status Pembayaran"
              @change="fetchTransactions"
              class="w-full"
            />
          </div>
        </div>

        <div
          class="flex items-center gap-2 text-sm text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/20 p-3 rounded-lg border border-amber-200 dark:border-amber-900/30"
        >
          <Icon icon="heroicons:information-circle-solid" class="w-5 h-5 flex-shrink-0" />
          <span
            >Sistem secara otomatis mengaudit seluruh rekaman nota dari cloud server POS Anda.</span
          >
        </div>
      </div>

      <div v-if="loading" class="flex justify-center py-16">
        <div class="text-center">
          <LoadingSpinner size="lg" />
          <p class="mt-4 text-gray-600 dark:text-gray-400">Memuat data transaksi...</p>
        </div>
      </div>

      <div v-else-if="transactions.length === 0">
        <EmptyState
          title="Riwayat Transaksi Kosong"
          description="Tidak ditemukan rekaman penjualan berdasarkan kriteria penyaringan filter Anda."
        >
          <template #icon>
            <Icon
              icon="heroicons:document-text"
              class="w-14 h-14 text-gray-300 dark:text-gray-600"
            />
          </template>
        </EmptyState>
      </div>

      <div v-else class="space-y-4">
        <div
          class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden"
        >
          <div class="overflow-x-auto">
            <BaseTable :rows="transactions" :columns="tableFields" :loading="loading">
              <template #cell(invoice_number)="{ row: item }">
                <span class="font-mono font-bold text-indigo-600 dark:text-indigo-400">
                  {{ item.invoice_number }}
                </span>
              </template>

              <template #cell(created_at)="{ row: item }">
                <span class="text-gray-600 dark:text-gray-300 text-sm">
                  {{ formatDate(item.created_at) }}
                </span>
              </template>

              <template #cell(cashier)="{ row: item }">
                <span class="text-gray-900 dark:text-white font-medium">
                  {{ item.cashier?.name || 'Sistem' }}
                </span>
              </template>

              <template #cell(total_item)="{ row: item }">
                <span class="text-gray-700 dark:text-gray-300">
                  {{ item.total_item || item.items?.length || 0 }} item
                </span>
              </template>

              <template #cell(grand_total)="{ row: item }">
                <span class="font-semibold text-gray-900 dark:text-white">
                  Rp {{ Number(item.grand_total).toLocaleString('id-ID') }}
                </span>
              </template>

              <template #cell(status)="{ row: item }">
                <BadgeStatus
                  :variant="
                    item.status === 'paid'
                      ? 'success'
                      : item.status === 'cancelled'
                        ? 'danger'
                        : 'warning'
                  "
                >
                  {{
                    item.status === 'paid'
                      ? 'Paid'
                      : item.status === 'cancelled'
                        ? 'Cancelled'
                        : item.status
                  }}
                </BadgeStatus>
              </template>

              <template #cell(actions)="{ row: item }">
                <div class="flex items-center justify-end gap-2">
                  <IconButton
                    variant="secondary"
                    tooltip="Lihat Rincian Detail"
                    @click="showDetail(item)"
                  >
                    <Icon icon="heroicons:eye" class="w-4 h-4" />
                  </IconButton>
                </div>
              </template>
            </BaseTable>
          </div>
        </div>

        <div class="flex justify-end">
          <Pagination
            v-model="currentPage"
            :total-rows="totalRows"
            :per-page="perPage"
            @update:modelValue="fetchTransactions"
          />
        </div>
      </div>

      <BaseModal v-model="isModalOpen" title="Rincian Transaksi POS">
        <div v-if="selectedTransaction" class="space-y-6">
          <div
            class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-800 space-y-2"
          >
            <DetailRow label="Nomor Nota" :value="selectedTransaction.invoice_number" />
            <DetailRow
              label="Waktu Transaksi"
              :value="formatDate(selectedTransaction.created_at)"
            />
            <DetailRow label="Operator Kasir" :value="selectedTransaction.cashier?.name || '-'" />
            <DetailRow label="Status" :value="selectedTransaction.status.toUpperCase()" />
          </div>

          <div>
            <h3
              class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-3 flex items-center gap-2"
            >
              <Icon icon="heroicons:shopping-cart-solid" class="text-gray-400" />
              Daftar Barang Belanja
            </h3>
            <div class="border border-gray-100 dark:border-gray-700 rounded-lg overflow-hidden">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr
                    class="bg-gray-50 dark:bg-gray-800 text-gray-500 font-semibold border-b border-gray-100 dark:border-gray-700"
                  >
                    <th class="p-3">Nama Produk</th>
                    <th class="p-3 text-center">Qty</th>
                    <th class="p-3 text-right">Harga</th>
                    <th class="p-3 text-right">Subtotal</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                  <tr
                    v-for="subItem in selectedTransaction.items || []"
                    :key="subItem.id"
                    class="text-gray-700 dark:text-gray-300"
                  >
                    <td class="p-3 font-medium text-gray-900 dark:text-white">
                      {{ subItem.product?.name || 'Produk Terhapus' }}
                    </td>
                    <td class="p-3 text-center">{{ subItem.qty }}</td>
                    <td class="p-3 text-right">Rp {{ Number(subItem.price).toLocaleString('id-ID') }}</td>
                    <td class="p-3 text-right font-medium">
                      Rp {{ Number(subItem.price * subItem.qty).toLocaleString('id-ID') }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="border-t border-gray-100 dark:border-gray-700 pt-4 space-y-2">
            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
              <span>Metode Finansial</span>
              <span class="font-medium text-gray-800 dark:text-white">
                {{ selectedTransaction.payment_method || 'Tunai / Cash' }}
              </span>
            </div>
            <div
              class="flex justify-between text-base font-bold text-gray-900 dark:text-white border-t border-dashed border-gray-200 dark:border-gray-700 pt-2"
            >
              <span>Total Dibayar</span>
              <span class="text-indigo-600 dark:text-indigo-400">
                Rp {{ Number(selectedTransaction.grand_total).toLocaleString('id-ID') }}
              </span>
            </div>
          </div>
        </div>

        <template #footer>
          <div class="flex justify-end gap-3 w-full">
            <button
              @click="isModalOpen = false"
              class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
            >
              Tutup
            </button>
            <button
              @click="printReceipt(selectedTransaction)"
              class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 dark:bg-teal-500 dark:hover:bg-teal-600 rounded-lg shadow-sm transition-colors"
            >
              <Icon icon="heroicons:printer-solid" class="w-4 h-4" />
              Cetak Struk
            </button>
          </div>
        </template>
      </BaseModal>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { Icon } from '@iconify/vue'
import api from '@/services/api'

// Pembetulan Fatal: Mengimpor seluruh komponen UI UI dari direktori resmi proyek
import SearchInput from '@/components/ui/SearchInput.vue'
import BaseSelect from '@/components/ui/BaseSelect.vue'
import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import BaseTable from '@/components/ui/BaseTable.vue'
import BadgeStatus from '@/components/ui/BadgeStatus.vue'
import IconButton from '@/components/ui/IconButton.vue'
import Pagination from '@/components/ui/Pagination.vue'
import BaseModal from '@/components/ui/BaseModal.vue'
import DetailRow from '@/components/ui/DetailRow.vue'

const loading = ref(false)
const isModalOpen = ref(false)
const transactions = ref([])
const selectedTransaction = ref(null)

const currentPage = ref(1)
const totalRows = ref(0)
const perPage = ref(10)

const filters = ref({
  search: '',
  status: '',
  cashier_id: '',
})

const statusOptions = [
  { value: 'paid', label: 'Paid' },
  { value: 'cancelled', label: 'Cancelled' },
]

// Konfigurasi Kolom Tabel (Ditambahkan properti width agar layout rigid & rapi)
const tableFields = [
  { key: 'invoice_number', label: 'Nomor Nota / Invoice', width: '160px' },
  { key: 'created_at', label: 'Waktu Pembayaran', width: '150px' },
  { key: 'cashier', label: 'Operator Kasir', width: '140px' },
  { key: 'total_item', label: 'Jumlah Barang', width: '120px' },
  { key: 'grand_total', label: 'Total Belanja', width: '140px' },
  { key: 'status', label: 'Status', width: '100px' },
  { key: 'actions', label: 'Aksi', align: 'right', width: '100px' },
]

// Computed properties untuk memasok data ke Stats Cards secara dinamis
const totalTransactions = computed(() => totalRows.value)
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

// Fungsi ambil data dari backend
const fetchTransactions = async () => {
  loading.value = true
  try {
    const response = await api.get('/cashier/transactions', {
      params: {
        page: currentPage.value,
        search: filters.value.search,
        status: filters.value.status,
        cashier_id: filters.value.cashier_id,
      },
    })

    const resData = response.data.data
    transactions.value = resData.data || resData
    totalRows.value = resData.total || resData.length
    perPage.value = resData.per_page || 10
  } catch (error) {
    console.error('Gagal memuat riwayat transaksi:', error)
  } finally {
    loading.value = false
  }
}

// Fungsi membersihkan seluruh kriteria filter
const clearFilters = () => {
  filters.value.search = ''
  filters.value.status = ''
  filters.value.cashier_id = ''
  currentPage.value = 1
  fetchTransactions()
}

// Fungsi membuka modal detail
const showDetail = (transaction) => {
  selectedTransaction.value = transaction
  isModalOpen.value = true
}

const formatDate = (dateString) => {
  if (!dateString) return '-'
  const options = {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  }
  return new Date(dateString).toLocaleDateString('id-ID', options)
}

const printReceipt = (transaction) => {
  alert(`Fungsi print struk berjalan untuk invoice: ${transaction.invoice_number}`)
}

const exportReport = () => {
  alert('Fitur ekspor dokumen...')
}

onMounted(() => {
  fetchTransactions()
})
</script>
