import VueRouter from 'vue-router';
import Home from './views/Home';
import Products from './views/Products';
import Login from './views/Login';
import Register from './views/Register';

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
    },
    {
      path: '/login',
      name: 'login',
      component: Login,
      props: true,
    },
    {
      path: '/register',
      name: 'register',
      component: Register,
    }
  ],
})

export default router;