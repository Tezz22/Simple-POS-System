<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-950 font-sans">
    <!-- ═══════════════════════════════════════════════════════
         STICKY HEADER
    ═══════════════════════════════════════════════════════ -->
    <div
      class="sticky top-0 z-30 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm border-b border-gray-200 dark:border-gray-700 shadow-xs"
    >
      <div class="max-w-[1440px] mx-auto px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-lg bg-teal-600 flex items-center justify-center shrink-0">
            <Icon icon="heroicons:beaker-solid" class="w-4.5 h-4.5 text-white" />
          </div>
          <div>
            <h1
              class="text-sm font-black text-gray-900 dark:text-white tracking-tight leading-none"
            >
              Component Lab
            </h1>
            <p class="text-[11px] text-gray-400 font-medium leading-none mt-0.5">
              {{ totalComponents }} komponen diuji — {{ activeTab }} aktif
            </p>
          </div>
        </div>
        <ThemeToggle />
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════════════════════════ -->
    <div class="max-w-[1440px] mx-auto p-6">
      <BaseTabs v-model="activeTab" :tabs="tabs">
        <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
             TAB 1 — STATUS & BADGE
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
        <div v-show="activeTab === 'status'" class="space-y-5">
          <!-- BadgeStatus -->
          <SectionCard title="BadgeStatus">
            <div class="flex flex-wrap gap-3 items-center">
              <BadgeStatus status="paid" />
              <BadgeStatus status="draft" />
              <BadgeStatus status="cancelled" />
              <BadgeStatus status="active" />
              <BadgeStatus status="inactive" />
              <span class="text-xs text-gray-300 dark:text-gray-600">|</span>
              <BadgeStatus status="paid" size="sm" />
              <BadgeStatus status="cancelled" size="sm" />
            </div>
          </SectionCard>

          <!-- RoleBadge -->
          <SectionCard title="RoleBadge">
            <div class="flex gap-3 items-center">
              <RoleBadge role="admin" />
              <RoleBadge role="kasir" />
            </div>
          </SectionCard>

          <!-- TransactionStatusBadge -->
          <SectionCard title="TransactionStatusBadge">
            <div class="flex flex-wrap gap-3 items-center">
              <TransactionStatusBadge status="paid" />
              <TransactionStatusBadge status="draft" />
              <TransactionStatusBadge status="pending" />
              <TransactionStatusBadge status="cancelled" />
            </div>
          </SectionCard>

          <!-- StockIndicator -->
          <SectionCard title="StockIndicator">
            <div class="grid grid-cols-3 gap-8 max-w-lg">
              <div class="space-y-2">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                  Stok Aman
                </p>
                <StockIndicator :stock="20" :minStock="5" />
              </div>
              <div class="space-y-2">
                <p class="text-[10px] text-amber-500 font-bold uppercase tracking-wider">
                  Stok Menipis
                </p>
                <StockIndicator :stock="3" :minStock="5" />
              </div>
              <div class="space-y-2">
                <p class="text-[10px] text-red-500 font-bold uppercase tracking-wider">
                  Stok Habis
                </p>
                <StockIndicator :stock="0" :minStock="5" />
              </div>
            </div>
          </SectionCard>

          <!-- Breadcrumb -->
          <SectionCard title="Breadcrumb">
            <div class="space-y-3">
              <Breadcrumb :items="[{ label: 'Dashboard', to: '/' }, { label: 'Produk' }]" />
              <Breadcrumb
                :items="[
                  { label: 'Dashboard', to: '/' },
                  { label: 'Karyawan', to: '/admin/karyawan' },
                  { label: 'Edit Karyawan' },
                ]"
              />
            </div>
          </SectionCard>
        </div>

        <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
             TAB 2 — ALERT
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
        <div v-show="activeTab === 'alert'" class="space-y-5">
          <SectionCard title="BaseAlert — Semua Varian">
            <div class="space-y-3">
              <BaseAlert variant="success">
                Transaksi <strong>TRX-001</strong> berhasil disimpan! Struk siap dicetak.
              </BaseAlert>
              <BaseAlert variant="error">
                Gagal menghubungi server API. Periksa koneksi internet Anda dan coba lagi.
              </BaseAlert>
              <BaseAlert variant="warning">
                Stok produk <strong>Nasi Goreng Special</strong> hampir habis (sisa 3 porsi). Segera
                lakukan pengisian stok.
              </BaseAlert>
              <BaseAlert variant="info">
                Sistem akan melakukan pemeliharaan pada pukul 02.00 WIB. Harap selesaikan transaksi
                sebelum waktu tersebut.
              </BaseAlert>
            </div>
          </SectionCard>

          <SectionCard title="BaseAlert — Dismissible (dapat ditutup)">
            <div class="space-y-3">
              <BaseAlert variant="success" :dismissible="true" @close="() => {}">
                Alert ini bisa ditutup. Klik tombol × di kanan untuk menutupnya.
              </BaseAlert>
              <BaseAlert variant="warning" :dismissible="true">
                Sesi Anda akan berakhir dalam 5 menit. Simpan pekerjaan Anda.
              </BaseAlert>
              <BaseAlert variant="error" :dismissible="true">
                Koneksi terputus. Data belum tersimpan ke server.
              </BaseAlert>
            </div>
          </SectionCard>
        </div>

        <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
             TAB 3 — TOMBOL
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
        <div v-show="activeTab === 'tombol'" class="space-y-5">
          <!-- BaseButton -->
          <SectionCard title="BaseButton — Varian">
            <div class="flex flex-wrap gap-3 items-center">
              <BaseButton variant="primary">Primary</BaseButton>
              <BaseButton variant="secondary">Secondary</BaseButton>
              <BaseButton variant="danger">Danger</BaseButton>
              <BaseButton variant="outline">Outline</BaseButton>
              <BaseButton variant="ghost">Ghost</BaseButton>
            </div>
          </SectionCard>

          <SectionCard title="BaseButton — Ukuran">
            <div class="flex flex-wrap gap-3 items-end">
              <BaseButton size="lg">Besar (lg)</BaseButton>
              <BaseButton size="md">Sedang (md)</BaseButton>
              <BaseButton size="sm">Kecil (sm)</BaseButton>
            </div>
          </SectionCard>

          <SectionCard title="BaseButton — State Khusus">
            <div class="space-y-3">
              <div class="flex flex-wrap gap-3 items-center">
                <BaseButton :loading="true">Sedang Menyimpan…</BaseButton>
                <BaseButton :disabled="true">Tidak Aktif</BaseButton>
                <BaseButton variant="danger" :loading="true">Menghapus…</BaseButton>
              </div>
              <BaseButton :w-full="true" variant="primary">Full Width (wFull)</BaseButton>
            </div>
          </SectionCard>

          <!-- IconButton -->
          <SectionCard title="IconButton — Varian & Ukuran">
            <div class="space-y-4">
              <div class="flex flex-wrap gap-3 items-center">
                <IconButton variant="primary" tooltip="Tambah">
                  <Icon icon="heroicons:plus" class="w-4 h-4" />
                </IconButton>
                <IconButton variant="secondary" tooltip="Edit">
                  <Icon icon="heroicons:pencil-square" class="w-4 h-4" />
                </IconButton>
                <IconButton variant="danger" tooltip="Hapus">
                  <Icon icon="heroicons:trash" class="w-4 h-4" />
                </IconButton>
                <IconButton variant="outline" tooltip="Lihat Detail">
                  <Icon icon="heroicons:eye" class="w-4 h-4" />
                </IconButton>
                <IconButton variant="ghost" tooltip="Opsi Lainnya">
                  <Icon icon="heroicons:ellipsis-horizontal" class="w-4 h-4" />
                </IconButton>
              </div>
              <div class="flex flex-wrap gap-3 items-end">
                <div class="text-center space-y-1">
                  <p class="text-[10px] text-gray-400">sm</p>
                  <IconButton size="sm" tooltip="Kecil">
                    <Icon icon="heroicons:magnifying-glass" class="w-3.5 h-3.5" />
                  </IconButton>
                </div>
                <div class="text-center space-y-1">
                  <p class="text-[10px] text-gray-400">md</p>
                  <IconButton size="md" tooltip="Sedang">
                    <Icon icon="heroicons:magnifying-glass" class="w-4 h-4" />
                  </IconButton>
                </div>
                <div class="text-center space-y-1">
                  <p class="text-[10px] text-gray-400">lg</p>
                  <IconButton size="lg" tooltip="Besar">
                    <Icon icon="heroicons:magnifying-glass" class="w-5 h-5" />
                  </IconButton>
                </div>
                <div class="text-center space-y-1">
                  <p class="text-[10px] text-gray-400">loading</p>
                  <IconButton :loading="true" tooltip="Memuat..." />
                </div>
                <div class="text-center space-y-1">
                  <p class="text-[10px] text-gray-400">disabled</p>
                  <IconButton :disabled="true" tooltip="Nonaktif">
                    <Icon icon="heroicons:plus" class="w-4 h-4" />
                  </IconButton>
                </div>
              </div>
            </div>
          </SectionCard>

          <!-- ButtonGroup -->
          <SectionCard title="ButtonGroup — Kelompok Tombol">
            <div class="space-y-4">
              <ButtonGroup>
                <BaseButton variant="outline" size="sm">Hari Ini</BaseButton>
                <BaseButton variant="primary" size="sm">Minggu Ini</BaseButton>
                <BaseButton variant="outline" size="sm">Bulan Ini</BaseButton>
              </ButtonGroup>
              <ButtonGroup>
                <BaseButton variant="secondary" size="sm">
                  <Icon icon="heroicons:list-bullet" class="w-3.5 h-3.5 mr-1.5" />
                  List
                </BaseButton>
                <BaseButton variant="secondary" size="sm">
                  <Icon icon="heroicons:squares-2x2" class="w-3.5 h-3.5 mr-1.5" />
                  Grid
                </BaseButton>
              </ButtonGroup>
            </div>
          </SectionCard>
        </div>

        <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
             TAB 4 — FORM
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
        <div v-show="activeTab === 'form'" class="space-y-5">
          <!-- BaseInput -->
          <SectionCard title="BaseInput">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <BaseInput
                v-model="demoInput"
                label="Nama Produk"
                placeholder="Masukkan nama produk..."
              />
              <BaseInput
                v-model="demoInput"
                label="Dengan Error"
                error="Nama produk wajib diisi!"
              />
              <BaseInput
                v-model="demoInput"
                label="Disabled"
                :disabled="true"
                placeholder="Tidak dapat diubah"
              />
              <BaseInput
                v-model="demoInputNumber"
                label="Type Number"
                type="number"
                placeholder="0"
              />
            </div>
          </SectionCard>

          <!-- BaseSelect -->
          <SectionCard title="BaseSelect">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <BaseSelect
                v-model="demoSelect"
                label="Kategori Produk"
                placeholder="Pilih kategori..."
                :options="selectOptions"
              />
              <BaseSelect
                v-model="demoSelect"
                label="Dengan Error"
                error="Kategori wajib dipilih!"
                :options="selectOptions"
              />
              <BaseSelect
                v-model="demoSelect"
                label="Disabled"
                :disabled="true"
                placeholder="Terkunci"
                :options="selectOptions"
              />
            </div>
          </SectionCard>

          <!-- BaseTextarea -->
          <SectionCard title="BaseTextarea">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-1">
                <p class="text-[11px] font-bold text-gray-400 uppercase">Normal</p>
                <BaseTextarea v-model="demoTextarea" placeholder="Deskripsi produk..." :rows="3" />
              </div>
              <div class="space-y-1">
                <p class="text-[11px] font-bold text-gray-400 uppercase">
                  Dengan Counter (maks 120)
                </p>
                <BaseTextarea v-model="demoTextarea" :maxlength="120" :showCount="true" :rows="3" />
              </div>
              <div class="space-y-1">
                <p class="text-[11px] font-bold text-gray-400 uppercase">Disabled</p>
                <BaseTextarea v-model="demoTextarea" :disabled="true" :rows="3" />
              </div>
            </div>
          </SectionCard>

          <!-- BaseCheckbox -->
          <SectionCard title="BaseCheckbox">
            <div class="flex flex-wrap gap-6 items-center">
              <BaseCheckbox v-model="check1" label="Centang saya" />
              <BaseCheckbox :model-value="true" label="Sudah tercentang" />
              <BaseCheckbox :model-value="false" label="Disabled (off)" :disabled="true" />
              <BaseCheckbox :model-value="true" label="Disabled (on)" :disabled="true" />
              <BaseCheckbox v-model="check1" />
            </div>
          </SectionCard>

          <!-- BaseRadio -->
          <SectionCard title="BaseRadio">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase mb-3">
                  Aktif — pilihan: {{ demoRadio }}
                </p>
                <BaseRadio v-model="demoRadio" :options="radioOptions" />
              </div>
              <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase mb-3">Disabled</p>
                <BaseRadio v-model="demoRadio" :options="radioOptions" :disabled="true" />
              </div>
            </div>
          </SectionCard>

          <!-- SearchInput -->
          <SectionCard title="SearchInput">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <SearchInput v-model="demoSearch" placeholder="Cari nama produk..." />
              <SearchInput
                :model-value="''"
                :disabled="true"
                placeholder="Pencarian dinonaktifkan"
              />
            </div>
          </SectionCard>

          <!-- FormGroup -->
          <SectionCard title="FormGroup — Pembungkus Label, Hint & Error">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <FormGroup label="Alamat Email" hint="Contoh: nama@perusahaan.com">
                <BaseInput v-model="demoInput" placeholder="Ketik email..." />
              </FormGroup>
              <FormGroup label="Password" error="Password minimal 8 karakter" :required="true">
                <BaseInput v-model="demoInput" type="password" placeholder="••••••••" />
              </FormGroup>
              <FormGroup label="Kategori" :required="true">
                <BaseSelect v-model="demoSelect" :options="selectOptions" placeholder="Pilih..." />
              </FormGroup>
              <FormGroup label="Catatan Pesanan" hint="Opsional — maksimal 200 karakter">
                <BaseTextarea v-model="demoTextarea" :rows="2" :maxlength="200" :showCount="true" />
              </FormGroup>
            </div>
          </SectionCard>
        </div>

        <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
             TAB 5 — DATA & TABEL
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
        <div v-show="activeTab === 'data'" class="space-y-5">
          <!-- DataCard -->
          <SectionCard title="DataCard — Semua Warna & Tren">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
              <DataCard
                label="Total Pendapatan"
                value="Rp 12,5 Jt"
                icon="heroicons:currency-dollar-solid"
                color="primary"
                :trend="{ value: 12, direction: 'up' }"
              />
              <DataCard
                label="Transaksi Sukses"
                value="48"
                icon="heroicons:shopping-cart-solid"
                color="success"
                :trend="{ value: 5, direction: 'up' }"
              />
              <DataCard
                label="Produk Menipis"
                value="7"
                icon="heroicons:archive-box-solid"
                color="warning"
                :trend="{ value: 3, direction: 'down' }"
              />
              <DataCard
                label="Transaksi Batal"
                value="2"
                icon="heroicons:x-circle-solid"
                color="danger"
              />
              <DataCard
                label="Pengguna Aktif"
                value="15"
                icon="heroicons:users-solid"
                color="info"
              />
            </div>
          </SectionCard>

          <!-- DetailRow -->
          <SectionCard title="DetailRow — Baris Detail Informasi">
            <DetailRow label="Nama Produk" value="Nasi Goreng Special" />
            <DetailRow label="Kategori" value="Makanan" />
            <DetailRow label="Harga Jual" value="Rp 25.000" />
            <DetailRow label="Stok Tersedia" value="18 Porsi" />
            <DetailRow label="Status Produk">
              <BadgeStatus status="active" size="sm" />
            </DetailRow>
            <DetailRow label="Role Kasir">
              <RoleBadge role="kasir" />
            </DetailRow>
          </SectionCard>

          <!-- SectionCard dengan Header Action -->
          <SectionCard title="SectionCard — Dengan Slot Header Action">
            <template #header-action>
              <SearchInput v-model="demoSearch" placeholder="Cari..." />
              <BaseButton size="sm">
                <Icon icon="heroicons:plus" class="w-3.5 h-3.5 mr-1" />
                Tambah
              </BaseButton>
            </template>
            <p class="text-sm text-textSecondary dark:text-gray-400">
              Ini adalah konten di dalam SectionCard. Slot
              <code class="bg-gray-100 dark:bg-gray-800 px-1 rounded text-xs">#header-action</code>
              diisi SearchInput dan BaseButton di pojok kanan atas card.
            </p>
          </SectionCard>

          <!-- BaseTabs (nested demo) -->
          <SectionCard title="BaseTabs — Demo Nested">
            <BaseTabs v-model="nestedTab" :tabs="nestedTabs">
              <div v-show="nestedTab === 'semua'">
                <p class="text-sm text-textSecondary dark:text-gray-400 py-2">
                  Menampilkan semua produk (15 item)
                </p>
              </div>
              <div v-show="nestedTab === 'makanan'">
                <p class="text-sm text-textSecondary dark:text-gray-400 py-2">
                  Menampilkan produk kategori Makanan (8 item)
                </p>
              </div>
              <div v-show="nestedTab === 'minuman'">
                <p class="text-sm text-textSecondary dark:text-gray-400 py-2">
                  Menampilkan produk kategori Minuman (7 item)
                </p>
              </div>
            </BaseTabs>
          </SectionCard>

          <!-- BaseTable -->
          <SectionCard title="BaseTable">
            <div class="flex flex-wrap gap-2 mb-4">
              <BaseButton size="sm" variant="outline" @click="tableLoading = !tableLoading">
                <Icon
                  :icon="tableLoading ? 'heroicons:stop' : 'heroicons:arrow-path'"
                  class="w-3.5 h-3.5 mr-1.5"
                />
                {{ tableLoading ? 'Hentikan Loading' : 'Toggle Loading' }}
              </BaseButton>
              <BaseButton size="sm" variant="outline" @click="tableEmpty = !tableEmpty">
                <Icon
                  :icon="tableEmpty ? 'heroicons:table-cells' : 'heroicons:inbox'"
                  class="w-3.5 h-3.5 mr-1.5"
                />
                {{ tableEmpty ? 'Tampilkan Data' : 'Kosongkan Tabel' }}
              </BaseButton>
            </div>
            <BaseTable
              :columns="tableColumns"
              :rows="tableEmpty ? [] : tableRows"
              :loading="tableLoading"
              @sort="handleSort"
            />
          </SectionCard>

          <!-- Pagination & TablePagination -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <SectionCard title="Pagination">
              <Pagination
                :currentPage="paginationPage"
                :totalPages="12"
                @pageChange="paginationPage = $event"
              />
              <p class="text-xs text-center text-gray-400 mt-2">
                Halaman aktif: {{ paginationPage }} / 12
              </p>
            </SectionCard>

            <SectionCard title="TablePagination">
              <TablePagination
                :currentPage="tablePage"
                :totalItems="87"
                :perPage="10"
                @pageChange="tablePage = $event"
              />
            </SectionCard>
          </div>
        </div>

        <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
             TAB 6 — LOADING & STATE
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
        <div v-show="activeTab === 'loading'" class="space-y-5">
          <!-- LoadingSpinner -->
          <SectionCard title="LoadingSpinner — Semua Ukuran">
            <div class="flex gap-12 items-end">
              <div class="flex flex-col items-center gap-2">
                <LoadingSpinner size="sm" />
                <span class="text-[11px] text-gray-400 font-bold">sm</span>
              </div>
              <div class="flex flex-col items-center gap-2">
                <LoadingSpinner size="md" />
                <span class="text-[11px] text-gray-400 font-bold">md</span>
              </div>
              <div class="flex flex-col items-center gap-2">
                <LoadingSpinner size="lg" />
                <span class="text-[11px] text-gray-400 font-bold">lg</span>
              </div>
              <div class="flex flex-col items-center gap-2">
                <LoadingSpinner size="lg" label="Memuat data produk..." />
                <span class="text-[11px] text-gray-400 font-bold">dengan label</span>
              </div>
            </div>
          </SectionCard>

          <!-- LoadingOverlay -->
          <SectionCard title="LoadingOverlay">
            <div class="flex flex-wrap gap-3">
              <BaseButton @click="triggerOverlay(false)">
                <Icon icon="heroicons:eye" class="w-4 h-4 mr-1.5" />
                Overlay Normal (2.5 dtk)
              </BaseButton>
              <BaseButton variant="secondary" @click="triggerOverlay(true)">
                <Icon icon="heroicons:eye" class="w-4 h-4 mr-1.5" />
                Overlay + Blur (2.5 dtk)
              </BaseButton>
            </div>
            <p class="text-xs text-gray-400 mt-2">
              Overlay menutupi seluruh layar, otomatis hilang setelah 2,5 detik.
            </p>
          </SectionCard>

          <!-- EmptyState -->
          <SectionCard title="EmptyState">
            <EmptyState
              title="Belum Ada Produk"
              description="Mulailah dengan menambahkan produk pertama Anda ke dalam sistem kasir."
            >
              <template #icon>
                <Icon
                  icon="heroicons:archive-box"
                  class="w-14 h-14 text-gray-300 dark:text-gray-600"
                />
              </template>
              <template #action>
                <BaseButton size="sm">
                  <Icon icon="heroicons:plus" class="w-3.5 h-3.5 mr-1.5" />
                  Tambah Produk Pertama
                </BaseButton>
              </template>
            </EmptyState>
          </SectionCard>

          <!-- ErrorState -->
          <SectionCard title="ErrorState">
            <ErrorState
              message="Tidak dapat memuat daftar produk. Pastikan koneksi internet Anda aktif dan coba lagi."
              @retry="handleRetry"
            />
          </SectionCard>
        </div>

        <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
             TAB 7 — DIALOG & MODAL
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
        <div v-show="activeTab === 'dialog'" class="space-y-5">
          <!-- BaseModal -->
          <SectionCard title="BaseModal — Tiga Ukuran">
            <div class="flex flex-wrap gap-3">
              <BaseButton @click="showModal = true">
                <Icon icon="heroicons:rectangle-stack" class="w-4 h-4 mr-1.5" />
                Modal Medium (md)
              </BaseButton>
              <BaseButton variant="secondary" @click="showModalSm = true">
                <Icon icon="heroicons:rectangle-stack" class="w-4 h-4 mr-1.5" />
                Modal Kecil (sm)
              </BaseButton>
              <BaseButton variant="secondary" @click="showModalLg = true">
                <Icon icon="heroicons:rectangle-stack" class="w-4 h-4 mr-1.5" />
                Modal Besar (lg)
              </BaseButton>
            </div>
          </SectionCard>

          <!-- ConfirmDialog -->
          <SectionCard title="ConfirmDialog — Dua Varian">
            <div class="flex flex-wrap gap-3">
              <BaseButton variant="danger" @click="showConfirmDanger = true">
                <Icon icon="heroicons:trash" class="w-4 h-4 mr-1.5" />
                Konfirmasi Hapus (danger)
              </BaseButton>
              <BaseButton variant="secondary" @click="showConfirmWarning = true">
                <Icon icon="heroicons:exclamation-triangle" class="w-4 h-4 mr-1.5" />
                Konfirmasi Aksi (warning)
              </BaseButton>
            </div>
          </SectionCard>
        </div>

        <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
             TAB 8 — TRANSAKSI
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
        <div v-show="activeTab === 'transaksi'" class="space-y-5">
          <!-- ProductCard -->
          <SectionCard title="ProductCard — Grid (ada stok & habis)">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
              <ProductCard
                v-for="p in demoProducts"
                :key="p.id"
                :product="p"
                @addToCart="handleAddToCart"
              />
            </div>
          </SectionCard>

          <!-- CartItem + CartSummary -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <SectionCard title="CartItem — Keranjang Belanja">
              <CartItem
                v-for="item in cartItems"
                :key="item.id"
                :item="item"
                @increaseQty="increaseQty"
                @decreaseQty="decreaseQty"
                @removeItem="removeItem"
              />
              <EmptyState
                v-if="cartItems.length === 0"
                title="Keranjang Kosong"
                description="Tambahkan produk dari grid di atas."
              >
                <template #icon>
                  <Icon
                    icon="heroicons:shopping-cart"
                    class="w-10 h-10 text-gray-300 dark:text-gray-600"
                  />
                </template>
              </EmptyState>
              <CartSummary :subtotal="cartSubtotal" class="mt-2" />
            </SectionCard>

            <SectionCard title="PaymentForm — Kasir Input">
              <PaymentForm :grandTotal="cartTotal" @pay="handlePay" />
            </SectionCard>
          </div>

          <!-- ReceiptCard -->
          <SectionCard title="ReceiptCard — Struk Transaksi">
            <ReceiptCard
              :transaction="receiptTransaction"
              :items="receiptItems"
              :cashier="receiptCashier"
            />
          </SectionCard>
        </div>

        <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
             TAB 9 — LAYOUT
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
        <div v-show="activeTab === 'layout'" class="space-y-5">
          <!-- ThemeToggle -->
          <SectionCard title="ThemeToggle">
            <div class="flex items-center gap-4">
              <ThemeToggle />
              <p class="text-sm text-textSecondary dark:text-gray-400">
                Klik untuk berganti tema terang / gelap. Preferensi disimpan di
                <code class="bg-gray-100 dark:bg-gray-800 px-1 rounded text-xs">localStorage</code>.
              </p>
            </div>
          </SectionCard>

          <!-- ToastContainer -->
          <SectionCard title="ToastContainer — Trigger Notifikasi">
            <div class="flex flex-wrap gap-3">
              <BaseButton
                variant="primary"
                @click="showToast('success', 'Data produk berhasil disimpan!')"
              >
                <Icon icon="heroicons:check-circle" class="w-4 h-4 mr-1.5" />
                Toast Success
              </BaseButton>
              <BaseButton variant="danger" @click="showToast('error', 'Koneksi server terputus!')">
                <Icon icon="heroicons:x-circle" class="w-4 h-4 mr-1.5" />
                Toast Error
              </BaseButton>
              <BaseButton
                variant="secondary"
                @click="showToast('warning', 'Stok produk hampir habis!')"
              >
                <Icon icon="heroicons:exclamation-triangle" class="w-4 h-4 mr-1.5" />
                Toast Warning
              </BaseButton>
              <BaseButton
                variant="outline"
                @click="showToast('info', 'Sistem diperbarui ke versi 2.1')"
              >
                <Icon icon="heroicons:information-circle" class="w-4 h-4 mr-1.5" />
                Toast Info
              </BaseButton>
            </div>
            <p class="text-xs text-gray-400 mt-3">Toast muncul di pojok kanan bawah layar.</p>
          </SectionCard>

          <!-- Sidebar Preview (CSS transform containment trick) -->
          <SectionCard title="Sidebar — Preview dalam Container (transform containment)">
            <div
              class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden"
              style="height: 400px; transform: translate3d(0, 0, 0)"
            >
              <Sidebar
                :isOpen="sidebarOpen"
                :menuItems="testMenuItems"
                @close="sidebarOpen = false"
                @toggle-collapse="sidebarOpen = !sidebarOpen"
              />
              <div
                :class="[
                  'h-full bg-gray-50 dark:bg-gray-900 flex flex-col justify-center px-8 transition-all duration-300',
                  sidebarOpen ? 'ml-64' : 'ml-20',
                ]"
              >
                <p class="text-sm font-bold text-textPrimary dark:text-white">Area Konten Utama</p>
                <p class="text-xs text-gray-400 mt-1">
                  Sidebar:
                  <strong>{{ sidebarOpen ? 'Terbuka (w-64)' : 'Collapsed (w-20)' }}</strong>
                </p>
                <div class="flex gap-2 mt-4">
                  <BaseButton size="sm" variant="outline" @click="sidebarOpen = !sidebarOpen">
                    <Icon icon="heroicons:bars-3" class="w-3.5 h-3.5 mr-1.5" />
                    Toggle Sidebar
                  </BaseButton>
                </div>
              </div>
            </div>
          </SectionCard>

          <!-- TopBar Preview -->
          <SectionCard title="TopBar — Preview dalam Container">
            <div
              class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden"
              style="height: 64px; transform: translate3d(0, 0, 0)"
            >
              <TopBar @toggleSidebar="sidebarOpen = !sidebarOpen">
                <template #page-title>
                  <span class="text-xs font-medium text-gray-400">Halaman</span>
                  <h2 class="text-sm font-bold text-gray-900 dark:text-white">Dashboard Kasir</h2>
                </template>
              </TopBar>
            </div>
            <p class="text-xs text-gray-400 mt-2">
              TopBar menggunakan
              <code class="bg-gray-100 dark:bg-gray-800 px-1 rounded">useAuthStore</code> — tampil
              sesuai data user yang sedang login.
            </p>
          </SectionCard>

          <!-- AppLayout Info -->
          <SectionCard title="AppLayout — Catatan Penggunaan">
            <BaseAlert variant="info">
              <strong>AppLayout</strong> adalah komponen wrapper halaman utama yang menggabungkan
              <strong>Sidebar</strong> + <strong>TopBar</strong> + slot konten. Tidak dapat
              di-preview secara inline karena bersifat full-page layout. Gunakan sebagai wrapper di
              router view:
              <code
                class="block mt-2 bg-blue-100/50 dark:bg-blue-900/20 px-3 py-2 rounded-lg text-xs font-mono"
              >
                &lt;AppLayout&gt; &lt;RouterView /&gt; &lt;/AppLayout&gt;
              </code>
            </BaseAlert>
          </SectionCard>
        </div>
      </BaseTabs>
    </div>

    <!-- ═══════════════════════════════════════════════════════
         GLOBAL OVERLAYS & DIALOGS
    ═══════════════════════════════════════════════════════ -->

    <!-- Toast -->
    <ToastContainer />

    <!-- Loading Overlays -->
    <LoadingOverlay :show="showOverlay" message="Memuat data sistem…" />
    <LoadingOverlay :show="showBlurOverlay" message="Memproses permintaan…" :blur="true" />

    <!-- Base Modals -->
    <BaseModal :show="showModal" title="Contoh Modal — Medium (md)" @close="showModal = false">
      <div class="space-y-4">
        <p class="text-sm leading-relaxed">
          Ini adalah konten modal berukuran medium. Anda bisa menempatkan form, informasi, atau
          tabel ringkas di dalam area ini.
        </p>
        <BaseInput
          v-model="modalInput"
          label="Contoh Input di Dalam Modal"
          placeholder="Ketik sesuatu..."
        />
        <BaseCheckbox v-model="check1" label="Saya menyetujui syarat dan ketentuan" />
      </div>
      <template #footer>
        <BaseButton variant="outline" size="sm" @click="showModal = false">Batal</BaseButton>
        <BaseButton size="sm" @click="showModal = false">Simpan Perubahan</BaseButton>
      </template>
    </BaseModal>

    <BaseModal :show="showModalSm" title="Modal Kecil (sm)" size="sm" @close="showModalSm = false">
      <p class="text-sm">
        Pesan konfirmasi singkat atau notifikasi penting dapat ditampilkan di sini.
      </p>
      <template #footer>
        <BaseButton size="sm" @click="showModalSm = false">OK, Mengerti</BaseButton>
      </template>
    </BaseModal>

    <BaseModal
      :show="showModalLg"
      title="Modal Besar (lg) — Preview Tabel"
      size="lg"
      @close="showModalLg = false"
    >
      <div class="space-y-4">
        <p class="text-sm text-textSecondary dark:text-gray-400">
          Modal berukuran besar cocok untuk tabel data, form detail, atau preview dokumen.
        </p>
        <BaseTable :columns="tableColumns" :rows="tableRows" />
      </div>
      <template #footer>
        <BaseButton variant="outline" size="sm" @click="showModalLg = false">Tutup</BaseButton>
        <BaseButton size="sm">Ekspor Data</BaseButton>
      </template>
    </BaseModal>

    <!-- Confirm Dialogs -->
    <ConfirmDialog
      :show="showConfirmDanger"
      title="Hapus Produk Ini?"
      message="Produk 'Nasi Goreng Special' akan dihapus secara permanen."
      subMessage="Data yang sudah dihapus tidak dapat dikembalikan."
      variant="danger"
      confirmText="Ya, Hapus Sekarang"
      cancelText="Batal"
      @confirm="handleConfirmDanger"
      @cancel="showConfirmDanger = false"
    />

    <ConfirmDialog
      :show="showConfirmWarning"
      title="Konfirmasi Proses"
      message="Apakah Anda yakin ingin memproses seluruh transaksi pending?"
      subMessage="Pastikan semua data sudah dicek sebelum melanjutkan."
      variant="warning"
      confirmText="Ya, Proses Sekarang"
      cancelText="Tunda"
      @confirm="handleConfirmWarning"
      @cancel="showConfirmWarning = false"
    />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Icon } from '@iconify/vue'

