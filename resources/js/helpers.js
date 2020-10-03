const helpers = {
  findVariationCartIndex (payload)  {
    const { cart, productCartIndex, productVariation } = payload;
    let variationCartIndex = null;
    
    cart['items'][productCartIndex]['logistics'].forEach((variation, index) => {
      if (variation.id == productVariation['id']) { 
        variationCartIndex = index 
      } 
    });

    return variationCartIndex;
  },

  updateLocalStorageCart(cart) {
    window.localStorage.setItem('cart', JSON.stringify({ 'cart': cart }));
  },

  getCartFromLocalStorage() {
    if (JSON.parse(window.localStorage.getItem('cart'))) {
      return JSON.parse(window.localStorage.getItem('cart'))['cart'];
    }

    return {
      totalItems: 0,
      totalPrice: 0,
      items: [],
    };
  },

  getProductsVariationsInCart(cart) {
    let productsVariation = [];
    let itemsIds = [];
    
    cart.items.forEach(item => {
      for(let itemVariation in item.logistics) {
        const variation = item.logistics[itemVariation];
        let variationFormated = {};
  
        itemsIds.push(variation.id);
  
        variationFormated[`${variation.id}`] = {
          id: variation.id,
          quantityChoosed: variation.productQuantityChoosed,
          totalPrice: variation.totalPrice,
        }
  
        productsVariation.push(variationFormated);
      }
    });

    return {
      productsVariation,
      itemsIds,
    }
  }
};

export default helpers;