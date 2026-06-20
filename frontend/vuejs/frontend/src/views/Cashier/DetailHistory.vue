<template>
  <div class="p-6">
    <LoadingOverlay :show="loading" message="Memuat rincian transaksi..." :blur="true" />

    <!-- Breadcrumb & Back -->
    <div class="flex items-center gap-2 text-xs text-text-secondary dark:text-gray-400">
      <button
        class="flex items-center gap-1.5 hover:text-primary dark:hover:text-primary-light transition-colors cursor-pointer"
        @click="$router.push('/cashier/transaction-history')"
      >
        <Icon icon="heroicons:arrow-left-solid" class="w-3.5 h-3.5" />
        Kembali ke Riwayat
      </button>
      <span class="text-gray-300 dark:text-gray-600">/</span>
      <span class="text-text-primary dark:text-white font-medium">
        {{ transaction?.invoice_number || 'Detail' }}
      </span>
    </div>

    <template v-if="transaction && !loading">
      <!-- Page Title + Actions -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 class="text-xl font-black text-text-primary dark:text-white tracking-tight">
            Detail Transaksi
            <span class="text-primary dark:text-primary-light font-mono">
              #{{ transaction.invoice_number }}
            </span>
          </h1>
          <p class="text-xs text-text-secondary dark:text-gray-400 mt-0.5">
            {{ formatDate(transaction.transaction_date) }}
          </p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
          <BaseButton variant="outline" size="sm" @click="$router.push('/cashier/transactions')">
            Kembali
          </BaseButton>
          <BaseButton variant="primary" size="sm" @click="goToPrint">
            <Icon icon="heroicons:printer-solid" class="w-4 h-4 mr-1.5" />
            Cetak Struk
          </BaseButton>
        </div>
      </div>

      <!-- Content Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Info ringkas -->
        <div class="space-y-4 lg:col-span-1">
          <SectionCard title="Informasi Transaksi">
            <DetailRow label="No. Invoice" :value="transaction.invoice_number" />
            <DetailRow label="Tanggal" :value="formatDate(transaction.transaction_date)" />
            <DetailRow label="Kasir" :value="transaction.cashier?.name || 'Sistem'" />
            <DetailRow label="Status">
              <BadgeStatus :status="transaction.status" size="sm" />
            </DetailRow>
          </SectionCard>

          <SectionCard title="Rincian Pembayaran">
            <DetailRow
              label="Metode Bayar"
              :value="(transaction.payment_method || 'CASH').toUpperCase()"
            />
            <DetailRow label="Total Belanja" :value="formatRupiah(transaction.grand_total)" />
            <DetailRow
              label="Uang Diterima"
              :value="formatRupiah(transaction.paid_amount ?? transaction.grand_total)"
            />
            <DetailRow label="Kembalian" :value="formatRupiah(transaction.change_amount ?? 0)" />
          </SectionCard>
        </div>

        <!-- Kolom Kanan: Daftar barang -->
        <div class="lg:col-span-2">
          <SectionCard title="Daftar Barang Belanja">
            <!-- Tabel barang -->
            <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-700">
              <table class="w-full text-left text-sm border-collapse">
                <thead
                  class="bg-gray-50 dark:bg-gray-800 text-xs font-bold uppercase tracking-wider text-text-secondary dark:text-gray-400 border-b border-gray-200 dark:border-gray-700"
                >
                  <tr>
                    <th class="px-4 py-3">Produk</th>
                    <th class="px-4 py-3 text-center w-20">Qty</th>
                    <th class="px-4 py-3 text-right w-36">Harga Satuan</th>
                    <th class="px-4 py-3 text-right w-36">Subtotal</th>
                  </tr>
                </thead>
                <tbody
                  class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-700/60"
                >
                  <tr
                    v-for="item in transaction.items"
                    :key="item.id"
                    class="hover:bg-gray-50/60 dark:hover:bg-gray-800/40 transition-colors"
                  >
                    <td class="px-4 py-3">
                      <p class="font-semibold text-text-primary dark:text-white text-xs">
                        {{ item.product?.name || 'Produk Terhapus' }}
                      </p>
                      <p class="text-[10px] text-text-secondary dark:text-gray-500 mt-0.5">
                        {{ item.product?.code || '-' }}
                      </p>
                    </td>
                    <td
                      class="px-4 py-3 text-center text-xs font-bold text-text-primary dark:text-white"
                    >
                      {{ item.qty }}
                    </td>
                    <td class="px-4 py-3 text-right text-xs text-text-secondary dark:text-gray-400">
                      {{ formatRupiah(item.price) }}
                    </td>
                    <td
                      class="px-4 py-3 text-right text-xs font-bold text-text-primary dark:text-white"
                    >
                      {{ formatRupiah(item.subtotal ?? item.price * item.qty) }}
                    </td>
                  </tr>
                </tbody>
                <tfoot
                  class="bg-gray-50 dark:bg-gray-800/60 border-t border-gray-200 dark:border-gray-700"
                >
                  <tr>
                    <td
                      colspan="3"
                      class="px-4 py-3 text-right text-xs font-black text-text-primary dark:text-white uppercase tracking-wide"
                    >
                      Grand Total
                    </td>
                    <td
                      class="px-4 py-3 text-right text-sm font-black text-primary dark:text-primary-light tracking-tight"
                    >
                      {{ formatRupiah(transaction.grand_total) }}
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </SectionCard>
        </div>
      </div>
    </template>

    <!-- Empty / Not Found -->
    <EmptyState
      v-if="!transaction && !loading"
      title="Transaksi Tidak Ditemukan"
      description="Data transaksi tidak ada atau sudah dihapus dari sistem."
    >
      <template #icon>
        <Icon
          icon="heroicons:document-magnifying-glass"
          class="w-12 h-12 text-gray-300 dark:text-gray-600"
        />
      </template>
      <template #action>
        <BaseButton variant="primary" @click="$router.push('/cashier/transaction-history')">
          Kembali ke Riwayat
        </BaseButton>
      </template>
    </EmptyState>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { Icon } from '@iconify/vue'
import api from '@/services/api'

import SectionCard from '@/components/ui/SectionCard.vue'
import DetailRow from '@/components/ui/DetailRow.vue'
import BadgeStatus from '@/components/ui/BadgeStatus.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import LoadingOverlay from '@/components/ui/LoadingOverlay.vue'
import EmptyState from '@/components/ui/EmptyState.vue'

const route = useRoute()
const router = useRouter()

const loading = ref(false)
const transaction = ref(null)

const fetchTransactionDetail = async () => {
  loading.value = true

  try {
    const response = await api.get(`/cashier/transactions/${route.params.id}`)

    transaction.value = response.data.data

    // Debug
    transaction.value.items.forEach((item) => {
      console.log(item)
    })
  } catch (err) {
    console.error(err)
    transaction.value = null
  } finally {
    loading.value = false
  }
}

const formatDate = (dateString) => {
  if (!dateString) return '-'
  return new Date(dateString).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
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

const goToPrint = () => {
  router.push(`/cashier/transaction-history/${route.params.id}/receipt`)
}

onMounted(fetchTransactionDetail)
</script>
