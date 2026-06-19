<template>
  <div class="p-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
      <div>
        <h1 class="text-xl font-black text-text-primary dark:text-white tracking-tight flex items-center gap-2">
          <Icon icon="heroicons:squares-2x2-solid" class="w-6 h-6 text-primary dark:text-primary-light" />
          Dashboard
        </h1>
        <p class="text-xs text-text-secondary dark:text-gray-400 mt-0.5">
          Ringkasan performa toko hari ini, {{ todayLabel }}
        </p>
      </div>

      <BaseSelect
        v-model="periode"
        :options="periodeOptions"
        class="w-full sm:w-48"
        @change="fetchDashboard"
      />
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex justify-center py-20">
      <LoadingSpinner size="lg" label="Memuat ringkasan dashboard..." />
    </div>

    <!-- Error -->
    <ErrorState
      v-else-if="error"
      title="Gagal Memuat Dashboard"
      message="Terjadi kendala saat mengambil data ringkasan. Silakan coba lagi."
      @retry="fetchDashboard"
    />

    <template v-else>

      <!-- ===== Ringkasan Penjualan ===== -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <DataCard
          label="Pendapatan"
          :value="formatRupiah(summary.revenue)"
          icon="heroicons:banknotes-solid"
          color="primary"
          :trend="summary.revenueTrend"
        />
        <DataCard
          label="Transaksi"
          :value="summary.transactionCount"
          icon="heroicons:ticket-solid"
          color="info"
          :trend="summary.transactionTrend"
        />
        <DataCard
          label="Produk Terjual"
          :value="summary.itemsSold"
          icon="heroicons:shopping-bag-solid"
          color="success"
        />
        <DataCard
          label="Rata-rata / Transaksi"
          :value="formatRupiah(summary.avgTransaction)"
          icon="heroicons:calculator-solid"
          color="warning"
        />
      </div>

      <!-- ===== Grafik Tren + Status Transaksi ===== -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Grafik tren penjualan -->
        <SectionCard title="Tren Penjualan" class="lg:col-span-2">
          <template #header-action>
            <span class="text-[11px] font-semibold text-text-secondary dark:text-gray-400">
              {{ periodeOptions.find(p => p.value === periode)?.label }}
            </span>
          </template>

          <div v-if="salesTrend.length === 0" class="py-12 text-center text-xs text-text-secondary dark:text-gray-400">
            Belum ada data penjualan pada periode ini.
          </div>
          <SalesTrendChart v-else :data="salesTrend" />
        </SectionCard>

        <!-- Distribusi status transaksi -->
        <SectionCard title="Status Transaksi">
          <div class="space-y-4">
            <div
              v-for="item in statusBreakdown"
              :key="item.status"
              class="space-y-1.5"
            >
              <div class="flex items-center justify-between text-xs">
                <span class="font-semibold text-text-primary dark:text-white flex items-center gap-1.5">
                  <span :class="['w-2 h-2 rounded-full', item.dotClass]"></span>
                  {{ item.label }}
                </span>
                <span class="text-text-secondary dark:text-gray-400 font-medium">
                  {{ item.count }} ({{ item.percent }}%)
                </span>
              </div>
              <div class="w-full h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                <div
                  :class="['h-full rounded-full transition-all duration-500', item.barClass]"
                  :style="{ width: `${item.percent}%` }"
                ></div>
              </div>
            </div>
          </div>
        </SectionCard>
      </div>

      <!-- ===== Produk & User ===== -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Produk stok menipis -->
        <SectionCard title="Stok Menipis" class="lg:col-span-2">
          <template #header-action>
            <RouterLink
              to="/admin/products"
              class="text-[11px] font-bold text-primary dark:text-primary-light hover:underline"
            >
              Kelola Produk
            </RouterLink>
          </template>

          <EmptyState
            v-if="lowStockProducts.length === 0"
            icon="📦"
            title="Stok Aman"
            description="Tidak ada produk dengan stok menipis saat ini."
          />

          <div v-else class="space-y-3">
            <div
              v-for="product in lowStockProducts"
              :key="product.id"
              class="flex items-center justify-between gap-3 py-2 border-b border-gray-50 dark:border-gray-800 last:border-0"
            >
              <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-base shrink-0">
                  {{ product.image_url ? '' : '🍽️' }}
                  <img
                    v-if="product.image_url"
                    :src="product.image_url"
                    :alt="product.name"
                    class="w-full h-full object-cover rounded-lg"
                  />
                </div>
                <div class="min-w-0">
                  <p class="text-xs font-bold text-text-primary dark:text-white truncate">
                    {{ product.name }}
                  </p>
                  <p class="text-[10px] text-text-secondary dark:text-gray-500">
                    {{ product.category?.name || 'Tanpa kategori' }}
                  </p>
                </div>
              </div>
              <StockIndicator :stock="product.stock" :min-stock="product.min_stock ?? 5" />
            </div>
          </div>
        </SectionCard>

        <!-- Ringkasan User -->
        <SectionCard title="Pengguna Aktif">
          <div class="grid grid-cols-2 gap-3 mb-5">
            <div class="bg-purple-50 dark:bg-purple-950/20 rounded-xl p-3 text-center">
              <p class="text-lg font-black text-purple-600 dark:text-purple-400">{{ summary.adminCount }}</p>
              <p class="text-[10px] font-bold uppercase tracking-wide text-purple-500 dark:text-purple-400/80">Admin</p>
            </div>
            <div class="bg-blue-50 dark:bg-blue-950/20 rounded-xl p-3 text-center">
              <p class="text-lg font-black text-blue-600 dark:text-blue-400">{{ summary.cashierCount }}</p>
              <p class="text-[10px] font-bold uppercase tracking-wide text-blue-500 dark:text-blue-400/80">Kasir</p>
            </div>
          </div>

          <p class="text-[11px] font-bold uppercase tracking-wider text-text-secondary dark:text-gray-400 mb-3">
            Kasir Paling Aktif
          </p>

          <EmptyState
            v-if="topCashiers.length === 0"
            icon="👤"
            title="Belum Ada Aktivitas"
            description="Belum ada transaksi yang tercatat oleh kasir."
          />

          <div v-else class="space-y-3">
            <div
              v-for="(cashier, idx) in topCashiers"
              :key="cashier.id"
              class="flex items-center gap-3"
            >
              <span class="text-[10px] font-black text-text-secondary dark:text-gray-500 w-4">
                {{ idx + 1 }}
              </span>
              <div class="w-7 h-7 rounded-full bg-teal-100 dark:bg-teal-900/40 flex items-center justify-center text-[11px] font-black text-primary dark:text-primary-light shrink-0">
                {{ cashier.name.charAt(0).toUpperCase() }}
              </div>
              <div class="min-w-0 flex-1">
                <p class="text-xs font-bold text-text-primary dark:text-white truncate">{{ cashier.name }}</p>
              </div>
              <span class="text-[11px] font-bold text-text-secondary dark:text-gray-400 shrink-0">
                {{ cashier.transaction_count }}x
              </span>
            </div>
          </div>
        </SectionCard>
      </div>

      <!-- ===== Produk Terlaris ===== -->
      <SectionCard title="Produk Terlaris">
        <template #header-action>
          <RouterLink
            to="/admin/transactions"
            class="text-[11px] font-bold text-primary dark:text-primary-light hover:underline"
          >
            Lihat Semua Transaksi
          </RouterLink>
        </template>

        <EmptyState
          v-if="bestSellers.length === 0"
          icon="🏆"
          title="Belum Ada Data"
          description="Belum ada produk terjual pada periode ini."
        />

        <BaseTable
          v-else
          :columns="bestSellerColumns"
          :rows="bestSellers"
        >
          <template #cell(rank)="{ index }">
            <span class="text-xs font-black text-text-secondary dark:text-gray-500">
              #{{ index + 1 }}
            </span>
          </template>

          <template #cell(name)="{ row }">
            <span class="text-xs font-bold text-text-primary dark:text-white">{{ row.name }}</span>
          </template>

          <template #cell(qty_sold)="{ row }">
            <span class="text-xs font-semibold text-text-secondary dark:text-gray-300">
              {{ row.qty_sold }} terjual
            </span>
          </template>

          <template #cell(revenue)="{ row }">
            <span class="text-xs font-black text-text-primary dark:text-white">
              {{ formatRupiah(row.revenue) }}
            </span>
          </template>
        </BaseTable>
      </SectionCard>

    </template>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { Icon } from '@iconify/vue'
