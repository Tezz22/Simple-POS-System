<template>
  <div class="space-y-6 ml-6">
    <!-- Header -->
    <div>
      <h1
        class="text-xl font-black text-text-primary dark:text-white tracking-tight flex items-center gap-2"
      >
        <Icon
          icon="heroicons:squares-2x2-solid"
          class="w-6 h-6 text-primary dark:text-primary-light"
        />
        Dashboard
      </h1>
      <p class="text-xs text-text-secondary dark:text-gray-400 mt-0.5">
        Ringkasan performa toko, {{ todayLabel }}
      </p>
    </div>

    <!-- Loading -->
    <div v-if="dashboardStore.loading" class="flex justify-center py-20">
      <LoadingSpinner size="lg" label="Memuat ringkasan dashboard..." />
    </div>

    <!-- Error -->
    <ErrorState
      v-else-if="error"
      title="Gagal Memuat Dashboard"
      message="Terjadi kendala saat mengambil data ringkasan. Silakan coba lagi."
      @retry="loadDashboard"
    />

    <template v-else-if="data">
      <!-- ===== Ringkasan Penjualan ===== -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <DataCard
          label="Pendapatan (Lunas)"
          :value="formatRupiah(data.summary.revenue)"
          icon="heroicons:banknotes-solid"
          color="primary"
        />
        <DataCard
          label="Total Transaksi Lunas"
          :value="data.summary.transactionCount"
          icon="heroicons:ticket-solid"
          color="info"
        />
        <DataCard
          label="Produk Terjual"
          :value="data.summary.itemsSold"
          icon="heroicons:shopping-bag-solid"
          color="success"
        />
        <DataCard
          label="Rata-rata / Transaksi"
          :value="formatRupiah(data.summary.avgTransaction)"
          icon="heroicons:calculator-solid"
          color="warning"
        />
      </div>

      <!-- ===== Ringkasan Keseluruhan Sistem ===== -->
      <div class="grid grid-cols-3 gap-4">
        <div
          class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs p-4 text-center"
        >
          <Icon
            icon="heroicons:users-solid"
            class="w-6 h-6 text-primary dark:text-primary-light mx-auto mb-1.5"
          />
          <p class="text-lg font-black text-text-primary dark:text-white">
            {{ data.counts.users }}
          </p>
          <p
            class="text-[10px] font-bold uppercase tracking-wide text-text-secondary dark:text-gray-400"
          >
            Total User
          </p>
        </div>
        <div
          class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs p-4 text-center"
        >
          <Icon
            icon="heroicons:cube-solid"
            class="w-6 h-6 text-primary dark:text-primary-light mx-auto mb-1.5"
          />
          <p class="text-lg font-black text-text-primary dark:text-white">
            {{ data.counts.products }}
          </p>
          <p
            class="text-[10px] font-bold uppercase tracking-wide text-text-secondary dark:text-gray-400"
          >
            Total Produk
          </p>
        </div>
        <div
          class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs p-4 text-center"
        >
          <Icon
            icon="heroicons:document-text-solid"
            class="w-6 h-6 text-primary dark:text-primary-light mx-auto mb-1.5"
          />
          <p class="text-lg font-black text-text-primary dark:text-white">
            {{ data.counts.transactions }}
          </p>
          <p
            class="text-[10px] font-bold uppercase tracking-wide text-text-secondary dark:text-gray-400"
          >
            Total Transaksi
          </p>
        </div>
      </div>

      <!-- ===== Grafik Tren + Status Transaksi ===== -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Grafik tren penjualan -->
        <SectionCard title="Tren Penjualan" class="lg:col-span-2">
          <template #header-action>
            <span class="text-[11px] font-semibold text-text-secondary dark:text-gray-400">
              7 Hari Terakhir
            </span>
          </template>

          <div
            v-if="!data.salesTrend?.length || allTrendValuesZero"
            class="py-12 text-center text-xs text-text-secondary dark:text-gray-400"
          >
            Belum ada transaksi lunas dalam 7 hari terakhir.
          </div>
          <SalesTrendChart v-else :data="data.salesTrend" />
        </SectionCard>

        <!-- Distribusi status transaksi -->
        <SectionCard title="Status Transaksi">
          <div
            v-if="!data.statusBreakdown?.length"
            class="py-8 text-center text-xs text-text-secondary dark:text-gray-400"
          >
            Belum ada data transaksi.
          </div>
          <div v-else class="space-y-4">
            <div v-for="item in data.statusBreakdown" :key="item.status" class="space-y-1.5">
              <div class="flex items-center justify-between text-xs">
                <span
                  class="font-semibold text-text-primary dark:text-white flex items-center gap-1.5"
                >
                  <span :class="['w-2 h-2 rounded-full', statusDotClass(item.status)]"></span>
                  {{ item.label }}
                </span>
                <span class="text-text-secondary dark:text-gray-400 font-medium">
                  {{ item.count }} ({{ item.percent }}%)
                </span>
              </div>
              <div class="w-full h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                <div
                  :class="[
                    'h-full rounded-full transition-all duration-500',
                    statusBarClass(item.status),
                  ]"
                  :style="{ width: `${item.percent}%` }"
                ></div>
              </div>
            </div>
          </div>
        </SectionCard>
      </div>

      <!-- ===== Produk & Transaksi Terbaru ===== -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Produk stok menipis -->
        <SectionCard title="Stok Menipis">
          <template #header-action>
            <RouterLink
              to="/admin/products"
              class="text-[11px] font-bold text-primary dark:text-primary-light hover:underline"
            >
              Kelola Produk
            </RouterLink>
          </template>

          <EmptyState
            v-if="!data.lowStockProducts?.length"
            icon="📦"
            title="Stok Aman"
            description="Tidak ada produk dengan stok menipis saat ini."
          />

          <div v-else class="space-y-3">
            <div
              v-for="product in data.lowStockProducts"
              :key="product.id"
              class="flex items-center justify-between gap-3 py-3 border-b border-gray-50 dark:border-gray-800 last:border-0 last:pb-0 first:pt-0"
            >
              <p class="text-xs font-bold text-text-primary dark:text-white truncate min-w-0">
                {{ product.name }}
              </p>
              <StockIndicator :stock="product.stock" :min-stock="product.min_stock ?? 5" />
            </div>
          </div>
        </SectionCard>

        <!-- Transaksi terbaru -->
        <SectionCard title="Transaksi Terbaru" class="lg:col-span-2">
          <template #header-action>
            <RouterLink
              to="/admin/transactions"
              class="text-[11px] font-bold text-primary dark:text-primary-light hover:underline"
            >
              Lihat Semua
            </RouterLink>
          </template>

          <EmptyState
            v-if="!data.recentTransactions?.length"
            icon="🧾"
            title="Belum Ada Transaksi"
            description="Transaksi terbaru akan muncul di sini."
          />

          <div v-else class="divide-y divide-gray-50 dark:divide-gray-800">
            <div
              v-for="trx in data.recentTransactions"
              :key="trx.id"
              class="flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0"
            >
              <div class="min-w-0">
                <p class="text-xs font-mono font-bold text-primary dark:text-primary-light">
                  {{ trx.invoice_number }}
                </p>
                <p class="text-[10px] text-text-secondary dark:text-gray-500 mt-0.5">
                  {{ trx.cashier?.name || 'Sistem' }} · {{ formatDate(trx.transaction_date) }}
                </p>
              </div>
              <div class="flex items-center gap-2 shrink-0">
                <span class="text-xs font-black text-text-primary dark:text-white">
                  {{ formatRupiah(trx.grand_total) }}
                </span>
                <BadgeStatus :status="trx.status" size="sm" />
              </div>
            </div>
          </div>
        </SectionCard>
      </div>
    </template>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { Icon } from '@iconify/vue'
import { useDashboardStore } from '@/stores/dashboard'

import DataCard from '@/components/ui/DataCard.vue'
import SectionCard from '@/components/ui/SectionCard.vue'
import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import ErrorState from '@/components/ui/ErrorState.vue'
import BadgeStatus from '@/components/ui/BadgeStatus.vue'
import StockIndicator from '@/components/shared/StockIndicator.vue'
import SalesTrendChart from '@/components/shared/SalesTrendChart.vue'

const dashboardStore = useDashboardStore()

const error = computed(() => dashboardStore.error)
const data = computed(() => dashboardStore.data)

const allTrendValuesZero = computed(
  () => data.value?.salesTrend?.every((d) => Number(d.value) === 0) ?? true,
)

const todayLabel = computed(() =>
  new Date().toLocaleDateString('id-ID', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  }),
)

const statusDotClass = (status) => (status === 'paid' ? 'bg-success' : 'bg-danger')
const statusBarClass = (status) => (status === 'paid' ? 'bg-success' : 'bg-danger')

const loadDashboard = () => {
  dashboardStore.fetchDashboard()
}

const formatDate = (dateString) => {
  if (!dateString) return '-'
  return new Date(dateString).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'short',
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

onMounted(loadDashboard)
</script>
