import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';
import helpers from './helpers';

Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    cart: JSON.parse(window.localStorage.getItem('cart'))['cart'] || {
      totalItems: 0,
      totalPrice: 0,
      items: [],
    },
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
    },

    addProductToCart(state, data) {
      state.cart.items.push(data);
    },

    addVariation(state, payload) {
      const product = state.cart.items[payload.productCartIndex]; 
      product['logistics'].push(payload.productVariation);
    },


    addVariationQuantity(state, payload) {
      let { 
        productCartIndex, 
        variationCartIndex, 
        variationQuantityChoosed 
      } = payload;
      
      variationQuantityChoosed = parseInt(variationQuantityChoosed);
      const variation = state.cart.items[productCartIndex]['logistics'][variationCartIndex];
      const productQuantity = variation['productQuantityChoosed'];
      variation['productQuantityChoosed'] = parseInt(productQuantity) +  variationQuantityChoosed;
    },

    changeVariationQuantity(state, payload) {
      let { 
        productCartIndex, 
        variationCartIndex, 
        variationQuantityChoosed 
      } = payload;
      
      variationQuantityChoosed = parseInt(variationQuantityChoosed);
      const variation = state.cart.items[productCartIndex]['logistics'][variationCartIndex];
      variation['productQuantityChoosed'] = variationQuantityChoosed;
    },

    setTotalItems(state, quantity) {
      state.cart.totalItems = parseInt(state.cart.totalItems) + parseInt(quantity);
    },

    changeTotalPrice(state, value) {
      state.cart.totalPrice += value;
    },

    updateTotalPrice(state) {
      let totalPrice = 0;
      
      state.cart.items.forEach(item => {
        for (let itemVariationKey in item.logistics) {
          const itemVariation = item.logistics[itemVariationKey];
          totalPrice += itemVariation.totalPrice;
        }
      });

      state.cart.totalPrice = totalPrice; 
    },

    updateTotalItems(state) {
      let totalItems = 0;

      state.cart.items.forEach(item => {
        for (let itemVariationKey in item.logistics) {
          const itemVariation = item.logistics[itemVariationKey];
          totalItems += parseInt(itemVariation.productQuantityChoosed);
        }
      });

      state.cart.totalItems = parseInt(totalItems); 
    },

    changeVariationTotalPrice(state, payload) {
      const { 
        productCartIndex, 
        variationCartIndex, 
        variationQuantityChoosed 
      } = payload;

      const productVariation = state.cart.items[productCartIndex]['logistics'][variationCartIndex];

      productVariation['totalPrice'] = variationQuantityChoosed * productVariation.price;
    },

    removeAllItemsForCart(state) {
      state.cart = { totalItems: 0, totalPrice: 0, items: [] };
    },

    removeVariationFromCart(state, payload) {
      const { productIndex, variationIndex } = payload;

      state.cart.items[productIndex]['logistics'].splice(variationIndex, 1);

      if (state.cart.items[productIndex]['logistics'].length == 0) {
        state.cart.items.splice(productIndex, 1);
      }
    },
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

    logout(context) {
      axios.post('/logout')
        .then(response => {
          context.commit('unsetUser');
        })
        .catch(error => {
          console.log('Something went wrong');
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

    addItemToCart(context, data) {
      const productId = data.id;
      const productVariation = data.logistics[0];
      const variationQuantityChoosed = productVariation['productQuantityChoosed']; 
      const cart = context.state.cart;
      let productCartIndex, variationCartIndex = null;

      cart.items.forEach((product, index) => { if (product.id == productId) { productCartIndex = index } });
      context.commit('setTotalItems', variationQuantityChoosed);

      productVariation['totalPrice'] = variationQuantityChoosed * productVariation['price'];
      context.commit('changeTotalPrice', productVariation['totalPrice']);

      if (productCartIndex == null) { 
        context.commit('addProductToCart', data)
        helpers.updateLocalStorageCart(context.state.cart);
        return;
      }
      variationCartIndex = helpers.findVariationCartIndex({cart, productCartIndex, productVariation});
      if (variationCartIndex == null) { 
        context.commit('addVariation', { productCartIndex, productVariation })
        helpers.updateLocalStorageCart(context.state.cart);
        return 
      }

      const productVariationOnCart = cart.items[productCartIndex]['logistics'][variationCartIndex];
      productVariationOnCart['totalPrice'] += productVariation['price'] * variationQuantityChoosed;
      context.commit('updateTotalPrice');
      
      context.commit('addVariationQuantity', { productCartIndex, variationCartIndex, variationQuantityChoosed });
      helpers.updateLocalStorageCart(context.state.cart);
    },

    clearCart(context) {
      context.commit('removeAllItemsForCart');
    },

    async routeForTest(context) {
      const response = await axios.get('/user')
      console.log(response.data)
    }
  }
})

export default store;