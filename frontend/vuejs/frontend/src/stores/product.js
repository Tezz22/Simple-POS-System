import { defineStore } from 'pinia'
import api from '@/services/api'

export const useProductStore = defineStore('product', {
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
        const response = await api.get('/admin/products', {
          params: { search: search },
        })

        console.log('RESPONSE API:', response.data)

        if (response.data.data && response.data.data.data) {
          this.products = response.data.data.data
        } else {
          this.products = response.data.data
        }

        console.log('PRODUCTS:', this.products)

      } catch (err) {
        console.error('Gagal mengambil data produk:', err)
      } finally {
        this.loading = false
      }
    },

    async fetchById(id) {
      this.loading = true
      this.errors = null
      try {
        const response = await api.get(`/admin/products/${id}`)
        return response.data.data
      } catch (err) {
        console.error('Gagal mengambil detail produk:', err)
        throw err
      } finally {
        this.loading = false
      }
    },

    async createProduct(payload) {
      this.loading = true
      this.errors = null
      try {
        const response = await api.post('/admin/products', payload)
        return response.data
      } catch (err) {
        if (err.response && err.response.status === 422) {
          this.errors = err.response.data.errors
        }
        throw err
      } finally {
        this.loading = false
      }
    },

    async updateProduct(id, payload) {
      this.loading = true
      this.errors = null
      try {
        const response = await api.put(`/admin/products/${id}`, payload)
        return response.data
      } catch (err) {
        if (err.response && err.response.status === 422) {
          this.errors = err.response.data.errors
        }
        throw err
      } finally {
        this.loading = false
      }
    },

    async deactivateProduct(id) {
      this.loading = true
      try {
        await api.patch(`/admin/products/${id}/deactivate`)
        await this.fetchAll()
      } catch (err) {
        console.error('Gagal menonaktifkan produk:', err)
      } finally {
        this.loading = false
      }
    },
  },
})