// ─── Layout ───────────────────────────────────────────────
import Sidebar from '@/components/layout/Sidebar.vue'
import TopBar from '@/components/layout/TopBar.vue'

// ─── Shared ───────────────────────────────────────────────
import CartItem from '@/components/shared/CartItem.vue'
import CartSummary from '@/components/shared/CartSummary.vue'
import PaymentForm from '@/components/shared/PaymentForm.vue'
import ProductCard from '@/components/shared/ProductCard.vue'
import ReceiptCard from '@/components/shared/ReceiptCard.vue'
import RoleBadge from '@/components/shared/RoleBadge.vue'
import StockIndicator from '@/components/shared/StockIndicator.vue'
import TransactionStatusBadge from '@/components/shared/TransactionStatusBadge.vue'

// ─── UI ───────────────────────────────────────────────────
import BadgeStatus from '@/components/ui/BadgeStatus.vue'
import BaseAlert from '@/components/ui/BaseAlert.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import BaseCheckbox from '@/components/ui/BaseCheckbox.vue'
import BaseInput from '@/components/ui/BaseInput.vue'
import BaseModal from '@/components/ui/BaseModal.vue'
import BaseRadio from '@/components/ui/BaseRadio.vue'
import BaseSelect from '@/components/ui/BaseSelect.vue'
import BaseTable from '@/components/ui/BaseTable.vue'
import BaseTabs from '@/components/ui/BaseTabs.vue'
import BaseTextarea from '@/components/ui/BaseTextarea.vue'
import Breadcrumb from '@/components/ui/Breadcrumb.vue'
import ButtonGroup from '@/components/ui/ButtonGroup.vue'
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue'
import DataCard from '@/components/ui/DataCard.vue'
import DetailRow from '@/components/ui/DetailRow.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import ErrorState from '@/components/ui/ErrorState.vue'
import FormGroup from '@/components/ui/FormGroup.vue'
import IconButton from '@/components/ui/IconButton.vue'
import LoadingOverlay from '@/components/ui/LoadingOverlay.vue'
import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'
import Pagination from '@/components/ui/Pagination.vue'
import SearchInput from '@/components/ui/SearchInput.vue'
import SectionCard from '@/components/ui/SectionCard.vue'
import TablePagination from '@/components/ui/TablePagination.vue'
import ToastContainer from '@/components/ui/ToastContainer.vue'

