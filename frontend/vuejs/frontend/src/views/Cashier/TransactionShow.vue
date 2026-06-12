<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">
      Detail Transaksi
    </h1>

    <div
      v-if="transactionStore.transaction"
      class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow"
    >
      <div class="space-y-2">
        <p>
          Invoice:
          {{ transactionStore.transaction.invoice }}
        </p>

        <p>
          Total:
          Rp {{ transactionStore.transaction.total }}
        </p>

        <p>
          Status:
          {{ transactionStore.transaction.status }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRoute } from 'vue-router'

import { useTransactionStore } from '@/stores/transaction'

const route = useRoute()

const transactionStore = useTransactionStore()

onMounted(async () => {
  await transactionStore.fetchById(route.params.id)
})
</script>