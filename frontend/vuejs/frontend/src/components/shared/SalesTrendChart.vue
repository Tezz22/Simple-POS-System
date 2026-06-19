<template>
  <div class="w-full">
    <svg
      :viewBox="`0 0 ${width} ${height}`"
      class="w-full h-56"
      preserveAspectRatio="none"
    >
      <!-- Garis bantu horizontal -->
      <line
        v-for="(line, idx) in gridLines"
        :key="'grid-' + idx"
        :x1="padding.left"
        :x2="width - padding.right"
        :y1="line.y"
        :y2="line.y"
        stroke="currentColor"
        class="text-gray-100 dark:text-gray-800"
        stroke-width="1"
      />

      <!-- Area gradient di bawah garis -->
      <defs>
        <linearGradient id="salesTrendGradient" x1="0" y1="0" x2="0" y2="1">
          <stop offset="0%" stop-color="var(--color-primary, #0d9488)" stop-opacity="0.25" />
          <stop offset="100%" stop-color="var(--color-primary, #0d9488)" stop-opacity="0" />
        </linearGradient>
      </defs>
      <polygon :points="areaPoints" fill="url(#salesTrendGradient)" />

      <!-- Garis tren -->
      <polyline
        :points="linePoints"
        fill="none"
        stroke="var(--color-primary, #0d9488)"
        stroke-width="2.5"
        stroke-linecap="round"
        stroke-linejoin="round"
      />

      <!-- Titik data + tooltip on hover -->
      <g v-for="(point, idx) in points" :key="'point-' + idx">
        <circle
          :cx="point.x"
          :cy="point.y"
          r="4"
          fill="white"
          stroke="var(--color-primary, #0d9488)"
          stroke-width="2.5"
          class="cursor-pointer"
          @mouseenter="hoverIndex = idx"
          @mouseleave="hoverIndex = null"
        />
        <!-- Hit area lebih besar supaya gampang di-hover -->
        <circle
          :cx="point.x"
          :cy="point.y"
          r="12"
          fill="transparent"
          class="cursor-pointer"
          @mouseenter="hoverIndex = idx"
          @mouseleave="hoverIndex = null"
        />
      </g>

      <!-- Label sumbu X -->
      <text
        v-for="(point, idx) in points"
        :key="'label-' + idx"
        :x="point.x"
        :y="height - 6"
        text-anchor="middle"
        class="fill-current text-text-secondary dark:text-gray-400"
        font-size="10"
        font-weight="600"
      >
        {{ data[idx].label }}
      </text>
    </svg>

    <!-- Tooltip nilai saat hover -->
    <div
      v-if="hoverIndex !== null"
      class="text-center -mt-2"
    >
      <span class="inline-block bg-gray-900 dark:bg-gray-700 text-white text-[11px] font-bold px-3 py-1 rounded-lg">
        {{ data[hoverIndex].label }} — {{ formatRupiah(data[hoverIndex].value) }}
      </span>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  // Format: [{ label: 'Sen', value: 120000 }, ...]
  data: {
    type: Array,
    required: true,
  },
})

const hoverIndex = ref(null)

const width  = 600
const height = 220
const padding = { top: 20, right: 16, bottom: 24, left: 16 }

const maxValue = computed(() => {
  const max = Math.max(...props.data.map((d) => d.value), 0)
  return max === 0 ? 1 : max
})

const points = computed(() => {
  const innerWidth  = width - padding.left - padding.right
  const innerHeight = height - padding.top - padding.bottom
  const count = props.data.length

  return props.data.map((d, idx) => {
    const x = count === 1
      ? padding.left + innerWidth / 2
      : padding.left + (innerWidth / (count - 1)) * idx
    const y = padding.top + innerHeight - (d.value / maxValue.value) * innerHeight
    return { x, y }
  })
})

const linePoints = computed(() =>
  points.value.map((p) => `${p.x},${p.y}`).join(' ')
)

const areaPoints = computed(() => {
  if (points.value.length === 0) return ''
  const baseline = height - padding.bottom
  const first = points.value[0]
  const last = points.value[points.value.length - 1]
  return `${first.x},${baseline} ` + linePoints.value + ` ${last.x},${baseline}`
})

const gridLines = computed(() => {
  const innerHeight = height - padding.top - padding.bottom
  return [0, 0.5, 1].map((fraction) => ({
    y: padding.top + innerHeight * fraction,
  }))
})

const formatRupiah = (angka) =>
  new Intl.NumberFormat('id-ID', {
    style: 'currency', currency: 'IDR', minimumFractionDigits: 0,
  }).format(angka || 0)
</script>
