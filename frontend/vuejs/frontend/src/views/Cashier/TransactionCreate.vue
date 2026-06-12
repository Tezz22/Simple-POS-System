<template>
  <div class="p-6">
    <div class="mb-6">
      <h1 class="text-2xl font-bold">Point Of Sales</h1>
      <p class="text-gray-500">Buat transaksi baru</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Product List -->
      <div class="lg:col-span-2">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
          <ProductCard
            v-for="product in productStore.products"
            :key="product.id"
            :product="product"
            @addToCart="addToCart"
          />
        </div>
      </div>

      <!-- Cart -->
      <div class="space-y-4">
        <CartSummary :subtotal="transactionStore.subTotal"/>

        <PaymentForm :grand-total="transactionStore.grandTotal" @pay="handlePayment" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useProductStore } from '@/stores/product'
import { useTransactionStore } from '@/stores/transaction'

import ProductCard from '@/components/shared/ProductCard.vue'
import CartSummary from '@/components/shared/CartSummary.vue'
import PaymentForm from '@/components/shared/PaymentForm.vue'

const productStore = useProductStore()
const transactionStore = useTransactionStore()

onMounted(() => {
  productStore.fetchAll()
})

const handlePayment = async (payment) => {
  try {
    const payload = {
      items: transactionStore.cart.map((item) => ({
        product_id: item.id,
        quantity: item.quantity,
      })),
      cash_received: payment.cash,
    }

    const transaction = await transactionStore.create(payload)

    console.log(transaction)
  } catch (error) {
    console.error(error)
  }
}

const addToCart = (product) => {
  transactionStore.addToCart(product)
}
</script>
