<template>
  <div class="p-6">
    <div class="mb-6">
      <h1 class="text-2xl font-bold">Riwayat Transaksi</h1>
    </div>

    <BaseTable>
      <thead>
        <tr>
          <th>Invoice</th>
          <th>Kasir</th>
          <th>Total</th>
          <th>Status</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="item in transactionStore.transactions" :key="item.id">
          <td>{{ item.invoice }}</td>
          <td>{{ item.cashier?.name }}</td>
          <td>Rp {{ item.total }}</td>
          <td>
            <TransactionStatusBadge :status="item.status" />
          </td>
        </tr>
      </tbody>
    </BaseTable>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useTransactionStore } from '@/stores/transaction'

import BaseTable from '@/components/ui/BaseTable.vue'
import TransactionStatusBadge from '@/components/shared/TransactionStatusBadge.vue'

const transactionStore = useTransactionStore()

onMounted(() => {
  transactionStore.fetchAll()
})
</script>
