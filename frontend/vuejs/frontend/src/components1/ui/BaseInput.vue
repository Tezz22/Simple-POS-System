<template>
  <div class="w-full">
    <label
      v-if="label"
      class="block text-sm font-semibold mb-1.5 text-text-primary dark:text-gray-300"
    >
      {{ label }}
    </label>

    <div class="relative rounded-lg shadow-xs">
      <div
        v-if="$slots.prefix"
        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-text-secondary"
      >
        <slot name="prefix"></slot>
      </div>

      <input
        :type="type"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :placeholder="placeholder"
        :disabled="disabled"
        :required="required"
        :class="[
          'w-full px-3 py-2 border rounded-lg focus:outline-hidden focus:ring-2 transition-all duration-200 text-sm bg-white text-text-primary dark:bg-gray-800 dark:text-white',
          $slots.prefix ? 'pl-9' : '',
          $slots.suffix ? 'pr-9' : '',
          error
            ? 'border-danger focus:ring-red-200 dark:border-danger'
            : 'border-gray-300 focus:ring-primary/20 focus:border-primary dark:border-gray-600 dark:focus:border-primary-light',
        ]"
      />

      <div
        v-if="$slots.suffix"
        class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-text-secondary"
      >
        <slot name="suffix"></slot>
      </div>
    </div>

    <p v-if="error" class="mt-1 text-xs font-medium text-danger">
      {{ error }}
    </p>
  </div>
</template>

<script setup>
defineProps({
  modelValue: [String, Number],
  type: { type: String, default: 'text' },
  label: String,
  placeholder: String,
  error: String,
  disabled: { type: Boolean, default: false },
  required: { type: Boolean, default: false },
})

defineEmits(['update:modelValue'])
</script>
