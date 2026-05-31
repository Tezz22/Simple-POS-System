import { defineStore } from 'pinia'

export const useToastStore = defineStore('toast', {
  state: () => ({
    toasts: [], // Menampung antrean popup yang aktif
  }),
  actions: {
    // Fungsi andalan untuk memicu kemunculan toast
    add({ type = 'success', message, duration = 3000 }) {
      const id = Date.now().toString()
      this.toasts.push({ id, type, message, duration })

      // Otomatis musnahkan toast setelah durasi habis
      setTimeout(() => {
        this.remove(id)
      }, duration)
    },
    remove(id) {
      this.toasts = this.toasts.filter((t) => t.id !== id)
    },
  },
})
