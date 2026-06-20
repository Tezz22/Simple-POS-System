# 🛒 Simple POS System

<div align="center">

# Sistem Point of Sale Modern dengan Laravel & Vue.js

Aplikasi kasir (_Point of Sale_) yang dirancang untuk membantu proses penjualan menjadi lebih cepat, mudah, dan efisien. Dibangun menggunakan **Laravel 12**, **Vue 3**, **Pinia**, dan **Tailwind CSS** dengan tampilan modern serta arsitektur yang mudah dikembangkan.

![Laravel](https://img.shields.io/badge/Laravel-12-red?style=for-the-badge&logo=laravel)
![Vue](https://img.shields.io/badge/Vue-3-42b883?style=for-the-badge&logo=vue.js)
![Pinia](https://img.shields.io/badge/Pinia-State-yellow?style=for-the-badge)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-UI-38bdf8?style=for-the-badge&logo=tailwindcss)

</div>

---

## 🎥 Demo Aplikasi

> Tambahkan GIF atau rekaman layar aplikasi di bawah ini.

```text
assets/demo.gif
```

---

# ✨ Fitur Utama

| Fitur                  | Status |
| ---------------------- | :----: |
| 🔐 Login & Autentikasi |   ✅   |
| 👥 Manajemen Pengguna  |   ✅   |
| 📂 Manajemen Kategori  |   ✅   |
| 🍽️ Manajemen Produk    |   ✅   |
| 🛒 Sistem Kasir (POS)  |   ✅   |
| 🧾 Riwayat Transaksi   |   ✅   |
| 📄 Detail Transaksi    |   ✅   |
| 🖨️ Cetak Struk         |   ✅   |
| 📊 Dashboard Ringkasan |   ✅   |
| 📈 Statistik Penjualan |   ✅   |
| 🌙 Mode Gelap          |   ✅   |
| 📱 Desain Responsif    |   ✅   |

---

# 🏗️ Arsitektur Proyek

```
Simple POS System
│
├── Backend (Laravel)
│   ├── Controllers
│   ├── Domain
│   │   ├── Actions
│   │   └── Queries
│   ├── Models
│   ├── Repositories
│   ├── Resources
│   └── API Routes
│
└── Frontend (Vue.js)
    ├── Layouts
    │   ├── AdminLayout
    │   └── CashierLayout
    ├── Pages
    ├── Components
    ├── Stores (Pinia)
    ├── Router
    └── Services
```

Struktur ini memisahkan logika bisnis, tampilan, dan pengelolaan data sehingga kode lebih rapi, mudah dipelihara, dan siap dikembangkan untuk kebutuhan yang lebih besar.

---

# 🚀 Teknologi yang Digunakan

## Backend

- Laravel 12
- Laravel Sanctum
- MySQL
- Eloquent ORM

## Frontend

- Vue 3 Composition API
- Vue Router
- Pinia
- Axios
- Tailwind CSS
- Iconify

---

# 📊 Dashboard Admin

Dashboard menyediakan ringkasan informasi penting secara cepat, seperti:

- 💰 Total pendapatan
- 🧾 Jumlah transaksi
- 📦 Total produk
- 📂 Total kategori
- 👤 Total pengguna
- 🔥 Statistik penjualan harian

Dengan tampilan ringkas, administrator dapat memantau kondisi bisnis tanpa harus membuka setiap menu satu per satu.

---

# 🛍️ Modul Kasir

Halaman kasir dirancang agar proses transaksi berlangsung cepat dan nyaman.

Fitur yang tersedia antara lain:

- Pencarian produk secara instan
- Filter berdasarkan kategori
- Keranjang belanja interaktif
- Perhitungan total otomatis
- Proses pembayaran
- Perhitungan kembalian
- Pembuatan invoice
- Cetak struk setelah transaksi berhasil

---

# 📜 Riwayat Transaksi

Setiap transaksi yang berhasil diproses akan tersimpan dan dapat ditinjau kembali.

Informasi yang tersedia meliputi:

- Nomor invoice
- Waktu transaksi
- Nama kasir
- Daftar produk yang dibeli
- Jumlah item
- Total pembayaran
- Status transaksi

---

# 📁 Struktur Folder

```
frontend/
 ├── components/
 ├── layouts/
 ├── pages/
 ├── router/
 ├── services/
 └── stores/

backend/
 ├── app/
 │   ├── Domain/
 │   ├── Http/
 │   ├── Models/
 │   └── Repository/
 ├── routes/
 └── database/
```

---

# ⚙️ Cara Menjalankan Proyek

## Clone Repository

```bash
git clone https://github.com/username/simple-pos-system.git
cd simple-pos-system
```

## Backend

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## Frontend

```bash
npm install
npm run dev
```

---

# 📸 Tampilan Aplikasi

Kamu bisa menambahkan beberapa tangkapan layar di bagian ini, misalnya:

- Dashboard Admin
- Halaman Kasir
- Manajemen Produk
- Riwayat Transaksi
- Detail Transaksi
- Cetak Struk

---

# 🔮 Pengembangan Selanjutnya

Beberapa fitur yang dapat ditambahkan di masa mendatang:

- 📊 Grafik penjualan
- 📦 Manajemen stok otomatis
- 📝 Activity Log
- 📤 Export PDF & Excel
- 🔔 Notifikasi stok menipis
- 👥 Multi-cabang
- 📱 Dukungan Progressive Web App (PWA)

---

# 👨‍💻 Pengembang

Dikembangkan sebagai proyek pembelajaran dan portofolio menggunakan Laravel dan Vue.js dengan fokus pada arsitektur yang bersih, antarmuka modern, dan pengalaman pengguna yang nyaman.

---

<div align="center">

### ⭐ Jika proyek ini bermanfaat, jangan ragu untuk memberikan bintang di GitHub!

**Terima kasih telah berkunjung. Semoga proyek ini dapat menjadi referensi dan inspirasi untuk pengembangan aplikasi Point of Sale yang lebih baik.**

</div>
