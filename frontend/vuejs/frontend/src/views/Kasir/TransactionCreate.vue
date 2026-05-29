<template>
  <div class="min-h-screen bg-gray-100 flex flex-col">
    
    <header class="bg-amber-600 text-white shadow-md px-6 py-4 flex justify-between items-center">
      <div class="flex items-center space-x-3">
        <span class="text-2xl">🕌</span>
        <h1 class="text-xl font-bold">POS Nasi Kebuli Abuya — Mode Kasir</h1>
      </div>
      <div class="flex items-center space-x-4">
        <span class="text-sm bg-amber-700 px-3 py-1 rounded-full">
          👤 {{ authStore.user?.name }}
        </span>
        <button @click="handleLogout" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-sm font-semibold transition">
          Keluar 🚪
        </button>
      </div>
    </header>

    <main class="flex-1 flex flex-col lg:flex-row p-4 gap-4 overflow-hidden">
      
      <section class="flex-1 bg-white rounded-xl shadow-sm p-4 flex flex-col">
        <div class="mb-4">
          <input 
            type="text" 
            placeholder="🔍 Scan Barcode atau Ketik Nama Menu..." 
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
          />
        </div>
        
        <div class="flex-1 grid grid-cols-2 md:grid-cols-3 gap-4 overflow-y-auto content-start">
          <div class="border rounded-xl p-4 bg-gray-50 text-center shadow-sm">
            <span class="text-3xl">🍗</span>
            <h3 class="font-bold text-gray-800 mt-2">Kebuli Ayam Abuya</h3>
            <p class="text-amber-600 font-semibold text-sm">Rp 25.000</p>
            <button class="mt-3 w-full bg-amber-500 text-white text-xs py-1.5 rounded-lg font-bold">+ Tambah</button>
          </div>
          <div class="border rounded-xl p-4 bg-gray-50 text-center shadow-sm">
            <span class="text-3xl">🥩</span>
            <h3 class="font-bold text-gray-800 mt-2">Kebuli Kambing Abuya</h3>
            <p class="text-amber-600 font-semibold text-sm">Rp 38.000</p>
            <button class="mt-3 w-full bg-amber-500 text-white text-xs py-1.5 rounded-lg font-bold">+ Tambah</button>
          </div>
          <div class="border rounded-xl p-4 bg-gray-50 text-center shadow-sm">
            <span class="text-3xl">🥤</span>
            <h3 class="font-bold text-gray-800 mt-2">Es Teh Manis</h3>
            <p class="text-amber-600 font-semibold text-sm">Rp 4.000</p>
            <button class="mt-3 w-full bg-amber-500 text-white text-xs py-1.5 rounded-lg font-bold">+ Tambah</button>
          </div>
        </div>
      </section>

      <section class="w-full lg:w-96 bg-white rounded-xl shadow-sm p-4 flex flex-col justify-between">
        <div>
          <h2 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">🛒 Keranjang Belanja</h2>
          <div class="text-gray-400 text-center py-12 text-sm">
            Keranjang masih kosong.<br>Klik menu di kiri untuk menambahkan belanjaan.
          </div>
        </div>

        <div class="border-t pt-4 bg-white">
          <div class="flex justify-between items-center mb-4">
            <span class="text-gray-600 font-medium">Total Tagihan:</span>
            <span class="text-2xl font-bold text-gray-900">Rp 0</span>
          </div>
          <button disabled class="w-full bg-gray-300 text-gray-500 font-bold py-3 rounded-xl cursor-not-allowed">
            Pilih Metode Pembayaran 💳
          </button>
        </div>
      </section>

    </main>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const handleLogout = async () => {
  if (confirm('Apakah kasir ingin keluar dari aplikasi?')) {
    await authStore.logout();
    router.push('/login');
  }
};
</script>