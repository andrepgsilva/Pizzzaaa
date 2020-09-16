import Vue from 'vue';
import Vuex from 'vuex';
import Axios from "axios";
import { reject } from 'lodash';

Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    products: []
  },

  mutations: {
    addProducts(state, products) {
      state.products = products;
    }
  },

  actions: {
    getProducts(context) {
      return new Promise((resolve, reject) => {
        Axios.get('http://pizzzaaa.test/api/products')
        .then(response => {
          context.commit('addProducts', response.data)
          resolve(response.data)
        })
        .catch(error => {
          reject(error);
          console.log(error);
        })
      })
    }
  }
})

export default store;