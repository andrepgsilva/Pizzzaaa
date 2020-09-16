require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import VueApp from './vueapp';
import router from './routes';
import store from './store';
import Home from './views/Home';
import Products from './views/Products';

Vue.use(VueRouter);

const app = new Vue({
  el: '#app',
  
  components: {
    'vueapp': VueApp,
    'Home': Home,
    'Products': Products
  },
  router,
  store,
});