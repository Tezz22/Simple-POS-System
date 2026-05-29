<template>
  <div class="min-h-screen bg-gray-100 flex flex-col md:flex-row">
    
    <aside class="w-full md:w-64 bg-slate-800 text-white flex flex-col">
      <div class="p-5 text-xl font-bold border-b border-slate-700 bg-slate-900">
        Nasi Kebuli Abuya
      </div>
      <nav class="flex-1 p-4 space-y-2">
        <router-link to="/admin/dashboard" class="flex items-center space-x-2 p-3 rounded-lg bg-amber-600 text-white font-semibold">
          <span>📊</span> <span>Dashboard</span>
        </router-link>
        <a href="#" class="flex items-center space-x-2 p-3 rounded-lg hover:bg-slate-700 text-gray-300 transition">
          <span>👥</span> <span>Manajemen User</span>
        </a>
        <a href="#" class="flex items-center space-x-2 p-3 rounded-lg hover:bg-slate-700 text-gray-300 transition">
          <span>📦</span> <span>Data Produk</span>
        </a>
      </nav>
      <div class="p-4 border-t border-slate-700">
        <button @click="handleLogout" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg transition text-sm font-semibold">
          Keluar Sistem 🚪
        </button>
      </div>
    </aside>

    <main class="flex-1 p-6 md:p-8">
      <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 pb-4 border-b">
        <div>
          <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
          <p class="text-gray-500 text-sm mt-1">Selamat datang kembali, <span class="font-semibold text-gray-700">{{ authStore.user?.name }}</span>!</p>
        </div>
        <div class="mt-4 md:mt-0 text-sm text-gray-500 bg-white px-4 py-2 rounded-lg shadow-sm">
          Role: <span class="capitalize font-bold text-amber-600">{{ authStore.user?.role?.name }}</span>
        </div>
      </header>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
          <div>
            <p class="text-sm font-semibold text-gray-400 uppercase">Omset Hari Ini</p>
            <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp 93.000</h3>
          </div>
          <span class="text-3xl bg-green-100 p-3 rounded-lg">💰</span>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
          <div>
            <p class="text-sm font-semibold text-gray-400 uppercase">Transaksi</p>
            <h3 class="text-2xl font-bold text-gray-800 mt-1">2 Baru</h3>
          </div>
          <span class="text-3xl bg-blue-100 p-3 rounded-lg">🛒</span>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
          <div>
            <p class="text-sm font-semibold text-gray-400 uppercase">Total Produk</p>
            <h3 class="text-2xl font-bold text-gray-800 mt-1">4 Menu</h3>
          </div>
          <span class="text-3xl bg-amber-100 p-3 rounded-lg">🍗</span>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
          <div>
            <p class="text-sm font-semibold text-gray-400 uppercase">Stok Menipis</p>
            <h3 class="text-2xl font-bold text-red-600 mt-1">1 Produk</h3>
          </div>
          <span class="text-3xl bg-red-100 p-3 rounded-lg">⚠️</span>
        </div>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Status Integrasi Sistem</h2>
        <div class="p-4 bg-amber-50 text-amber-800 rounded-lg border border-amber-200 text-sm">
          <strong>Info:</strong> Data angka di atas saat ini masih bersifat sementara (*hardcoded*). Angka ini akan otomatis sinkron secara *realtime* setelah kita membuat <strong>DashboardController (Summary JSON)</strong> di backend Laravel nanti.
        </div>
      </div>
    </main>

  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const handleLogout = async () => {
    if (confirm('Apakah Anda yakin ingin keluar dari aplikasi POS?')) {
        await authStore.logout();
        router.push('/login');
    }
};
</script>