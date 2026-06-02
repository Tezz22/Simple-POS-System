<template>
  <div
    class="min-h-screen bg-background text-text-primary dark:bg-gray-950 dark:text-gray-100 transition-colors duration-200"
  >
    <Sidebar :isOpen="sidebarOpen" :menuItems="menuItems" @close="sidebarOpen = false" />

    <div
      v-if="sidebarOpen"
      class="fixed inset-0 z-35 bg-black/40 backdrop-blur-xs md:hidden"
      @click="sidebarOpen = false"
    ></div>

    <div class="md:pl-64 flex flex-col min-h-screen pt-16">
      <TopBar @toggleSidebar="sidebarOpen = !sidebarOpen" />

      <main class="flex-1 p-6 overflow-y-auto">
        <div class="max-w-7xl mx-auto space-y-6">
          <slot></slot>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useAuthStore } from '@/stores/auth'
import Sidebar from './Sidebar.vue'
import TopBar from './TopBar.vue'

const authStore = useAuthStore()
const sidebarOpen = ref(false)

// Menentukan link menu secara otomatis berdasarkan role user yang login
const menuItems = computed(() => {
  if (authStore.isAdmin) {
    return [
      { label: 'Dashboard', to: '/admin/dashboard', icon: '📊' },
      { label: 'Manajemen Produk', to: '/admin/produk', icon: '📦' },
      { label: 'Kelola Karyawan', to: '/admin/karyawan', icon: '👥' },
      { label: 'Laporan Penjualan', to: '/admin/laporan', icon: '📈' },
    ]
  } else {
    return [
      { label: 'Transaksi Baru', to: '/kasir/transaksi', icon: '🛒' },
      { label: 'Riwayat Transaksi', to: '/kasir/riwayat', icon: '📋' },
    ]
  }
})
</script>
