require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import VueApp from './vueapp';
import router from './routes';
import store from './store';
import Home from './views/Home';
import Products from './views/Products';
import Paginate from 'vuejs-paginate';
import Vuelidate from 'vuelidate';

Vue.use(Vuelidate);
Vue.use(VueRouter);
Vue.component('Paginate', Paginate);

const app = new Vue({
  el: '#app',
  
  components: {
    'vueapp': VueApp,
    'Home': Home,
    'Products': Products,
  },
  router,
  store,
});