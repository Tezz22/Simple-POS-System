<template>
  <aside
    class="bg-sidebar text-gray-100 flex flex-col fixed inset-y-0 left-0 z-40 shadow-xl transition-all duration-300 md:translate-x-0"
    :class="[
      // Mengatur lebar dinamis: w-64 saat terbuka, w-20 saat tertutup di desktop
      isOpen ? 'w-64 translate-x-0' : 'w-20 -translate-x-full md:translate-x-0'
    ]"
  >
    <!-- HEADER SIDEBAR -->
    <div 
      class="h-16 flex items-center border-b border-teal-800 shrink-0 transition-all duration-300"
      :class="[isOpen ? 'justify-between px-6' : 'justify-center px-0']"
    >
      <!-- Logo & Judul Aplikasi -->
      <div class="flex items-center gap-2 overflow-hidden whitespace-nowrap">
        <span class="text-2xl select-none">🏪</span>
        <!-- Teks judul disembunyikan lewat transisi opacity saat ditutup -->
        <h1 
          class="text-lg font-black tracking-wider text-white uppercase transition-all duration-200"
          :class="[isOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 pointer-events-none']"
        >
          TOKO KITA
        </h1>
      </div>

      <!-- Tombol Tutup (Hanya Muncul di Mobile/Tablet) -->
      <button
        @click="$emit('close')"
        class="md:hidden p-1 text-teal-300 hover:text-white cursor-pointer"
      >
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <!-- Tombol Collapse/Expand (Hanya Muncul di Desktop/MD ke atas) -->
      <button
        @click="$emit('toggle-collapse')"
        class="hidden md:block p-1 text-teal-300 hover:text-white cursor-pointer hover:bg-teal-800/40 rounded-lg transition-colors"
      >
        <!-- Ikon panah dinamis berubah arah mengikuti state isOpen -->
        <svg class="h-5 w-5 transform transition-transform duration-300" :class="{ 'rotate-180': !isOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
        </svg>
      </button>
    </div>

    <!-- DAFTAR LINK NAVIGASI MENU -->
    <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto overflow-x-hidden">
      <RouterLink
        v-for="item in menuItems"
        :key="item.to"
        :to="item.to"
        @click="handleLinkClick"
        active-class="bg-teal-800 text-white font-bold animate-fade-in"
        class="flex items-center rounded-xl text-sm font-medium text-teal-100 hover:bg-teal-800/50 hover:text-white transition-all duration-150 whitespace-nowrap"
        :class="[
          isOpen ? 'gap-3 px-4 py-3 justify-start' : 'px-0 py-3 justify-center'
        ]"
        :title="!isOpen ? item.label : ''"
      >
        <!-- Ikon Menu (Selalu Muncul) -->
        <span class="text-base shrink-0 select-none">{{ item.icon }}</span>
        
        <!-- Judul Menu (Sembunyi saat collapse) -->
        <span 
          class="transition-all duration-200 truncate"
          :class="[isOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 pointer-events-none']"
        >
          {{ item.label }}
        </span>
      </RouterLink>
    </nav>

    <!-- FOOTER PROFILE USER -->
    <div 
      class="p-4 border-t border-teal-800 bg-teal-950/40 flex items-center shrink-0 transition-all duration-300"
      :class="[isOpen ? 'gap-3 justify-start' : 'gap-0 justify-center']"
    >
      <!-- Avatar Bulat -->
      <div
        class="w-9 h-9 rounded-full bg-teal-700 flex items-center justify-center text-sm font-bold text-white shadow-inner shrink-0 select-none"
      >
        {{ authStore.user?.name?.charAt(0).toUpperCase() || 'U' }}
      </div>

      <!-- Info Teks User (Sembunyi saat collapse) -->
      <div 
        class="overflow-hidden whitespace-nowrap transition-all duration-200"
        :class="[isOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 pointer-events-none']"
      >
        <p class="text-xs font-bold text-white truncate">
          {{ authStore.user?.name || 'User POS' }}
        </p>
        <p class="text-[10px] text-teal-300 font-medium capitalize truncate">
          {{ authStore.user?.role?.name || 'Kasir' }}
        </p>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth'

const props = defineProps({
  isOpen: Boolean, // true = Terbuka lebar, false = Mengempis (mini)
  menuItems: { type: Array, required: true },
})

const emit = defineEmits(['close', 'toggle-collapse'])

const authStore = useAuthStore()

// Otomatis tutup drawer jika diakses dari hp/mobile setelah klik menu
const handleLinkClick = () => {
  if (window.innerWidth < 768) {
    emit('close')
  }
}
</script>