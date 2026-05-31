<template>
  <BaseModal
    :show="show"
    :title="title"
    size="sm"
    :close-on-overlay="!loading"
    @close="handleCancel"
  >
    <!-- Konten Isi Dialog Pesan -->
    <div class="flex items-start gap-4">
      <div
        :class="[
          'p-3 rounded-full text-xl shrink-0',
          variant === 'danger'
            ? 'bg-red-50 text-danger dark:bg-red-950/30'
            : 'bg-amber-50 text-warning dark:bg-amber-950/30',
        ]"
      >
        <span>{{ variant === 'danger' ? '⚠️' : '❓' }}</span>
      </div>
      <div class="space-y-1">
        <p class="font-medium text-text-primary dark:text-white">
          {{ message }}
        </p>
        <p v-if="subMessage" class="text-xs text-text-secondary dark:text-gray-400 leading-relaxed">
          {{ subMessage }}
        </p>
      </div>
    </div>

    <!-- Tombol Kontrol di Bagian Footer -->
    <template #footer>
      <BaseButton variant="outline" size="sm" :disabled="loading" @click="handleCancel">
        {{ cancelText }}
      </BaseButton>
      <BaseButton
        :variant="variant === 'danger' ? 'danger' : 'primary'"
        size="sm"
        :loading="loading"
        @click="handleConfirm"
      >
        {{ confirmText }}
      </BaseButton>
    </template>
  </BaseModal>
</template>

<script setup>
import BaseModal from './BaseModal.vue'
import BaseButton from './BaseButton.vue'

const props = defineProps({
  show: { type: Boolean, required: true },
  title: { type: String, default: 'Konfirmasi Aksi' },
  message: { type: String, default: 'Apakah Anda yakin ingin melanjutkan tindakan ini?' },
  subMessage: { type: String, default: 'Tindakan ini tidak dapat dibatalkan setelah diproses.' },
  variant: { type: String, default: 'danger' }, // danger atau warning
  confirmText: { type: String, default: 'Ya, Hapus' },
  cancelText: { type: String, default: 'Batal' },
  loading: { type: Boolean, default: false }, // Efek memuat data saat memproses konfirmasi
})

const emit = defineEmits(['confirm', 'cancel'])

const handleCancel = () => {
  if (!props.loading) {
    emit('cancel')
  }
}

const handleConfirm = () => {
  emit('confirm')
}
</script>
