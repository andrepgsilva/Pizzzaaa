<template>
  <div class="flex flex-col justify-center h-full my-10">
    <div class="flex flex-col items-center my-10">
      <div class="container flex justify-center">
        <table class="table-auto" v-if="!!cartItems.length">
          <thead>
            <tr>
              <th colspan="2" class="px-4 py-2">Quantity</th>
              <th class="px-4 py-2">Product</th>
              <th class="px-4 py-2">Size</th>
              <th class="px-4 py-2">Price</th>
            </tr>
          </thead>
          <tbody>
            <template v-for="(itemWithVariations, itemIndex) in cartItems">
              <tr
                v-for="(variation,
                variationIndex) in itemWithVariations.logistics"
                :key="variation.id"
              >
                <td class="border px-4 py-2">
                  <select
                    name="quantity"
                    class="bg-white block border border-gray-400 focus:outline-none hover:border-gray-500 leading-tight mt-3 pr-8 px-4 py-1 rounded shadow"
                    @change="
                      changeProductQuantityChoosed(
                        $event,
                        itemIndex,
                        variationIndex
                      )
                    "
                  >
                    <option value="0">
                      0
                    </option>
                    <option
                      v-for="counter in counterLimit(
                        variation.productQuantityChoosed
                      )"
                      :key="counter"
                      :value="counter"
                      :selected="variation.productQuantityChoosed == counter"
                    >
                      {{ counter }}
                    </option>
                  </select>
                </td>
                <td class="border px-2 py-2">
                  <img class="w-36 h-16" :src="itemWithVariations.image_url" />
                </td>
                <td class="border px-4 py-2">
                  <p class="font-lg font-medium text-cool-gray-900">
                    {{ itemWithVariations.name }}
                  </p>
                </td>
                <td class="border px-4 py-2">
                  <p class="font-lg font-medium text-cool-gray-900">
                    {{ variation.size_name }}
                  </p>
                </td>
                <td class="border px-4 py-2">
                  <p class="font-lg font-medium text-cool-gray-900">
                    {{ toPrice(variation.totalPrice) }}
                  </p>
                </td>
              </tr>
            </template>
            <tr class="border px-4 py-2">
              <td class="p-2">
                <button 
                  @click="clearCart"
                  class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 focus:outline-none rounded"
                >
                  Clear Cart
                </button>
              </td>
              <td colspan="2"></td>
              <td>Total</td>
              <td>
                <p>{{ toPrice(cartTotalPrice) }}</p>
              </td>
            </tr>
            <tr class="border px-4 py-2">
              <td colspan="5" class="p-2 text-right">
                <button 
                  @click="buyNow"
                  class="bg-yellow-300 hover:bg-yellow-400 text-white font-bold py-2 px-4 focus:outline-none rounded"
                >
                  Buy Now
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import Dinero from "dinero.js";
import helpers from "../helpers";

export default {
  data() {
    return {
      // counter: 0,
    };
  },

  computed: {
    cartItems() {
      return this.$store.state.cart.items;
    },

    cartTotalPrice() {
      return this.$store.state.cart.totalPrice;
    },

    isUserLogged() {
      return !! this.$store.state.user;
    }
  },

  methods: {
    changeProductQuantityChoosed(event, productIndex, variationIndex) {
      const quantityChoosed = event.target.value;

      if (event.target.value == 0) {
        this.$store.commit('removeVariationFromCart', { productIndex, variationIndex })
        this.$store.commit('updateTotalPrice');
        this.$store.commit('updateTotalItems');
        helpers.updateLocalStorageCart(this.$store.state.cart);
        return;
      }

      this.$store.commit('changeVariationQuantity', {
        productCartIndex: productIndex,
        variationCartIndex: variationIndex,
        variationQuantityChoosed: parseInt(quantityChoosed),
      });

      this.$store.commit('changeVariationTotalPrice', {
        productCartIndex: productIndex,
        variationCartIndex: variationIndex,
        variationQuantityChoosed: parseInt(quantityChoosed),
      });

      this.$store.commit('updateTotalPrice');
      this.$store.commit('updateTotalItems');
      helpers.updateLocalStorageCart(this.$store.state.cart);
    },

    clearCart() {
      this.$store.dispatch('clearCart')
        .then(response => {
          this.$router.push({ 
            name: 'products', 
          });
        }) 
    },

    buyNow() {
      if (! this.isUserLogged) {
        this.$router.push({ 
          name: 'login', 
        });
      }
    },

    counterLimit(numberOfOptions) {
      return numberOfOptions < 10 ? 10 : numberOfOptions;
    },

    toPrice(amount) {
      return Dinero({ amount, currency: "EUR" }).toFormat("$0,0.00");
    },
  },
};
</script>