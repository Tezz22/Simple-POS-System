import { defineStore } from 'pinia'
import api from '@/services/api'

export const useDashboardStore = defineStore('dashboard', {
  state: () => ({
    data: null,
    loading: false,
    error: false,
  }),

  actions: {
    async fetchDashboard() {
      this.loading = true
      this.error = false

      try {
        const response = await api.get('/admin/dashboard')
        this.data = response.data.data
      } catch (err) {
        console.error('Gagal memuat dashboard:', err)
        this.error = true
      } finally {
        this.loading = false
      }
    },
  },
})
