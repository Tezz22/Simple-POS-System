<script setup>
import { onMounted } from 'vue'
import api from './services/api'

onMounted(async () => {
    const response = await api.get('/test')

    console.log(response.data)
})
</script>

<template>
  <h1>Simple POS Vue</h1>
</template>