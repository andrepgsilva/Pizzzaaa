require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import VueApp from './vueapp';
import Home from './views/Home';

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',

  routes: [
    {
      path: '/',
      name: 'home',
      component: Home, 
    }
  ],
})


const app = new Vue({
  el: '#app',
  components: {
    'vueapp': VueApp,
    'Home': Home,
  },
  router,
});