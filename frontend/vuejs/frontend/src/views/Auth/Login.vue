<template>
  <div
    class="min-h-screen w-full flex items-center justify-center bg-gray-50 dark:bg-gray-950 px-4 py-12 transition-colors duration-200 relative"
  >
    <div class="absolute top-4 right-4 z-10">
      <ThemeToggle />
    </div>

    <div
      class="w-full max-w-md bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-xl p-8 space-y-6 transition-colors duration-200"
    >
      <div class="text-center space-y-2">
        <div
          class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-primary/10 dark:bg-primary-light/10 text-3xl mb-1"
        >
          🏪
        </div>
        <h2 class="text-2xl font-black tracking-tight text-text-primary dark:text-white uppercase">
          TOKO KITA
        </h2>
        <p class="text-xs text-text-secondary dark:text-gray-400 font-medium">
          Silakan masuk untuk mengakses sistem POS & Manajemen
        </p>
      </div>

      <BaseAlert v-if="errorMessage" variant="error" dismissible @close="errorMessage = ''">
        {{ errorMessage }}
      </BaseAlert>

      <form @submit.prevent="handleLogin" class="space-y-4">
        <FormGroup label="Username atau Email" required>
          <BaseInput
            v-model="form.username"
            type="text"
            placeholder="Masukkan username Anda"
            required
            :disabled="loading"
          />
        </FormGroup>

        <FormGroup label="Kata Sandi" required>
          <BaseInput
            v-model="form.password"
            type="password"
            placeholder="••••••••"
            required
            :disabled="loading"
          />
        </FormGroup>

        <div class="flex items-center justify-between text-xs pt-1">
          <label
            class="flex items-center gap-2 font-medium text-text-secondary dark:text-gray-400 cursor-pointer select-none"
          >
            <input
              type="checkbox"
              v-model="form.rememberMe"
              class="rounded-sm border-gray-300 text-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-700"
            />
            Ingat saya di perangkat ini
          </label>
        </div>

        <div class="pt-2">
          <BaseButton
            type="submit"
            variant="primary"
            class="w-full justify-center text-sm py-2.5 font-bold tracking-wide"
            :loading="loading"
          >
            Masuk ke Aplikasi
          </BaseButton>
        </div>
      </form>

      <div class="text-center border-t border-gray-100 dark:border-gray-850 pt-4">
        <p class="text-[11px] font-medium text-text-secondary dark:text-gray-500">
          &copy; 2026 TOKO KITA. All rights reserved.
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useToastStore } from '@/stores/toast'

// Import semua komponen UI dan Toggle Tema
import BaseButton from '@/components/ui/BaseButton.vue'
import BaseInput from '@/components/ui/BaseInput.vue'
import BaseAlert from '@/components/ui/BaseAlert.vue'
import FormGroup from '@/components/ui/FormGroup.vue'
import ThemeToggle from '@/components/ThemeToggle.vue' // Memanggil tombol saklar warna

const router = useRouter()
const authStore = useAuthStore()
const toastStore = useToastStore()

const loading = ref(false)
const errorMessage = ref('')

const form = reactive({
  username: '',
  password: '',
  rememberMe: false,
})

const handleLogin = async () => {
  loading.value = true
  errorMessage.value = ''

  try {
    const success = await authStore.login(form.username, form.password)

    if (success) {
      toastStore.add({
        type: 'success',
        message: `Selamat datang kembali, ${authStore.user?.name || 'User'}!`,
        duration: 3000,
      })

      if (authStore.isAdmin) {
        router.push('/admin/dashboard')
      } else {
        router.push('/kasir/transaksi')
      }
    } else {
      errorMessage.value = 'Username atau kata sandi yang Anda masukkan salah.'
    }
  } catch (error) {
    errorMessage.value = 'Terjadi kesalahan jaringan. Silakan coba beberapa saat lagi.'
  } finally {
    loading.value = false
  }
}
</script>