import api from '@/services/api'

import DataCard        from '@/components/ui/DataCard.vue'
import SectionCard     from '@/components/ui/SectionCard.vue'
import BaseSelect      from '@/components/ui/BaseSelect.vue'
import BaseTable       from '@/components/ui/BaseTable.vue'
import LoadingSpinner  from '@/components/ui/LoadingSpinner.vue'
import EmptyState      from '@/components/ui/EmptyState.vue'
import ErrorState      from '@/components/ui/ErrorState.vue'
import StockIndicator  from '@/components/shared/StockIndicator.vue'
import SalesTrendChart from '@/components/shared/SalesTrendChart.vue'

const loading = ref(false)
const error   = ref(false)
const periode = ref('week')

const periodeOptions = [
  { value: 'today', label: 'Hari Ini' },
  { value: 'week',  label: '7 Hari Terakhir' },
  { value: 'month', label: '30 Hari Terakhir' },
]

// Struktur default — disesuaikan begitu response asli /admin/dashboard diketahui
const summary = ref({
  revenue: 0,
  revenueTrend: null,        // { value: 12, direction: 'up' }
  transactionCount: 0,
  transactionTrend: null,
  itemsSold: 0,
  avgTransaction: 0,
  adminCount: 0,
  cashierCount: 0,
})

