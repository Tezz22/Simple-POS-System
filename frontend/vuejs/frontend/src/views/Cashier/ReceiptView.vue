<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex flex-col items-center justify-center p-4">
 
    <!-- Loading -->
    <div v-if="loading" class="print:hidden flex flex-col items-center gap-3">
      <LoadingSpinner size="lg" label="Menyiapkan struk transaksi..." />
    </div>
 
    <!-- Error -->
    <ErrorState
      v-else-if="error"
      class="print:hidden"
      title="Struk Tidak Ditemukan"
      message="Gagal memuat data struk. Transaksi mungkin tidak ada atau sudah dihapus."
      @retry="fetchForPrint"
    />
 
    <!-- Content -->
    <template v-else-if="transaction">
 
      <!-- Toolbar (hidden saat print) -->
      <div class="print:hidden w-full max-w-sm mb-6 flex items-center justify-between gap-3">
        <BaseButton variant="outline" size="sm" @click="goBack">
          <Icon icon="heroicons:arrow-left-solid" class="w-4 h-4 mr-1.5" />
          Kembali
        </BaseButton>
        <BaseButton variant="primary" size="sm" @click="executePrint">
          <Icon icon="heroicons:printer-solid" class="w-4 h-4 mr-1.5" />
          Cetak Ulang
        </BaseButton>
      </div>
 
      <!-- Struk -->
      <div class="receipt-container">
        <ReceiptCard
          :transaction="formattedTransaction"
          :items="formattedItems"
          :cashier="transaction.cashier || { name: 'Sistem' }"
        />
      </div>
 
    </template>
 
  </div>
</template>
 
<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { Icon } from '@iconify/vue'
import api from '@/services/api'
 
import ReceiptCard    from '@/components/shared/ReceiptCard.vue'
import BaseButton     from '@/components/ui/BaseButton.vue'
import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'
import ErrorState     from '@/components/ui/ErrorState.vue'
 
const route  = useRoute()
const router = useRouter()
 
const loading     = ref(true)
const error       = ref(false)
const transaction = ref(null)
 
// ReceiptCard butuh prop `transaction` berisi info nota (bukan daftar barang)
const formattedTransaction = computed(() => {
  if (!transaction.value) return {}
  return {
    id:            transaction.value.invoice_number,
    invoice_number: transaction.value.invoice_number,
    total:         transaction.value.grand_total,
    grand_total:   transaction.value.grand_total,
    created_at:    transaction.value.transaction_date || transaction.value.created_at,
    payment_method: transaction.value.payment_method,
    paid_amount:   transaction.value.paid_amount,
    change_amount: transaction.value.change_amount,
  }
})
 
// ReceiptCard butuh setiap item berbentuk { id, name, price, qty }
// sedangkan backend mengembalikan { product: { name }, price, qty }
const formattedItems = computed(() => {
  if (!transaction.value?.items) return []
  return transaction.value.items.map((item) => ({
    id:    item.id,
    name:  item.product?.name || 'Produk Terhapus',
    price: Number(item.price),
    qty:   item.qty,
  }))
})
 
const fetchForPrint = async () => {
  loading.value = true
  error.value = false
  try {
    const response = await api.get(`/cashier/transactions/${route.params.id}`)
    transaction.value = response.data.data
 
    // Tunggu DOM (ReceiptCard) selesai render sebelum membuka dialog print
    setTimeout(() => {
      executePrint()
    }, 500)
 
  } catch (err) {
    console.error('Gagal memuat struk:', err)
    error.value = true
  } finally {
    loading.value = false
  }
}
 
const executePrint = () => {
  window.print()
}
 
const goBack = () => {
  router.push(`/cashier/transaction-history`)
}
 
onMounted(fetchForPrint)
</script>
 
<style scoped>
/* Reset spesifik untuk memastikan hasil cetakan thermal rapi */
@media print {
  body, html {
    margin: 0;
    padding: 0;
    background-color: white !important;
  }
 
  /* Hilangkan semua elemen UI selain receipt saat di-print */
  .print\:hidden {
    display: none !important;
  }
 
  .receipt-container {
    width: 100%;
    max-width: 80mm; /* Standar kertas thermal kasir 80mm */
    margin: 0 auto;
  }
}
</style>