<template>
  <aside
    class="w-64 bg-sidebar text-gray-100 flex flex-col fixed inset-y-0 left-0 z-40 shadow-xl transition-transform duration-300 md:translate-x-0"
    :class="[isOpen ? 'translate-x-0' : '-translate-x-full']"
  >
    <div class="h-16 flex items-center justify-between px-6 border-b border-teal-800 shrink-0">
      <div class="flex items-center gap-2">
        <span class="text-2xl">🏪</span>
        <h1 class="text-lg font-black tracking-wider text-white uppercase">TOKO KITA</h1>
      </div>
      <button
        @click="$emit('close')"
        class="md:hidden p-1 text-teal-300 hover:text-white cursor-pointer"
      >
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
      <RouterLink
        v-for="item in menuItems"
        :key="item.to"
        :to="item.to"
        @click="$emit('close')"
        active-class="bg-teal-800 text-white font-bold"
        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-teal-100 hover:bg-teal-800/50 hover:text-white transition-all duration-150"
      >
        <span class="text-base">{{ item.icon }}</span>
        <span>{{ item.label }}</span>
      </RouterLink>
    </nav>

    <div class="p-4 border-t border-teal-800 bg-teal-950/40 flex items-center gap-3 shrink-0">
      <div
        class="w-9 h-9 rounded-full bg-teal-700 flex items-center justify-center text-sm font-bold text-white shadow-inner"
      >
        {{ authStore.user?.name?.charAt(0).toUpperCase() || 'U' }}
      </div>
      <div class="overflow-hidden">
        <p class="text-xs font-bold text-white truncate">
          {{ authStore.user?.name || 'User POS' }}
        </p>
        <p class="text-[10px] text-teal-300 font-medium capitalize">
          {{ authStore.user?.role?.name || 'Kasir' }}
        </p>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth'

defineProps({
  isOpen: Boolean,
  menuItems: { type: Array, required: true }, // Menerima array menu dinamis tergantung role
})
defineEmits(['close'])

const authStore = useAuthStore()
</script>
