<template>
  <header
    class="h-16 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between px-6 fixed top-0 right-0 left-0 md:left-64 z-30 transition-colors duration-200 shadow-xs"
  >
    <div class="flex items-center gap-3">
      <button
        @click="$emit('toggleSidebar')"
        class="md:hidden p-2 text-text-secondary hover:text-text-primary dark:text-gray-400 dark:hover:text-white cursor-pointer rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"
      >
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
      <div class="hidden sm:block">
        <slot name="page-title">
          <span class="text-xs font-medium text-text-secondary dark:text-gray-400"
            >Selamat Bekerja,</span
          >
          <h2 class="text-sm font-bold text-text-primary dark:text-white capitalize">
            {{ authStore.user?.name }}
          </h2>
        </slot>
      </div>
    </div>

    <div class="flex items-center gap-3">
      <ThemeToggle />

      <button
        @click="handleLogout"
        class="cursor-pointer flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-danger hover:bg-red-50 dark:hover:bg-red-950/20 rounded-lg transition-colors border border-transparent hover:border-red-100 dark:hover:border-red-900/30"
      >
        <span>🚪</span>
        <span class="hidden sm:inline">Keluar</span>
      </button>
    </div>
  </header>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import ThemeToggle from '@/components/ThemeToggle.vue'

defineEmits(['toggleSidebar'])

const authStore = useAuthStore()
const router = useRouter()

const handleLogout = async () => {
  if (confirm('Apakah Anda yakin ingin keluar dari TOKO KITA?')) {
    await authStore.logout()
    router.push('/login')
  }
}
</script>
