import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const routes = [
  {
    path: '/',
    redirect: '/login',
  },

  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/Auth/Login.vue'),
    meta: {
      guestOnly: true,
    },
  },

  {
    path: '/tester',
    name: 'tester',
    component: () => import('@/views/ComponentTester.vue'),
  },

  // =========================
  // ADMIN
  // =========================
  {
    path: '/admin',
    component: () => import('@/layouts/AdminLayout.vue'),
    meta: {
      requiresAuth: true,
      role: 'admin',
    },
    children: [
      {
        path: 'dashboard',
        name: 'admin.dashboard',
        component: () => import('@/views/Admin/Dashboard.vue'),
      },

      {
        path: 'products',
        name: 'admin.products.index',
        component: () => import('@/views/Admin/products/Index.vue'),
      },

      {
        path: 'products/create',
        name: 'admin.products.create',
        component: () => import('@/views/Admin/products/Create.vue'),
      },

      {
        path: 'products/:id/edit',
        name: 'admin.products.edit',
        component: () => import('@/views/Admin/products/Edit.vue'),
      },
    ],
  },

  // =========================
  // CASHIER
  // =========================
  {
    path: '/cashier',
    component: () => import('@/layouts/CashierLayout.vue'),
    meta: {
      requiresAuth: true,
      role: 'kasir',
    },
    children: [
      {
        path: '',
        name: 'cashier.pos',
        component: () => import('@/views/Cashier/TransactionCreate.vue'),
      },

      {
        path: 'transactions',
        name: 'cashier.transactions',
        component: () => import('@/views/Cashier/TransactionIndex.vue'),
      },

      {
        path: 'transactions/:id',
        name: 'cashier.transaction.show',
        component: () => import('@/views/Cashier/TransactionShow.vue'),
      },

      {
        path: 'receipts/:id',
        name: 'cashier.receipt',
        component: () => import('@/views/Cashier/ReceiptView.vue'),
      },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach(async (to) => {
  const authStore = useAuthStore()

  if (authStore.token && !authStore.user) {
    try {
      await authStore.fetchCurrentUser()
    } catch (error) {
      authStore.logout()
      return '/login'
    }
  }

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    return '/login'
  }

  if (to.meta.guestOnly && authStore.isAuthenticated) {
    if (authStore.isAdmin) {
      return '/admin/dashboard'
    }

    if (authStore.isKasir) {
      return '/cashier'
    }
  }

  if (to.meta.role && authStore.user?.role?.name !== to.meta.role) {
    if (authStore.isAdmin) {
      return '/admin/dashboard'
    }

    if (authStore.isKasir) {
      return '/cashier'
    }

    return '/login'
  }
})

export default router
