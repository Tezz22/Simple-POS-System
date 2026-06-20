<template>
  <div class="flex h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Sidebar Component -->
    <Sidebar
      :is-open="isSidebarOpen"
      :menu-items="menuItems"
      @close="isSidebarOpen = false"
      @toggle-collapse="isSidebarOpen = !isSidebarOpen"
    />

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden transition-all duration-300" :class="[
      isSidebarOpen
        ? 'md:ml-64'
        : 'md:ml-20'
    ]">
      <!-- TopBar Component -->
      <TopBar :is-sidebar-open="isSidebarOpen" @toggle-sidebar="isSidebarOpen = !isSidebarOpen" />

      <!-- Main Content -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-gray-900 mt-16">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import Sidebar from '@/components/layout/Sidebar.vue'
import TopBar from '@/components/layout/TopBar.vue'

const isSidebarOpen = ref(true)

const menuItems = [
  { label: 'Dashboard', to: '/admin/dashboard', icon: 'heroicons:squares-2x2-solid' },
  // { label: 'Kategori Produk', to: '/admin/categories', icon: 'heroicons:tag-solid' },
  { label: 'Master Produk', to: '/admin/products', icon: 'heroicons:shopping-bag-solid' },
  { label: 'Transaksi Penjualan', to: '/admin/transaction-history', icon: 'heroicons:credit-card-solid' },
]
</script>
