<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
      <div class="text-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Nasi Kebuli Abuya</h2>
        <p class="text-gray-500 text-sm mt-1">Simple POS System — Silakan Login</p>
      </div>

      <div v-if="authStore.error" class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
        {{ authStore.error }}
      </div>

      <form @submit.prevent="handleLogin">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-semibold mb-2">Username</label>
          <input
            v-model="form.username"
            type="text"
            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
            placeholder="Masukkan username Anda"
            required
          />
        </div>

        <div class="mb-6">
          <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
          <input
            v-model="form.password"
            type="password"
            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
            placeholder="••••••••"
            required
          />
        </div>

        <button
          type="submit"
          :disabled="authStore.loading"
          class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 disabled:opacity-50"
        >
          {{ authStore.loading ? 'Menghubungkan...' : 'Masuk ke Sistem' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const form = reactive({
  username: '',
  password: '',
})

const handleLogin = async () => {
  try {
    await authStore.login(form.username, form.password)

    // Otorisasi: Arahkan halaman berdasarkan role pengguna setelah berhasil login
    if (authStore.isAdmin) {
      router.push('/admin/dashboard')
    } else {
      router.push('/kasir/transaksi')
    }
  } catch (error) {
    // Error sudah ditangani oleh store
    console.log('Login gagal dilakukan.')
  }
}
</script>
