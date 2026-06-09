<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <div class="px-4 sm:px-6 lg:px-8 py-8 max-w-3xl mx-auto space-y-6">
      <!-- Header Section -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="space-y-1">
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <Icon icon="heroicons:pencil-square-solid" class="w-8 h-8 text-teal-600 dark:text-teal-400" />
            Perbarui Data Produk
          </h1>
          <p class="text-sm text-gray-600 dark:text-gray-400">
            Ubah dan perbarui rincian informasi komoditas barang dagang kasir Anda.
          </p>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loadingData" class="flex justify-center py-16">
        <div class="text-center">
          <LoadingSpinner size="lg" />
          <p class="mt-4 text-gray-600 dark:text-gray-400">Memuat data produk...</p>
        </div>
      </div>

      <!-- Form Container -->
      <div v-else class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Info Alert -->
        <div class="bg-blue-50 dark:bg-blue-950/20 border-b border-blue-200 dark:border-blue-900/30 p-4 flex items-start gap-3">
          <Icon icon="heroicons:information-circle-solid" class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" />
          <div>
            <p class="text-sm font-medium text-blue-900 dark:text-blue-200">Catatan Penting</p>
            <p class="text-sm text-blue-800 dark:text-blue-300">Kode Produk tidak dapat diubah. Pastikan harga jual lebih tinggi dari harga beli pokok.</p>
          </div>
        </div>

        <form @submit.prevent="handleSubmit" class="p-6 space-y-6">
          <!-- Section: Kategori -->
          <div class="space-y-4">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
              <Icon icon="heroicons:tag-solid" class="w-4 h-4 text-teal-600" />
              Kategori & Identitas
            </h3>
            
            <FormGroup label="Kategori Produk" required :error="errors?.category_id">
              <BaseSelect 
                v-model="form.category_id"
                :options="categoryOptions"
                placeholder="Pilih Kategori Barang"
              />
            </FormGroup>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <FormGroup label="Kode Produk / SKU" required :error="errors?.code">
                <BaseInput 
                  v-model="form.code" 
                  disabled 
                  class="bg-gray-100 dark:bg-gray-700 cursor-not-allowed"
                />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kode produk bersifat tetap dan tidak dapat diubah</p>
              </FormGroup>

              <FormGroup label="Barcode (Opsional)" :error="errors?.barcode">
                <BaseInput 
                  v-model="form.barcode" 
                  placeholder="Scan nomor barcode"
                />
              </FormGroup>
            </div>
          </div>

          <!-- Divider -->
          <div class="border-t border-gray-200 dark:border-gray-700"></div>

          <!-- Section: Informasi Produk -->
          <div class="space-y-4">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
              <Icon icon="heroicons:shopping-bag-solid" class="w-4 h-4 text-teal-600" />
              Informasi Produk
            </h3>

            <FormGroup label="Nama Lengkap Barang" required :error="errors?.name">
              <BaseInput 
                v-model="form.name" 
                placeholder="Masukkan nama komoditas produk"
              />
            </FormGroup>

            <FormGroup label="Deskripsi & Catatan" :error="errors?.description">
              <BaseTextarea 
                v-model="form.description" 
                rows="3"
                placeholder="Tulis rincian, spesifikasi, atau catatan produk di sini..."
              />
            </FormGroup>
          </div>

          <!-- Divider -->
          <div class="border-t border-gray-200 dark:border-gray-700"></div>

          <!-- Section: Harga & Stok -->
          <div class="space-y-4">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
              <Icon icon="heroicons:currency-dollar-solid" class="w-4 h-4 text-teal-600" />
              Harga & Stok
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <FormGroup label="Harga Beli Pokok (Rp)" required :error="errors?.purchase_price">
                <BaseInput 
                  v-model.number="form.purchase_price" 
                  type="number"
                  min="0"
                />
              </FormGroup>

              <FormGroup 
                label="Harga Jual Kasir (Rp)" 
                required 
                :error="priceError || errors?.selling_price"
              >
                <BaseInput 
                  v-model.number="form.selling_price" 
                  type="number"
                  :min="form.purchase_price"
                />
                <p v-if="priceError" class="mt-1 text-xs text-red-600 dark:text-red-400">{{ priceError }}</p>
              </FormGroup>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <FormGroup label="Stok Tersedia" required :error="errors?.stock">
                <BaseInput 
                  v-model.number="form.stock" 
                  type="number"
                  min="0"
                />
              </FormGroup>

              <FormGroup label="Batas Minimum Stok" required :error="errors?.min_stock">
                <BaseInput 
                  v-model.number="form.min_stock" 
                  type="number"
                  min="0"
                />
              </FormGroup>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end gap-3">
            <button
              type="button"
              @click="handleCancel"
              class="px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="isSubmitting || productStore.loading"
              class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 dark:from-teal-500 dark:to-teal-600 dark:hover:from-teal-600 dark:hover:to-teal-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:scale-100"
            >
              <Icon icon="heroicons:arrow-path-solid" class="w-5 h-5" />
              <span v-if="!isSubmitting && !productStore.loading">Perbarui Produk</span>
              <span v-else>Menyimpan...</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { Icon } from '@iconify/vue'
import { useProductStore } from '@/stores/product'
import { useCategoryStore } from '@/stores/category'

const route = useRoute()
const router = useRouter()
const productStore = useProductStore()
const categoryStore = useCategoryStore()

const errors = computed(() => productStore.errors)
const priceError = ref('')
const loadingData = ref(true)

const form = ref({
  category_id: '',
  code: '',
  barcode: '',
  name: '',
  purchase_price: 0,
  selling_price: 0,
  stock: 0,
  min_stock: 0,
  description: ''
})

const categoryOptions = computed(() => {
  return categoryStore.categories.map(c => ({
    value: c.id,
    label: c.name
  }))
})

onMounted(async () => {
  try {
    await categoryStore.fetchAll()
    // Ambil data detail produk saat ini
    const data = await productStore.fetchById(route.params.id)
    form.value = { ...data }
  } finally {
    loadingData.value = false
  }
})

const handleCancel = () => {
  router.push('/admin/products')
}

const handleSubmit = async () => {
  priceError.value = ''

  if (form.value.selling_price < form.value.purchase_price) {
    priceError.value = 'Aturan Bisnis: Harga jual tidak boleh lebih rendah dari harga beli pokok.'
    return
  }

  try {
    await productStore.updateProduct(route.params.id, form.value)
    router.push('/admin/products')
  } catch (err) {
    // Error Handling
  }
}
</script>