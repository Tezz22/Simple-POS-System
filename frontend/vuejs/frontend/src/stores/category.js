import { defineStore } from 'pinia'
import api from '@/services/api'

export const useCategoryStore = defineStore('category', {
  state: () => ({
    categories: [],
    loading: false,
  }),

  actions: {
    async fetchAll() {
      this.loading = true
      try {
        const response = await api.get('/api/admin/categories')
        this.categories = response.data.data
      } catch (err) {
        console.error('Gagal mengambil data kategori:', err)
      } finally {
        this.loading = false
      }
    },
  },
})
