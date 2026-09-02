const routes = [
  {
    path: '/',
    component: () => import('@/layouts/MainLayout.vue'),
    children: [
      { path: '', redirect: { name: 'coletas' } },
      { path: 'coletas', name: 'coletas', component: () => import('@/pages/ColetasPage.vue') },
      {
        path: 'motoristas',
        name: 'motoristas',
        component: () => import('@/pages/MotoristasPage.vue'),
      },
    ],
  },
  {
    path: '/:catchAll(.*)*',
    component: () => import('@/pages/ErrorNotFound.vue'),
  },
]

export default routes
