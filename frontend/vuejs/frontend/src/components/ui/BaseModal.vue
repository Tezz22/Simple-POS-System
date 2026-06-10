<template>
  <Teleport to="body">
    <Transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="show"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
      >
        <!-- Backdrop -->
        <div
          class="absolute inset-0 bg-black/50 backdrop-blur-sm z-0"
          @click="closeOnBackdrop"
        ></div>

        <!-- Modal -->
        <div
          :class="[
            'relative z-10 w-full transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-xl flex flex-col',
            sizeClasses[size],
          ]"
        >
          <!-- Header -->
          <div
            class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-6 py-4"
          >
            <h3 class="text-base font-bold text-text-primary dark:text-white tracking-tight">
              {{ title }}
            </h3>

            <button
              @click="$emit('close')"
              class="cursor-pointer rounded-lg p-1.5 text-text-secondary hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-gray-400 transition-colors"
            >
              <svg
                class="h-4 w-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>

          <!-- Body -->
          <div
            class="px-6 py-4 overflow-y-auto max-h-[70vh] text-sm text-text-primary dark:text-gray-300"
          >
            <slot />
          </div>

          <!-- Footer -->
          <div
            v-if="$slots.footer"
            class="flex items-center justify-end gap-2 border-t border-gray-100 dark:border-gray-700 px-6 py-3.5 bg-gray-50 dark:bg-gray-900"
          >
            <slot name="footer" />
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
const props = defineProps({
  show: {
    type: Boolean,
    required: true,
  },
  title: {
    type: String,
    default: 'Informasi',
  },
  size: {
    type: String,
    default: 'md',
  },
  closeOnOverlay: {
    type: Boolean,
    default: true,
  },
})

const emit = defineEmits(['close'])

const sizeClasses = {
  sm: 'max-w-md',
  md: 'max-w-lg',
  lg: 'max-w-2xl',
  xl: 'max-w-4xl',
}

const closeOnBackdrop = () => {
  if (props.closeOnOverlay) {
    emit('close')
  }
}
</script>