const routes = [
  {
    path: '/',
    component: () => import('@/layouts/MainLayout.vue'),
    children: [
      { path: '', name: 'inicio', component: () => import('@/pages/IndexPage.vue') },
      { path: 'coletas', name: 'coletas', component: () => import('@/pages/ColetasPage.vue') },
      { path: 'agenda', name: 'agenda', component: () => import('@/pages/AgendaPage.vue') },
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
