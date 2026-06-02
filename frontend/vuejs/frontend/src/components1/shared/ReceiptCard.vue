<template>
  <div
    class="w-full max-w-xs mx-auto bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm font-mono text-xs text-gray-800 dark:text-gray-300"
  >
    <div
      class="text-center space-y-1 pb-4 border-b border-dashed border-gray-200 dark:border-gray-800"
    >
      <h3 class="text-sm font-black tracking-wider text-gray-900 dark:text-white uppercase">
        🏪 TOKO KITA
      </h3>
      <p class="text-[10px] text-gray-400">Jl. Malioboro No. 123, Yogyakarta</p>
      <p class="text-[10px] text-gray-400">Telp: 0274-555123</p>
    </div>

    <div
      class="py-3 space-y-1 text-[11px] border-b border-dashed border-gray-200 dark:border-gray-800"
    >
      <div class="flex justify-between">
        <span>Nota: {{ transaction.id }}</span>
        <span>{{ transaction.created_at }}</span>
      </div>
      <div class="flex justify-between">
        <span>Kasir: {{ cashier.name }}</span>
        <span class="text-primary dark:text-primaryLight font-bold uppercase text-[9px]"
          >SELESAI</span
        >
      </div>
    </div>

    <div class="py-3 space-y-2 border-b border-dashed border-gray-200 dark:border-gray-800">
      <div v-for="item in items" :key="item.id" class="space-y-0.5">
        <div class="flex justify-between font-bold text-gray-900 dark:text-white">
          <span class="truncate max-w-[180px]">{{ item.name }}</span>
          <span>FormatRp {{ formatUang(item.price * item.qty) }}</span>
        </div>
        <div class="text-[10px] text-gray-400">
          {{ item.qty }} x FormatRp {{ formatUang(item.price) }}
        </div>
      </div>
      <div v-if="items.length === 0" class="text-center py-4 text-gray-400 text-[11px]">
        (Belum ada item belanja)
      </div>
    </div>

    <div class="py-3 space-y-1.5 text-[11px]">
      <div class="flex justify-between font-black text-xs text-gray-900 dark:text-white pt-1">
        <span>GRAND TOTAL</span>
        <span>FormatRp {{ formatUang(transaction.total) }}</span>
      </div>
    </div>

    <div
      class="text-center pt-4 border-t border-dashed border-gray-200 dark:border-gray-800 mt-2 space-y-1"
    >
      <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Terima Kasih</p>
      <p class="text-[9px] text-gray-450">Periksa barang sebelum meninggalkan kasir.</p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  transaction: {
    type: Object,
    required: true, // { id: 'TRX100', total: 45000, created_at: '2026-06-02' }
  },
  items: {
    type: Array,
    required: true, // [{ id: 1, name: 'Barang', price: 15000, qty: 2 }]
  },
  cashier: {
    type: Object,
    default: () => ({ name: 'Kasir POS' }),
  },
})

// Fungsi pembantu format rupiah ringkas
const formatUang = (angka) => {
  return new Intl.NumberFormat('id-ID').format(angka)
}
</script>
