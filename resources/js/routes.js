import VueRouter from 'vue-router';
import Home from './views/Home';
import Products from './views/Products';

const router = new VueRouter({
  mode: 'history',

  routes: [
    {
      path: '/',
      name: 'home',
      component: Home, 
    },
    {
      path: '/products',
      name: 'products',
      component: Products,
    }
  ],
})

export default router;