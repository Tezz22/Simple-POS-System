import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Login from '@/views/Auth/Login.vue';
import ComponentTester from '@/views/ComponentTester.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import ProductCreate from '@/views/Admin/products/create.vue';
import ProductEdit from '@/views/Admin/products/edit.vue';
import ProductIndex from '@/views/Admin/products/index.vue';


const routes = [
    { 
        path: '/login', 
        name: 'login', 
        component: Login,
        meta: { guestOnly: true }
    },
    {
        path: '/tester',
        name: 'tester',
        component: ComponentTester,
        meta: { requiresAuth: false }
    },
    {
        path: '/',
        redirect: '/login'
    },
    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: () => import('@/views/Admin/Dashboard.vue'),
        meta: { requiresAuth: true, role: 'admin' }
    },
    {
        path: '/kasir/transaksi',
        name: 'kasir.transaksi',
        component: () => import('@/views/Kasir/TransactionCreate.vue'),
        meta: { requiresAuth: true, role: 'kasir' }
    },
    {
        path: '/admin',
        component: AdminLayout,
        children: [
            { path: 'products', component: ProductIndex },
            { path: 'products/create', component: ProductCreate },
            { path: 'products/:id/edit', component: ProductEdit }
        ]
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Guard Checking sebelum berpindah halaman
router.beforeEach(async (to, from) => {
    const authStore = useAuthStore();
    
    // Jika token ada tetapi data user kosong, fetch dulu data usernya
    if (authStore.token && !authStore.user) {
        try {
            await authStore.fetchCurrentUser();
        } catch (error) {
            console.error("Gagal mengambil data user:", error);
            authStore.logout(); // Bersihkan token jika ternyata token kedaluwarsa
            return '/login';
        }
    }

    // Kasus 1: Halaman butuh login, tapi user belum login
    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        return '/login'; // Langsung return string path tujuan
    }

    // Kasus 2: Halaman khusus tamu (login), tapi user sudah login -> Lempar ke dashboard sesuai role
    if (to.meta.guestOnly && authStore.isAuthenticated) {
        if (authStore.isAdmin) return '/admin/dashboard';
        if (authStore.isKasir) return '/kasir/transaksi';
        return '/'; // Fallback jika role tidak dikenali
    }

    // Kasus 3: Cek hak akses role
    if (to.meta.role && authStore.user?.role?.name !== to.meta.role) {
        alert('Anda tidak memiliki hak akses ke halaman ini!');
        
        // Jika tidak ada halaman asal (misal user ketik URL manual di tab baru), lempar ke halaman aman
        if (from.path === to.path || from.path === '/') {
            return authStore.isAdmin ? '/admin/dashboard' : '/kasir/transaksi';
        }
        return from.path; // Kembalikan ke halaman sebelumnya
    }
});

export default router;