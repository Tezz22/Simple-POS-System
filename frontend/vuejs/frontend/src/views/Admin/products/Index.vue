<template>
  <!-- Main Container with responsive padding for sidebar -->
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <div class="px-4 sm:px-6 lg:px-8 py-8 max-w-7xl mx-auto space-y-6">
      <!-- Header Section -->
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div class="space-y-1">
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <Icon icon="heroicons:shopping-bag-solid" class="w-8 h-8 text-teal-600 dark:text-teal-400" />
            Master Data Produk
          </h1>
          <p class="text-sm text-gray-600 dark:text-gray-400">
            Kelola inventaris barang, kode SKU, harga, dan pantau batas minimum stok.
          </p>
        </div>
        <!-- Create Product Button -->
        <button
          @click="navigateToCreate"
          class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 dark:from-teal-500 dark:to-teal-600 dark:hover:from-teal-600 dark:hover:to-teal-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105 active:scale-95 lg:whitespace-nowrap"
          title="Tambahkan produk baru ke dalam sistem"
        >
          <Icon icon="heroicons:plus-solid" class="w-5 h-5 animate-pulse" />
          <span class="hidden sm:inline">Tambah Produk Baru</span>
          <span class="sm:hidden">Tambah</span>
        </button>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Total Produk</p>
              <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ totalProducts }}</p>
            </div>
            <Icon icon="heroicons:cube-solid" class="w-8 h-8 text-blue-500 opacity-20" />
          </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Produk Aktif</p>
              <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ activeProducts }}</p>
            </div>
            <Icon icon="heroicons:check-circle-solid" class="w-8 h-8 text-green-500 opacity-20" />
          </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Stok Kritis</p>
              <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ criticalStock }}</p>
            </div>
            <Icon icon="heroicons:exclamation-triangle-solid" class="w-8 h-8 text-red-500 opacity-20" />
          </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Nonaktif</p>
              <p class="text-2xl font-bold text-gray-600 dark:text-gray-400">{{ inactiveProducts }}</p>
            </div>
            <Icon icon="heroicons:x-circle-solid" class="w-8 h-8 text-gray-500 opacity-20" />
          </div>
        </div>
      </div>

      <!-- Search & Filter Section -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 space-y-4">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
          <div class="flex-1">
            <SearchInput
              v-model="searchQuery"
              placeholder="🔍 Cari nama, kode, atau barcode produk..."
              @input="handleSearch"
              class="w-full"
            />
          </div>
          <button
            v-if="searchQuery"
            @click="clearSearch"
            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
          >
            Bersihkan Filter
          </button>
        </div>
        <div class="flex items-center gap-2 text-sm text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/20 p-3 rounded-lg border border-amber-200 dark:border-amber-900/30">
          <Icon icon="heroicons:information-circle-solid" class="w-5 h-5 flex-shrink-0" />
          <span>Sistem secara otomatis memantau produk dengan stok di bawah batas minimum</span>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="productStore.loading" class="flex justify-center py-16">
        <div class="text-center">
          <LoadingSpinner size="lg" />
          <p class="mt-4 text-gray-600 dark:text-gray-400">Memuat data produk...</p>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="productStore.products.length === 0">
        <EmptyState
          title="Produk Tidak Ditemukan"
          description="Belum ada data barang terdaftar atau kata kunci pencarian Anda tidak cocok."
        />
      </div>

      <!-- Products Table -->
      <div v-else class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
          <BaseTable :items="productStore.products" :fields="tableFields">
            <template #cell(code)="{ item }">
              <div class="space-y-1">
                <div class="font-mono text-sm font-semibold text-gray-900 dark:text-white">{{ item.code }}</div>
                <div v-if="item.barcode" class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ item.barcode }}</div>
              </div>
            </template>

            <template #cell(name)="{ item }">
              <span class="font-medium text-gray-900 dark:text-white line-clamp-2">{{ item.name }}</span>
            </template>

            <template #cell(category)="{ item }">
              <span class="inline-block px-2.5 py-1 text-xs font-medium text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/30 rounded-full">
                {{ item.category?.name || 'Umum' }}
              </span>
            </template>

            <template #cell(selling_price)="{ item }">
              <span class="font-semibold text-gray-900 dark:text-white">
                Rp {{ item.selling_price.toLocaleString('id-ID') }}
              </span>
            </template>

            <template #cell(stock)="{ item }">
              <div class="flex items-center gap-2">
                <span 
                  class="font-semibold"
                  :class="
                    item.stock <= item.min_stock
                      ? 'text-red-600 dark:text-red-400'
                      : 'text-green-600 dark:text-green-400'
                  "
                >
                  {{ item.stock }}
                </span>
                <BadgeStatus 
                  v-if="item.stock <= item.min_stock" 
                  variant="danger"
                > 
                  ⚠️ Kritis
                </BadgeStatus>
                <span v-else class="text-xs text-gray-500 dark:text-gray-400">(Min: {{ item.min_stock }})</span>
              </div>
            </template>

            <template #cell(is_active)="{ item }">
              <BadgeStatus :variant="item.is_active ? 'success' : 'secondary'">
                {{ item.is_active ? '✓ Aktif' : '✕ Nonaktif' }}
              </BadgeStatus>
            </template>

            <template #cell(actions)="{ item }">
              <div class="flex items-center justify-end gap-2">
                <button
                  @click="$router.push(`/admin/products/${item.id}/edit`)"
                  class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/20 rounded-lg transition-colors"
                  title="Edit"
                >
                  <Icon icon="heroicons:pencil-square-solid" class="w-5 h-5" />
                </button>
                <button
                  v-if="item.is_active"
                  @click="openDeactivateConfirm(item.id)"
                  class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 rounded-lg transition-colors"
                  title="Nonaktifkan"
                >
                  <Icon icon="heroicons:eye-slash-solid" class="w-5 h-5" />
                </button>
              </div>
            </template>
          </BaseTable>
        </div>
      </div>

      <!-- Confirmation Dialog -->
      <ConfirmDialog
        v-model="isConfirmOpen"
        title="Nonaktifkan Produk?"
        message="Produk yang dinonaktifkan tidak akan ditampilkan pada aplikasi kasir POS."
        confirm-text="Ya, Nonaktifkan"
        cancel-text="Batal"
        variant="danger"
        @confirm="executeDeactivate"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { Icon } from '@iconify/vue'
