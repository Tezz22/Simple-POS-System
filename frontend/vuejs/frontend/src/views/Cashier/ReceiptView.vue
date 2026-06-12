<template>
  <div class="max-w-2xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Receipt</h1>

    <ReceiptCard v-if="transactionStore.receipt" :receipt="transactionStore.receipt" />
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRoute } from 'vue-router'

import { useTransactionStore } from '@/stores/transaction'

import ReceiptCard from '@/components/shared/ReceiptCard.vue'

const route = useRoute()

const transactionStore = useTransactionStore()

onMounted(async () => {
  await transactionStore.fetchReceipt(route.params.id)
})
</script>
