import { defineStore } from 'pinia'
import api from '@/services/api'

export const useTransactionStore = defineStore('transaction', {
  state: () => ({
    cart: [],

    transactions: [],
    transaction: null,
    receipt: null,

    loading: false,
  }),

  getters: {
    totalItems: (state) => {
      return state.cart.reduce((total, item) => total + item.quantity, 0)
    },

    subTotal: (state) => {
      return state.cart.reduce((total, item) => total + item.selling_price * item.quantity, 0)
    },

    tax() {
      return this.subTotal * 0.11
    },

    grandTotal() {
      return this.subTotal + this.tax
    },

    isCartEmpty: (state) => state.cart.length === 0,
  },

  actions: {
    // =========================
    // CART
    // =========================

    addToCart(product) {
      const existing = this.cart.find((item) => item.id === product.id)

      // stok habis
      if (Number(product.stock) <= 0) {
        return false
      }

      if (existing) {
        // qty sudah mentok stok
        if (existing.quantity >= Number(product.stock)) {
          return false
        }

        existing.quantity++
        return true
      }

      this.cart.push({
        id: product.id,
        name: product.name,
        code: product.code,
        selling_price: Number(product.selling_price),
        stock: Number(product.stock),
        quantity: 1,
      })

      return true
    },

    increaseQty(productId) {
      const item = this.cart.find((item) => item.id === productId)

      if (!item) return false

      if (item.quantity >= item.stock) {
        return false
      }

      item.quantity++

      return true
    },

    updateProductStock(productId, stock) {
      const item = this.cart.find((item) => item.id === productId)

      if (!item) return

      item.stock = Number(stock)

      // kalau stok berkurang setelah transaksi lain
      if (item.quantity > item.stock) {
        item.quantity = item.stock
      }

      if (item.stock <= 0) {
        this.removeItem(productId)
      }
    },

    decreaseQty(productId) {
      const item = this.cart.find((item) => item.id === productId)

      if (!item) return

      if (item.quantity > 1) {
        item.quantity--
        return
      }

      this.removeFromCart(productId)
    },

    removeFromCart(productId) {
      this.cart = this.cart.filter((item) => item.id !== productId)
    },

    removeItem(productId) {
      this.removeFromCart(productId)
    },

    clearCart() {
      this.cart = []
    },

    // =========================
    // API
    // =========================

    async fetchAll() {
      this.loading = true

      try {
        const response = await api.get('/cashier/transactions')

        this.transactions = response.data.data
      } catch (error) {
        console.error(error)
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
      } catch (error) {
        console.error(error)
      } finally {
        this.loading = false
      }
    },

    async create(payload) {
      this.loading = true

      try {
        const response = await api.post('/cashier/transactions', payload)

        this.clearCart()

        return response.data.data
      } catch (error) {
        console.error(error)
        // console.log(error.response?.status)
        // console.log(error.response?.data)
        throw error
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
      } catch (error) {
        console.error(error)
      } finally {
        this.loading = false
      }
    },
  },
})
