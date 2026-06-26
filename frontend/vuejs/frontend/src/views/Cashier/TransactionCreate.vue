<template>
  <div class="p-6">
    <!-- Header -->
    <div>
      <h1
        class="text-xl font-black text-text-primary dark:text-white tracking-tight flex items-center gap-2"
      >
        <Icon
          icon="heroicons:shopping-cart-solid"
          class="w-6 h-6 text-primary dark:text-primary-light"
        />
        Kasir / Point of Sale
      </h1>
      <p class="text-xs text-text-secondary dark:text-gray-400 mt-0.5">
        Pilih produk untuk membuat transaksi penjualan baru
      </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
      <!-- ============ KOLOM PRODUK ============ -->
      <div class="lg:col-span-2 space-y-4">
        <!-- Search & Filter -->
        <SectionCard>
          <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
              <SearchInput v-model="search" placeholder="Cari nama produk..." />
            </div>
            <div class="w-full sm:w-52">
              <BaseSelect
                v-model="categoryFilter"
                :options="categoryOptions"
                placeholder="Semua Kategori"
              />
            </div>
          </div>
        </SectionCard>

        <!-- Loading produk -->
        <div v-if="productStore.loading" class="flex justify-center py-16">
          <LoadingSpinner size="lg" label="Memuat daftar produk..." />
        </div>

        <!-- Empty state -->
        <EmptyState
          v-else-if="filteredProducts.length === 0"
          icon="🍽️"
          title="Produk Tidak Ditemukan"
          description="Coba ubah kata kunci pencarian atau filter kategori."
        >
          <template #action>
            <BaseButton v-if="search || categoryFilter" variant="outline" @click="resetFilter">
              Hapus Filter
            </BaseButton>
          </template>
        </EmptyState>

        <!-- Grid produk -->
        <div v-else class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-3 gap-4">
          <ProductCard
            v-for="product in filteredProducts"
            :key="product.id"
            :product="product"
            @addToCart="addToCart"
          />
        </div>
      </div>

      <!-- ============ KOLOM KERANJANG (sticky) ============ -->
      <div class="space-y-4 lg:sticky lg:top-6">
        <SectionCard>
          <template #header>
            <div class="flex items-center justify-between w-full">
              <h3
                class="text-xs font-black uppercase tracking-wider text-text-secondary dark:text-gray-400 flex items-center gap-1.5"
              >
                <Icon icon="heroicons:shopping-bag-solid" class="w-4 h-4" />
                Keranjang
                <span
                  v-if="cartCount > 0"
                  class="bg-primary text-white dark:bg-primary-light dark:text-gray-900 text-[10px] font-black rounded-full px-1.5 py-0.5 min-w-[18px] text-center"
                >
                  {{ cartCount }}
                </span>
              </h3>
              <button
                v-if="cart.length > 0"
                type="button"
                class="text-[11px] font-bold text-danger hover:underline cursor-pointer"
                @click="confirmClearCart = true"
              >
                Kosongkan
              </button>
            </div>
          </template>

          <!-- Keranjang kosong -->
          <div v-if="cart.length === 0" class="py-8 text-center">
            <Icon
              icon="heroicons:shopping-cart"
              class="w-10 h-10 text-gray-300 dark:text-gray-600 mx-auto mb-2"
            />
            <p class="text-xs text-text-secondary dark:text-gray-400">
              Belum ada produk dipilih.<br />Klik tombol + pada produk untuk menambah.
            </p>
          </div>

          <!-- Daftar item -->
          <div v-else class="max-h-[320px] overflow-y-auto -mr-2 pr-2">
            <CartItem
              v-for="item in cart"
              :key="item.id"
              :item="item"
              @increaseQty="increaseQty"
              @decreaseQty="decreaseQty"
              @removeItem="removeItem"
            />
          </div>

          <CartSummary v-if="cart.length > 0" :subtotal="subTotal" />
        </SectionCard>

        <PaymentForm v-if="cart.length > 0" :grand-total="grandTotal" @pay="handlePayment" />
      </div>
    </div>

    <!-- Modal konfirmasi kosongkan keranjang -->
    <ConfirmDialog
      :show="confirmClearCart"
      title="Kosongkan Keranjang?"
      message="Semua produk yang sudah dipilih akan dihapus dari keranjang."
      variant="danger"
      confirm-label="Ya, Kosongkan"
      @confirm="clearCart"
      @cancel="confirmClearCart = false"
    />

    <!-- Modal sukses transaksi -->
    <BaseModal :show="successModal.show" title="Transaksi Berhasil" @close="closeSuccessModal">
      <div class="flex flex-col items-center text-center gap-3 py-2">
        <div
          class="w-14 h-14 rounded-full bg-green-50 dark:bg-green-950/30 flex items-center justify-center"
        >
          <Icon icon="heroicons:check-circle-solid" class="w-9 h-9 text-success" />
        </div>
        <div>
          <p class="text-sm font-bold text-text-primary dark:text-white">
            Pembayaran tercatat untuk
          </p>
          <p class="text-base font-black text-primary dark:text-primary-light font-mono">
            {{ successModal.invoiceNumber }}
          </p>
        </div>
        <div class="w-full bg-gray-50 dark:bg-gray-900/50 rounded-xl p-3 space-y-1.5 text-xs">
          <div class="flex justify-between">
            <span class="text-text-secondary dark:text-gray-400">Total Tagihan</span>
            <span class="font-bold text-text-primary dark:text-white">{{
              formatRupiah(successModal.total)
            }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-text-secondary dark:text-gray-400">Uang Diterima</span>
            <span class="font-bold text-text-primary dark:text-white">{{
              formatRupiah(successModal.cash)
            }}</span>
          </div>
          <div
            class="flex justify-between border-t border-dashed border-gray-200 dark:border-gray-700 pt-1.5"
          >
            <span class="text-text-secondary dark:text-gray-400">Kembalian</span>
            <span class="font-black text-success">{{ formatRupiah(successModal.change) }}</span>
          </div>
        </div>
      </div>

      <template #footer>
        <div class="flex gap-3 w-full">
          <BaseButton variant="outline" class="flex-1" @click="closeSuccessModal">
            Transaksi Baru
          </BaseButton>
          <BaseButton variant="primary" class="flex-1" @click="goToReceipt">
            <Icon icon="heroicons:printer-solid" class="w-4 h-4 mr-1.5" />
            Cetak Struk
          </BaseButton>
        </div>
      </template>
    </BaseModal>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { Icon } from '@iconify/vue'
import { useCashierProductStore } from '@/stores/cashierProduct'
import { useTransactionStore } from '@/stores/transaction'
import { useToastStore } from '@/stores/toast'

import ProductCard from '@/components/shared/ProductCard.vue'
import CartItem from '@/components/shared/CartItem.vue'
import CartSummary from '@/components/shared/CartSummary.vue'
import PaymentForm from '@/components/shared/PaymentForm.vue'
import SectionCard from '@/components/ui/SectionCard.vue'
import SearchInput from '@/components/ui/SearchInput.vue'
import BaseSelect from '@/components/ui/BaseSelect.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import BaseModal from '@/components/ui/BaseModal.vue'
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue'

const router = useRouter()
const productStore = useCashierProductStore()
const transactionStore = useTransactionStore()
const toastStore = useToastStore()

const search = ref('')
const categoryFilter = ref('')
const confirmClearCart = ref(false)

const successModal = ref({
  show: false,
  invoiceNumber: '',
  total: 0,
  cash: 0,
  change: 0,
  id: null,
})

const cart = computed(() => transactionStore.cart)
const subTotal = computed(() => transactionStore.subTotal)
const grandTotal = computed(() => transactionStore.grandTotal)
const cartCount = computed(() => cart.value.reduce((sum, item) => sum + item.quantity, 0))

// Opsi kategori diturunkan otomatis dari daftar produk yang ada
const categoryOptions = computed(() => {
  const unique = new Map()
  productStore.products.forEach((p) => {
    if (p.category?.id) unique.set(p.category.id, p.category.name)
  })
  return Array.from(unique, ([value, label]) => ({ value, label }))
})

const filteredProducts = computed(() => {
  return productStore.products.filter((p) => {
    const matchSearch = p.name.toLowerCase().includes(search.value.toLowerCase())
    const matchCategory = !categoryFilter.value || p.category?.id === categoryFilter.value
    return matchSearch && matchCategory
  })
})

const resetFilter = () => {
  search.value = ''
  categoryFilter.value = ''
}

const addToCart = (product) => {
  const success = transactionStore.addToCart(product)
  if (!success) {
    toastStore.show?.({
      type: 'warning',
      message: `Stok ${product.name} tidak mencukupi.`,
    })
  }
}

const increaseQty = (id) => {
  const success = transactionStore.increaseQty(id)
  if (!success) {
    const item = cart.value.find((item) => item.id === id)

    toastStore.show?.({
      type: 'warning',
      message: `Stok ${item.name} tinggal ${item.stock}`,
    })
  }
}

const decreaseQty = (id) => {
  transactionStore.decreaseQty(id)
}

const removeItem = (id) => {
  transactionStore.removeItem(id)
}

const clearCart = () => {
  transactionStore.clearCart()
  confirmClearCart.value = false
}

const handlePayment = async (payment) => {
  try {
    const payload = {
      items: cart.value.map((item) => ({
        product_id: item.id,
        quantity: item.quantity,
      })),
      cash_received: payment.cash,
    }

    const transaction = await transactionStore.create(payload)

    successModal.value = {
      show: true,
      invoiceNumber: transaction.invoice_number,
      total: transaction.grand_total ?? grandTotal.value,
      cash: payment.cash,
      change: payment.change,
      id: transaction.id,
    }

    transactionStore.clearCart()
    await productStore.fetchAll()
  } catch (error) {
    if (error.response?.status === 422) {
      toastStore.show({
        type: 'warning',
        message: error.response.data.message,
      })

      await productStore.fetchAll()

      return
    }

    console.error(error)

    toastStore.show({
      type: 'error',
      message: 'Gagal memproses transaksi.',
    })
  }
}

const closeSuccessModal = () => {
  successModal.value.show = false
}

const goToReceipt = () => {
  if (successModal.value.id) {
    router.push(`/cashier/transaction-history/${successModal.value.id}/receipts`)
  }
}

const formatRupiah = (angka) =>
  new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(angka || 0)

onMounted(() => {
  productStore.fetchAll()
})
</script>
