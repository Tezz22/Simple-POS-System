import { defineStore } from 'pinia'
import api from '@/services/api'

export const useTransactionStore = defineStore('transaction', {
  state: () => ({
    transactions: [],
    transaction: null,
    receipt: null,
    loading: false,
  }),

  actions: {
    async fetchAll() {
      this.loading = true

      try {
        const response = await api.get('/cashier/transactions')
        this.transactions = response.data.data
      } catch (err) {
        console.error('Gagal mengambil data transaksi:', err)
      } finally {
        this.loading = false
      }
    },

    async fetchById(id) {
      this.loading = true

      try {
        const response = await api.get(`/cashier/transactions/${id}`)
        this.transaction = response.data.data

        return response.data.data
      } catch (err) {
        console.error('Gagal mengambil detail transaksi:', err)
      } finally {
        this.loading = false
      }
    },

    async create(payload) {
      this.loading = true

      try {
        const response = await api.post('/cashier/transactions', payload)

        return response.data.data
      } catch (err) {
        console.error('Gagal membuat transaksi:', err)
        throw err
      } finally {
        this.loading = false
      }
    },

    async pay(id, payload) {
      this.loading = true

      try {
        const response = await api.post(`/cashier/transactions/${id}/pay`, payload)

        return response.data
      } catch (err) {
        console.error('Gagal melakukan pembayaran:', err)
        throw err
      } finally {
        this.loading = false
      }
    },

    async cancel(id) {
      this.loading = true

      try {
        const response = await api.post(`/cashier/transactions/${id}/cancel`)

        return response.data
      } catch (err) {
        console.error('Gagal membatalkan transaksi:', err)
        throw err
      } finally {
        this.loading = false
      }
    },

    async fetchReceipt(id) {
      this.loading = true

      try {
        const response = await api.get(`/cashier/receipts/${id}`)

        this.receipt = response.data.data

        return response.data.data
      } catch (err) {
        console.error('Gagal mengambil receipt:', err)
      } finally {
        this.loading = false
      }
    },
  },
})