// ─── Other ────────────────────────────────────────────────
import ThemeToggle from '@/components/ThemeToggle.vue'

// ─── Stores ───────────────────────────────────────────────
import { useToastStore } from '@/stores/toast'
const toastStore = useToastStore()

// ════════════════════════════════════════════════════════
// NAVIGASI UTAMA
// ════════════════════════════════════════════════════════

const totalComponents = 43
const activeTab = ref('status')

const tabs = [
  { value: 'status', label: 'Status & Badge' },
  { value: 'alert', label: 'Alert' },
  { value: 'tombol', label: 'Tombol' },
  { value: 'form', label: 'Form' },
  { value: 'data', label: 'Data & Tabel' },
  { value: 'loading', label: 'Loading & State' },
  { value: 'dialog', label: 'Dialog & Modal' },
  { value: 'transaksi', label: 'Transaksi' },
  { value: 'layout', label: 'Layout' },
]

// ════════════════════════════════════════════════════════
// FORM STATE
// ════════════════════════════════════════════════════════

const demoInput = ref('Nasi Goreng Special')
const demoInputNumber = ref(25000)
const demoSelect = ref('')
const demoTextarea = ref('Produk andalan dengan cita rasa autentik...')
const demoSearch = ref('goreng')
const demoRadio = ref('makanan')
const check1 = ref(false)
const modalInput = ref('')

