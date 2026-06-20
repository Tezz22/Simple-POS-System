import { defineStore } from 'pinia'
import api from '@/services/api'

export const useCashierProductStore = defineStore('cashierProduct', {
  state: () => ({
    products: [],
    loading: false,
    errors: null,
  }),

  actions: {
    async fetchAll(search = '') {
      this.loading = true
      this.errors = null

      try {
        const response = await api.get('/cashier/products', {
          params: {
            search,
          },
        })

        if (response.data.data?.data) {
          this.products = response.data.data.data
        } else {
          this.products = response.data.data
        }
      } catch (err) {
        console.error('Gagal mengambil produk kasir:', err)
        this.errors = err
      } finally {
        this.loading = false
      }
    },

    async fetchById(id) {
      this.loading = true

      try {
        const response = await api.get(`/cashier/products/${id}`)
        return response.data.data
      } catch (err) {
        console.error(err)
        throw err
      } finally {
        this.loading = false
      }
    },
  },
})
