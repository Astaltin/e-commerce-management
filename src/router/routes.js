/** @type {import("vue-router").RouterOptions['routes']} routes */
const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('src/pages/DashboardPage.vue') },
      {
        path: '/products',
        component: () => import('src/pages/ProductsPage.vue'),
      },
    ],
  },
  {
    path: '/auth/login',
    component: () => import('src/pages/auth/LoginPage.vue'),
  },
  {
    path: '/profile',
    component: () => import('src/pages/ProfilePage.vue'),
  },
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
  },
];

export default routes;
