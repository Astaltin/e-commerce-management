/** @type {Readonly<import("vue-router").RouteRecordRaw>} */
export default {
  path: '/auth',
  children: [
    {
      path: 'login',
      name: 'auth.login',
      component: () => import('src/pages/auth/LoginPage.vue'),
    },
  ],
};