const selectOptions = [
  { value: 'makanan', label: 'Makanan' },
  { value: 'minuman', label: 'Minuman' },
  { value: 'snack', label: 'Snack & Camilan' },
]

const radioOptions = [
  { value: 'makanan', label: 'Makanan Berat' },
  { value: 'minuman', label: 'Minuman Segar' },
  { value: 'snack', label: 'Camilan Ringan' },
]

// ════════════════════════════════════════════════════════
// TABEL
// ════════════════════════════════════════════════════════

const tableLoading = ref(false)
const tableEmpty = ref(false)

const tableColumns = [
  { key: 'name', label: 'Nama Produk', sortable: true },
  { key: 'category', label: 'Kategori' },
  { key: 'price', label: 'Harga Jual' },
  { key: 'stock', label: 'Stok', sortable: true },
  { key: 'status', label: 'Status' },
]

const tableRows = [
  {
    id: 1,
    name: 'Nasi Goreng Special',
    category: 'Makanan',
    price: 'Rp 25.000',
    stock: 15,
    status: 'active',
  },
  {
    id: 2,
    name: 'Es Teh Manis',
    category: 'Minuman',
    price: 'Rp 8.000',
    stock: 3,
    status: 'active',
  },
  {
    id: 3,
    name: 'Ayam Bakar Madu',
    category: 'Makanan',
    price: 'Rp 35.000',
    stock: 0,
    status: 'inactive',
  },
  {
    id: 4,
    name: 'Jus Alpukat Susu',
    category: 'Minuman',
    price: 'Rp 18.000',
    stock: 12,
    status: 'active',
  },
  {
    id: 5,
    name: 'Keripik Tempe Pedas',
    category: 'Snack',
    price: 'Rp 12.000',
    stock: 8,
    status: 'active',
  },
]

