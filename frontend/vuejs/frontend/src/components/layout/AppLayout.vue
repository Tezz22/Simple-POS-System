<template>
  <div
    class="min-h-screen bg-background text-text-primary dark:bg-gray-950 dark:text-gray-100 transition-colors duration-200"
  >
    <Sidebar
      :isOpen="sidebarOpen"
      :menuItems="menuItems"
      @close="sidebarOpen = false"
      @toggle-collapse="sidebarOpen = !sidebarOpen"
    />

    <div
      v-if="sidebarOpen"
      class="fixed inset-0 z-35 bg-black/40 backdrop-blur-xs md:hidden"
      @click="sidebarOpen = false"
    ></div>

    <!-- Padding kiri dinamis: lebar penuh saat terbuka (pl-64), ramping saat ditutup (pl-20) -->
    <div
      class="flex flex-col min-h-screen pt-16 transition-all duration-300"
      :class="sidebarOpen ? 'md:pl-64' : 'md:pl-20'"
    >
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
// icon menggunakan nama icon Iconify (heroicons)
const menuItems = computed(() => {
  if (authStore.isAdmin) {
    return [
      { label: 'Dashboard',          to: '/admin/dashboard', icon: 'heroicons:squares-2x2-solid' },
      { label: 'Manajemen Produk',   to: '/admin/produk',    icon: 'heroicons:archive-box-solid' },
      { label: 'Kelola Karyawan',    to: '/admin/karyawan',  icon: 'heroicons:users-solid' },
      { label: 'Laporan Penjualan',  to: '/admin/laporan',   icon: 'heroicons:chart-bar-solid' },
    ]
  } else {
    return [
      { label: 'Transaksi Baru',       to: '/kasir/transaksi', icon: 'heroicons:shopping-cart-solid' },
      { label: 'Riwayat Transaksi',    to: '/kasir/riwayat',   icon: 'heroicons:clipboard-document-list-solid' },
    ]
  }
})
</script>
