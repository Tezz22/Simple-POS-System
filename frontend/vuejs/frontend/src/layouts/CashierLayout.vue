<template>
  <div class="flex h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Sidebar -->
    <Sidebar
      :is-open="isSidebarOpen"
      :menu-items="menuItems"
      @close="isSidebarOpen = false"
      @toggle-collapse="isSidebarOpen = !isSidebarOpen"
    />

    <!-- Main Content -->
    <div
      class="flex-1 flex flex-col overflow-hidden transition-all duration-300"
      :class="[isSidebarOpen ? 'md:ml-64' : 'md:ml-20']"
    >
      <TopBar :is-sidebar-open="isSidebarOpen" @toggle-sidebar="isSidebarOpen = !isSidebarOpen" />

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
  {
    label: 'Kasir',
    to: '/cashier',
    icon: 'heroicons:calculator-solid',
  },
  {
    label: 'Riwayat Transaksi',
    to: '/cashier/transactions',
    icon: 'heroicons:receipt-percent-solid',
  },
]
</script>
