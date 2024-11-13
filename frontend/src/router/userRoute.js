/** @type {Readonly<import("vue-router").RouteRecordRaw>} */
export default {
  path: '/',
  meta: { requiresAuth: true },
  component: () => import('src/layouts/UserLayout.vue'),
  children: [
    {
      path: '',
      name: 'dashboard',
      component: () => import('src/pages/DashboardPage.vue'),
    },
    {
      path: 'products',
      name: 'products',
      component: () => import('src/pages/ProductsPage.vue'),
    },
    {
      path: 'profile',
      name: 'profile',
      component: () => import('src/pages/ProfilePage.vue'),
    },
  ],
};
