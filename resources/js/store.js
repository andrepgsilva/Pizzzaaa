import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';
import helpers from './helpers';

Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    cart: helpers.getCartFromLocalStorage(),
    products: [],
    user: null || JSON.parse(window.localStorage.getItem('user')),
    cartVerifiedByServer: false,
    cardInformationErrorMessage: null,
    showPaymentModal: false,
    showOrderConfirmedModal: false,
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
    
    changeVariationQuantity(state, payload) {
      let { 
        productCartIndex, 
        variationCartIndex, 
        variationQuantityChoosed,
        operation,
      } = payload;

      variationQuantityChoosed = parseInt(variationQuantityChoosed);
      const variation = state.cart.items[productCartIndex]['logistics'][variationCartIndex];

      const operations = {
        equal() { variation['productQuantityChoosed'] = variationQuantityChoosed; },
        sum() { 
          variation['productQuantityChoosed'] = parseInt(productQuantity) +  variationQuantityChoosed; 
        },
        subtract() {
          variation['productQuantityChoosed'] = parseInt(productQuantity) -  variationQuantityChoosed;
        },
      };

      operations[operation]();
    },

    setTotalItems(state, quantity) {
      state.cart.totalItems = parseInt(state.cart.totalItems) + parseInt(quantity);
    },

    changeTotalPrice(state, payload) {
      const { value, operation } = payload;

      const operations = {
        equal() { state.cart.totalPrice = value },
        sum() { state.cart.totalPrice += value },
        subtract() { state.cart.totalPrice -= value }
      }

      operations[operation]();
    },

    sumItemsVariationsPrice(state) {
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

    setCartVerification(state, value) {
      state.cartVerifiedByServer = value; 
    },

    changeCardNotificationMessage(state, message) {
      state.cardInformationErrorMessage = message;
    },

    changeShowPaymentModal(state, value) {
      state.showPaymentModal = value;
    },

    changeShowOrderConfirmedModal(state, value) {
      state.showOrderConfirmedModal = value;
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
      context.commit('changeTotalPrice', { value: productVariation['totalPrice'], operation: 'sum' });

      context.commit('setCartVerification', false);

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
      context.commit('sumItemsVariationsPrice');
      
      context.commit('changeVariationQuantity', { 
        productCartIndex, 
        variationCartIndex, 
        variationQuantityChoosed,
        operation: 'sum',
      });
      helpers.updateLocalStorageCart(context.state.cart);
    },

    clearCart(context) {
      context.commit('removeAllItemsForCart');
    },

    checkPurchase(context) {
      let { productsVariation, itemsIds } = helpers.getProductsVariationsInCart(context.state.cart);

      return new Promise((resolve, reject) => {
        return axios.post('/check-purchase', {
          items: productsVariation,
          totalPrice: context.state.cart.totalPrice,
          itemsIds: itemsIds,
        })
          .then(response => {
            resolve(response);
          })
          .catch(error => {
            reject(error);
          })
      })
    },

    processChargeClient(context, payload) {
      let { productsVariation, itemsIds } = helpers.getProductsVariationsInCart(context.state.cart);
      payload.productsVariation = productsVariation;
      payload.itemsIds = itemsIds;

      return new Promise((resolve, reject) => {
        return axios.post('/charge-client', payload)
          .then(response => {
            resolve(response);
          })
          .catch(error => {
            reject(error);
          })
      })
    },

    async routeForTest(context) {
      const response = await axios.get('/user')
      console.log(response.data)
    }
  }
})

export default store;