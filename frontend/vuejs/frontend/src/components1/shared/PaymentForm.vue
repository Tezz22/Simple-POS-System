<template>
  <div
    class="w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-sm font-sans"
  >
    <h3
      class="text-xs font-black uppercase text-textSecondary dark:text-gray-400 border-b pb-2 mb-4 tracking-wider"
    >
      🧮 Pembayaran Tunai
    </h3>

    <div class="space-y-4">
      <div
        class="flex justify-between items-center bg-gray-50 dark:bg-gray-950 px-4 py-3 rounded-xl border border-gray-100 dark:border-gray-850"
      >
        <span class="text-xs font-bold text-textSecondary dark:text-gray-400">Total Tagihan:</span>
        <span class="text-base font-black text-primary dark:text-primaryLight">
          Rp {{ formatUang(grandTotal) }}
        </span>
      </div>

      <div class="flex flex-col gap-1.5">
        <label class="text-[11px] font-bold text-textSecondary dark:text-gray-400">
          Uang Diterima (Cash):
        </label>
        <div class="relative flex items-center">
          <span class="absolute left-4 text-xs font-bold text-gray-400 select-none"> Rp </span>
          <input
            type="number"
            v-model.number="cashReceived"
            placeholder="0"
            min="0"
            class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-950 border border-gray-250 dark:border-gray-800 rounded-xl text-xs font-black text-textPrimary dark:text-gray-100 outline-none transition-all focus:border-primary dark:focus:border-primaryLight focus:ring-2 focus:ring-primary/10"
          />
        </div>
      </div>

      <div class="flex flex-wrap gap-1.5">
        <button
          type="button"
          @click="cashReceived = grandTotal"
          class="px-2.5 py-1.5 text-[10px] font-bold bg-teal-50 dark:bg-teal-950/20 text-teal-600 dark:text-teal-400 rounded-lg border border-teal-200/40 cursor-pointer hover:bg-teal-100 transition-colors"
        >
          Uang Pas
        </button>
        <button
          v-for="nominal in pecahanUang"
          :key="nominal"
          type="button"
          v-show="nominal > grandTotal"
          @click="cashReceived = nominal"
          class="px-2.5 py-1.5 text-[10px] font-bold bg-gray-100 dark:bg-gray-800 text-textSecondary dark:text-gray-300 rounded-lg cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
        >
          {{ formatUang(nominal) }}
        </button>
      </div>

      <div
        class="flex justify-between items-center px-4 py-3 rounded-xl border border-dashed transition-all duration-150"
        :class="[
          isKurang
            ? 'bg-red-50/40 dark:bg-red-950/10 border-red-200/60 text-red-600'
            : 'bg-green-50/40 dark:bg-green-950/10 border-green-200/60 text-green-600 dark:text-green-400',
        ]"
      >
        <span class="text-xs font-bold">
          {{ isKurang ? 'Kekurangan Uang:' : 'Uang Kembalian:' }}
        </span>
        <span class="text-sm font-black"> Rp {{ formatUang(Math.abs(changeAmount)) }} </span>
      </div>

      <button
        type="button"
        :disabled="isKurang || grandTotal <= 0"
        @click="eksekusiPembayaran"
        class="w-full py-3 rounded-xl text-xs font-black uppercase tracking-wider text-white bg-primary dark:bg-primaryLight dark:text-gray-900 shadow-sm cursor-pointer transition-all active:scale-[0.98] disabled:opacity-40 disabled:cursor-not-allowed disabled:pointer-events-none"
      >
        🏁 Selesaikan Transaksi
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
  grandTotal: {
    type: Number,
    required: true,
  },
})

const emit = defineEmits(['pay'])

const cashReceived = ref('')
const pecahanUang = [10000, 20000, 50000, 100000]

// Hitung nominal kembalian atau kekurangan uang secara reaktif
const changeAmount = computed(() => {
  const bayar = cashReceived.value || 0
  return bayar - props.grandTotal
})

const isKurang = computed(() => {
  return changeAmount.value < 0
})

// Otomatis reset input uang cash jika isi keranjang di-clear atau berubah totalnya
watch(
  () => props.grandTotal,
  (newTotal) => {
    if (newTotal === 0) cashReceived.value = ''
  },
)

const eksekusiPembayaran = () => {
  if (isKurang.value || props.grandTotal <= 0) return
  emit('pay', {
    total: props.grandTotal,
    cash: cashReceived.value,
    change: changeAmount.value,
  })
}

const formatUang = (angka) => {
  return new Intl.NumberFormat('id-ID').format(angka)
}
</script>