import { useRouter } from 'vue-router'
import { useProductStore } from '@/stores/product'

const router = useRouter()
const productStore = useProductStore()
const searchQuery = ref('')
const isConfirmOpen = ref(false)
const selectedProductId = ref(null)

const tableFields = [
  { key: 'code', label: 'Kode / Barcode', width: '120px' },
  { key: 'name', label: 'Nama Produk', width: '200px' },
  { key: 'category', label: 'Kategori', width: '100px' },
  { key: 'selling_price', label: 'Harga Jual', width: '120px' },
  { key: 'stock', label: 'Stok', width: '100px' },
  { key: 'is_active', label: 'Status', width: '100px' },
  { key: 'actions', label: 'Aksi', align: 'right', width: '100px' },
]

// Computed statistics
const totalProducts = computed(() => productStore.products.length)
const activeProducts = computed(() => productStore.products.filter(p => p.is_active).length)
const inactiveProducts = computed(() => productStore.products.filter(p => !p.is_active).length)
const criticalStock = computed(() => productStore.products.filter(p => p.stock <= p.min_stock).length)

onMounted(() => {
  productStore.fetchAll()
})

const handleSearch = () => {
  productStore.fetchAll(searchQuery.value)
}

const clearSearch = () => {
  searchQuery.value = ''
  productStore.fetchAll()
}

const navigateToCreate = () => {
  router.push('/admin/products/create')
}

const openDeactivateConfirm = (id) => {
  selectedProductId.value = id
  isConfirmOpen.value = true
}

const executeDeactivate = async () => {
  if (selectedProductId.value) {
    await productStore.deactivateProduct(selectedProductId.value)
    selectedProductId.value = null
  }
}
</script>
