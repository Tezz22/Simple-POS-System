import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Login from '@/views/Auth/Login.vue';

const routes = [
    { 
        path: '/login', 
        name: 'login', 
        component: Login,
        meta: { guestOnly: true }
    },
    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: () => import('@/views/Admin/Dashboard.vue'), // Lazy load
        meta: { requiresAuth: true, role: 'admin' }
    },
    {
        path: '/kasir/transaksi',
        name: 'kasir.transaksi',
        component: () => import('@/views/Kasir/TransactionCreate.vue'),
        meta: { requiresAuth: true, role: 'kasir' }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Guard Checking sebelum berpindah halaman
router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();
    
    // Jika token ada tetapi data user kosong, fetch dulu data usernya (misal setelah refresh page)
    if (authStore.token && !authStore.user) {
        await authStore.fetchCurrentUser();
    }

    // Kasus 1: Halaman butuh login, tapi user belum login
    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        return next('/login');
    }

    // Kasus 2: Halaman khusus tamu (seperti login page), tapi user sudah terlanjur login
    if (to.meta.guestOnly && authStore.isAuthenticated) {
        if (authStore.isAdmin) return next('/admin/dashboard');
        if (authStore.isKasir) return next('/kasir/transaksi');
    }

    // Kasus 3: Cek hak akses role
    if (to.meta.role && authStore.user?.role?.name !== to.meta.role) {
        alert('Anda tidak memiliki hak akses ke halaman ini!');
        return next(from.path); // Kembalikan ke halaman sebelumnya
    }

    next();
});

export default router;