const salesTrend      = ref([]) // [{ label: 'Sen', value: 120000 }, ...]
const lowStockProducts = ref([])
const topCashiers      = ref([])
const bestSellers      = ref([])
const statusCounts     = ref({ paid: 0, cancelled: 0 })

const bestSellerColumns = [
  { key: 'rank',     label: '#',        width: '50px' },
  { key: 'name',     label: 'Produk' },
  { key: 'qty_sold', label: 'Terjual',  width: '120px' },
  { key: 'revenue',  label: 'Pendapatan', width: '150px' },
]

const statusBreakdown = computed(() => {
  const total = statusCounts.value.paid + statusCounts.value.cancelled
  const pct = (n) => (total === 0 ? 0 : Math.round((n / total) * 100))
  return [
    {
      status: 'paid',
      label: 'Lunas',
      count: statusCounts.value.paid,
      percent: pct(statusCounts.value.paid),
      dotClass: 'bg-success',
      barClass: 'bg-success',
    },
    {
      status: 'cancelled',
      label: 'Dibatalkan',
      count: statusCounts.value.cancelled,
      percent: pct(statusCounts.value.cancelled),
      dotClass: 'bg-danger',
      barClass: 'bg-danger',
    },
  ]
})

const todayLabel = computed(() =>
  new Date().toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })
)

const fetchDashboard = async () => {
  loading.value = true
  error.value = false
  try {
    const response = await api.get('/admin/dashboard', {
      params: { period: periode.value },
    })
    const data = response.data.data

    // --- PENYESUAIAN DIBUTUHKAN ---
    // Mapping berikut memakai asumsi nama field yang umum dipakai.
    // Sesuaikan key di sisi kanan (data.xxx) dengan response asli backend Anda.
    summary.value = {
      revenue:           data.revenue ?? data.total_revenue ?? 0,
      revenueTrend:      data.revenue_trend ?? null,
      transactionCount:  data.transaction_count ?? data.total_transactions ?? 0,
      transactionTrend:  data.transaction_trend ?? null,
      itemsSold:         data.items_sold ?? 0,
      avgTransaction:    data.avg_transaction ?? (
        data.transaction_count > 0 ? Math.round((data.revenue ?? 0) / data.transaction_count) : 0
      ),
      adminCount:        data.admin_count ?? 0,
      cashierCount:      data.cashier_count ?? 0,
    }

    salesTrend.value       = data.sales_trend ?? []
    lowStockProducts.value = data.low_stock_products ?? []
    topCashiers.value      = data.top_cashiers ?? []
    bestSellers.value      = data.best_sellers ?? []
    statusCounts.value     = {
      paid:      data.status_breakdown?.paid ?? 0,
      cancelled: data.status_breakdown?.cancelled ?? 0,
    }

  } catch (err) {
    console.error('Gagal memuat dashboard:', err)
    error.value = true
  } finally {
    loading.value = false
  }
}

const formatRupiah = (angka) =>
  new Intl.NumberFormat('id-ID', {
    style: 'currency', currency: 'IDR', minimumFractionDigits: 0,
  }).format(angka || 0)

onMounted(fetchDashboard)
</script>
