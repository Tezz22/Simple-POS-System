<template>
  <button 
    type="button"
    @click="toggleTheme" 
    class="cursor-pointer px-4 py-2 rounded-lg font-medium transition duration-200 border text-sm
           bg-gray-200 border-gray-300 text-gray-800 
           dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
  >
    <span>{{ isDark ? '☀️' : '🌙' }}</span>
  </button>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'

// 1. Inisialisasi status awal (default false / terang)
const isDark = ref(false)

// 2. Gunakan fungsi Watcher untuk mengawasi variabel isDark
// Kapan pun nilai isDark berubah, fungsi di bawah ini OTOMATIS langsung ikut mengeksekusi HTML
watch(isDark, (newValue) => {
  if (newValue) {
    document.documentElement.classList.add('dark')
    localStorage.setItem('theme', 'dark')
  } else {
    document.documentElement.classList.remove('dark')
    localStorage.setItem('theme', 'light')
  }
}, { immediate: false }) // Jalankan hanya saat ada interaksi klik

// 3. Logika pembalik nilai saat tombol diklik
const toggleTheme = () => {
  isDark.value = !isDark.value
  console.log('Tombol tema diklik! Status dark saat ini:', isDark.value)
}

// 4. Sinkronisasi saat komponen pertama kali dipasang di layar
onMounted(() => {
  const savedTheme = localStorage.getItem('theme')
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
  
  if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
    isDark.value = true
    document.documentElement.classList.add('dark')
  } else {
    isDark.value = false
    document.documentElement.classList.remove('dark')
  }
})
</script>