const handleSort = (key) => {
  console.log('[BaseTable] Sort kolom:', key)
}

// ════════════════════════════════════════════════════════
// PAGINATION
// ════════════════════════════════════════════════════════

const paginationPage = ref(3)
const tablePage = ref(1)

// ════════════════════════════════════════════════════════
// NESTED TABS (demo di seksi data)
// ════════════════════════════════════════════════════════

const nestedTab = ref('semua')
const nestedTabs = [
  { value: 'semua', label: 'Semua Produk' },
  { value: 'makanan', label: 'Makanan' },
  { value: 'minuman', label: 'Minuman' },
]

// ════════════════════════════════════════════════════════
// OVERLAYS & DIALOGS
// ════════════════════════════════════════════════════════

const showOverlay = ref(false)
const showBlurOverlay = ref(false)
const showModal = ref(false)
const showModalSm = ref(false)
const showModalLg = ref(false)
const showConfirmDanger = ref(false)
const showConfirmWarning = ref(false)

const triggerOverlay = (withBlur = false) => {
  if (withBlur) {
    showBlurOverlay.value = true
    setTimeout(() => {
      showBlurOverlay.value = false
    }, 2500)
  } else {
    showOverlay.value = true
    setTimeout(() => {
      showOverlay.value = false
    }, 2500)
  }
}

