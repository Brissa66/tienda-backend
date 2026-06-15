import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  { path: '/', redirect: '/catalogo' },
  {
    path: '/catalogo',
    name: 'Catalogo',
    component: () => import('../views/CatalogoView.vue'),
  },
  {
    path: '/carrito',
    name: 'Carrito',
    component: () => import('../views/CarritoView.vue'),
  },
  {
    path: '/checkout',
    name: 'Checkout',
    component: () => import('../views/CheckoutView.vue'),
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/LoginView.vue'),
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('../views/RegisterView.vue'),
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
