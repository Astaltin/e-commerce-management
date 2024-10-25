import publicRoute from './publicRoute';
import userRoute from './userRoute';

/** @type {import("vue-router").RouterOptions['routes']} routes */
const routes = [
  userRoute,
  publicRoute,
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
  },
];

export default routes;