const handleConfirmDanger = () => {
  showConfirmDanger.value = false
  showToast('success', 'Produk berhasil dihapus dari sistem.')
}

const handleConfirmWarning = () => {
  showConfirmWarning.value = false
  showToast('info', 'Proses transaksi pending berhasil dijalankan.')
}

// ════════════════════════════════════════════════════════
// TOAST
// Asumsi: toastStore.add({ type, message }) — sesuaikan
// dengan implementasi @/stores/toast di project Anda.
// ════════════════════════════════════════════════════════

const showToast = (type, message) => {
  if (typeof toastStore.add === 'function') {
    toastStore.add({ type, message })
  } else {
    console.warn('[ComponentTestPage] toastStore.add() tidak ditemukan. Sesuaikan API store toast.')
  }
}

// ════════════════════════════════════════════════════════
// TRANSAKSI / COMMERCE
// ════════════════════════════════════════════════════════

const demoProducts = [
  {
    id: 1,
    name: 'Nasi Goreng Special',
    price: 25000,
    stock: 10,
    image_url: null,
    category: { name: 'Makanan' },
  },
  {
    id: 2,
    name: 'Es Teh Manis',
    price: 8000,
    stock: 0,
    image_url: null,
    category: { name: 'Minuman' },
  },
  {
    id: 3,
    name: 'Ayam Bakar Madu',
    price: 35000,
    stock: 5,
    image_url: null,
    category: { name: 'Makanan' },
  },
  {
    id: 4,
    name: 'Jus Alpukat Susu',
    price: 18000,
    stock: 8,
    image_url: null,
    category: { name: 'Minuman' },
  },
]

