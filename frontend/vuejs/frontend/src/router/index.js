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
        component: () => import('@/views/Admin/AdminDashboard.vue'),
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

      {
        path: 'transaction-history',
        name: 'admin.transaction-history',
        component: () => import('@/views/Admin/transactions/TransactionHistory.vue'),
      },

      {
        path: 'transaction-history/:id/detail',
        name: 'admin.transaction.detail',
        component: () => import('@/views/Admin/transactions/DetailHistory.vue'),
      }
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
        path: 'transaction-history/:id/receipts',
        name: 'cashier.receipt',
        component: () => import('@/views/Cashier/ReceiptView.vue'),
      },

      {
        path: 'transaction-history',
        name: 'cashier.transaction-history',
        component: () => import('@/views/Cashier/TransactionHistory.vue'),
      },

      {
        path: 'transaction-history/:id/detail',
        name: 'cashier.transaction.detail',
        component: () => import('@/views/Cashier/DetailHistory.vue'),
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
