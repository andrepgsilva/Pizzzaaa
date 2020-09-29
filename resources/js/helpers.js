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
  }
};

export default helpers;