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
    getProducts(context, page = '') {
      let getProductsLink = 'http://pizzzaaa.test/api/products';
      
      if (page) {
        getProductsLink += `?page=${page}`;
      }

      return new Promise((resolve, reject) => {
        Axios.get(getProductsLink)
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