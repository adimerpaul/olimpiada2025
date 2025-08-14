const routes = [
  {
    path: '/menu',
    // name: 'menu',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/IndexPage.vue'), meta: { requiresAuth: true } },
      { path: '/inscritos', component: () => import('pages/registro/Inscritos.vue'), meta: { requiresAuth: true } },
      { path: '/areas', component: () => import('pages/area/Area.vue'), meta: { requiresAuth: true } },
    ]
  },
  {
    path: '/',
    component: () => import('pages/registro/Registro.vue')
  },
  {
    path: '/login',
    component: () => import('pages/login/Login.vue')
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