const cartItems = ref([
  { id: 1, name: 'Nasi Goreng Special', price: 25000, qty: 2 },
  { id: 3, name: 'Ayam Bakar Madu', price: 35000, qty: 1 },
])

const cartSubtotal = computed(() =>
  cartItems.value.reduce((sum, item) => sum + item.price * item.qty, 0),
)
const cartTotal = computed(() => Math.round(cartSubtotal.value * 1.11))

const handleAddToCart = (product) => {
  const existing = cartItems.value.find((i) => i.id === product.id)
  if (existing) {
    existing.qty++
  } else {
    cartItems.value.push({ id: product.id, name: product.name, price: product.price, qty: 1 })
  }
  showToast('success', `${product.name} ditambahkan ke keranjang!`)
}

const increaseQty = (id) => {
  const item = cartItems.value.find((i) => i.id === id)
  if (item) item.qty++
}

const decreaseQty = (id) => {
  const item = cartItems.value.find((i) => i.id === id)
  if (!item) return
  if (item.qty > 1) item.qty--
  else removeItem(id)
}

const removeItem = (id) => {
  cartItems.value = cartItems.value.filter((i) => i.id !== id)
}

const handlePay = ({ total, cash, change }) => {
  showToast(
    'success',
    `Transaksi Rp ${total.toLocaleString('id-ID')} selesai! Kembalian Rp ${change.toLocaleString('id-ID')}`,
  )
}

