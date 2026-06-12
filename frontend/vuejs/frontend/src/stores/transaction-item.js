import { defineStore } from 'pinia'
import api from '@/services/api'

export const useTransactionItemStore = defineStore('transaction-item', {
  state: () => ({
    loading: false,
  }),

  actions: {
    async create(payload) {
      this.loading = true

      try {
        const response = await api.post('/cashier/transaction-items', payload)

        return response.data.data
      } catch (err) {
        console.error('Gagal menambah item transaksi:', err)
        throw err
      } finally {
        this.loading = false
      }
    },

    async update(id, payload) {
      this.loading = true

      try {
        const response = await api.put(`/cashier/transaction-items/${id}`, payload)

        return response.data.data
      } catch (err) {
        console.error('Gagal mengubah item transaksi:', err)
        throw err
      } finally {
        this.loading = false
      }
    },

    async delete(id) {
      this.loading = true

      try {
        await api.delete(`/cashier/transaction-items/${id}`)
      } catch (err) {
        console.error('Gagal menghapus item transaksi:', err)
        throw err
      } finally {
        this.loading = false
      }
    },
  },
})
