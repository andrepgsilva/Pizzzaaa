import Vue from 'vue';
import Vuex from 'vuex';
import axios from "axios";

Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    products: [],
    user: null || JSON.parse(window.localStorage.getItem('user')),
  },

  mutations: {
    addProducts(state, products) {
      state.products = products;
    },

    setUser(state, userData) {
      state.user = userData;

      window.localStorage.setItem('user', JSON.stringify(userData));
    },

    unsetUser(state) {
      state.user = null;

      window.localStorage.removeItem('user');
    }
  },

  actions: {
    getProducts(context, page = '') {
      let getProductsLink = 'products';
      
      if (page) {
        getProductsLink += `?page=${page}`;
      }

      return new Promise((resolve, reject) => {
        axios.get(getProductsLink)
        .then(response => {
          context.commit('addProducts', response.data)
          resolve(response.data) 
        })
        .catch(error => {
          reject(error);
          console.log(error);
        })
      })
    },

    registerUser(context, credentials) {
      return new Promise((resolve, reject) => {
        axios.get('/sanctum/csrf-cookie').then(response => {
          axios.post('register', credentials)
            .then(response => {
                resolve(response);
              })
              .catch(error => {
                reject(error);
              })
        });
      })
    },

    login(context, credentials) {
      return new Promise((resolve, reject) => {
        axios.get('/sanctum/csrf-cookie').then(response => {
          axios.post('login', credentials)
            .then(response => {
                
                context.commit('setUser', {
                  'username': response.data.user.username,
                  'email': response.data.user.email,
                  'profilePhotoPath': response.data.user.profilePhotoPath,
                });
                resolve(response);
              })
              .catch(error => {
                reject(error);
              })
        });
      });
    },

    logout(context, credentials) {
      axios.post('/logout')
        .then(response => {
          context.commit('unsetUser');
        })
        .catch(error => {
          console.log('The error is going to be sended to the administrators');
        });
    },

    forgotPassword(context, email) {
      axios.post('/password/email', {email})
    },

    resetPassword(context, data) {
      return new Promise((resolve, reject) => {
        axios.post('/password/reset', data)
          .then(response => {
            resolve(response); 
          })
          .catch(error => {
            reject(error);
          })
      });
    },

    async routeForTest(context) {
      const response = await axios.get('/user')
      console.log(response.data)
    }
  }
})

export default store;