const receiptTransaction = {
  id: 'TRX-2026-0042',
  total: 93000,
  created_at: '02/06/2026 14:30',
}

const receiptItems = [
  { id: 1, name: 'Nasi Goreng Special', price: 25000, qty: 2 },
  { id: 4, name: 'Jus Alpukat Susu', price: 18000, qty: 1 },
  { id: 3, name: 'Ayam Bakar Madu', price: 35000, qty: 1 },
]

const receiptCashier = { name: 'Budi Santoso' }

// ════════════════════════════════════════════════════════
// LAYOUT PREVIEW
// ════════════════════════════════════════════════════════

const sidebarOpen = ref(true)

const testMenuItems = [
  { label: 'Dashboard', to: '#', icon: 'heroicons:squares-2x2-solid' },
  { label: 'Manajemen Produk', to: '#', icon: 'heroicons:archive-box-solid' },
  { label: 'Kelola Karyawan', to: '#', icon: 'heroicons:users-solid' },
  { label: 'Laporan Penjualan', to: '#', icon: 'heroicons:chart-bar-solid' },
]

// ════════════════════════════════════════════════════════
// MISC HANDLERS
// ════════════════════════════════════════════════════════

const handleRetry = () => {
  showToast('info', 'Mencoba ulang memuat data...')
}
</script>
