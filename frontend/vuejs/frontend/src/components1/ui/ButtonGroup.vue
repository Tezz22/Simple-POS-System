<template>
  <div class="inline-flex rounded-xl shadow-xs isolate -space-x-px">
    <slot></slot>
  </div>
</template>

<script setup>
// Komponen ini murni berupa wrapper layouting CSS utility,
// sehingga tidak memerlukan props atau logika script khusus.
</script>

<style scoped>
/* Trik CSS Scoped untuk mengatur elemen BaseButton atau button apa pun 
  yang dimasukkan ke dalam slot agar sudutnya (border-radius) otomatis menyatu.
*/
:deep(button),
:deep(.base-button) {
  /* Matikan lengkungan default di semua sisi terlebih dahulu */
  border-radius: 0px !important;
}

/* Tombol Pertama: Beri lengkungan hanya di sisi kiri */
:flex-start,
:deep(button:first-of-type),
:deep(.base-button:first-of-type) {
  border-top-left-radius: 0.75rem !important; /* senilai rounded-xl (12px) */
  border-bottom-left-radius: 0.75rem !important;
}

/* Tombol Terakhir: Beri lengkungan hanya di sisi kanan */
:flex-end,
:deep(button:last-of-type),
:deep(.base-button:last-of-type) {
  border-top-right-radius: 0.75rem !important;
  border-bottom-right-radius: 0.75rem !important;
}
</style>
