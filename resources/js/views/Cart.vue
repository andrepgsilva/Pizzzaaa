<template>
  <div class="flex flex-col justify-center h-full my-10">
    <div class="flex flex-col items-center my-10">
      <div class="container flex justify-center">
        <table class="table-fixed w-full" v-if="!!cartItems.length">
          <thead>
            <tr>
              <th colspan="2" class="hidden sm:table-cell md:table-cell lg:table-cell px-4 py-2">Quantity</th>
              <th class="py-2 block sm:hidden md:hidden lg:hidden">Quantity</th>
              <th class="px-4 py-2">Product</th>
              <th class="px-4 py-2">Size</th>
              <th class="px-4 py-2">Price</th>
            </tr>
          </thead>
          <tbody class="block sm:table-row-group md:table-row-group lg:table-row-group">
            <template v-for="(itemWithVariations, itemIndex) in cartItems">
              <tr
                v-for="(variation,
                variationIndex) in itemWithVariations.logistics"
                :key="variation.id"
              >
                <td class="border px-4 py-2">
                  <select
                    name="quantity"
                    class="bg-white block border border-gray-400 focus:outline-none hover:border-gray-500 leading-tight mt-3 sm:pr-8 md:pr-8 lg:pr-8 px-1 sm:px-4 md:px-4 lg:px-4 py-1 rounded shadow"
                    @change="
                      changeProductQuantityChoosed(
                        $event,
                        itemIndex,
                        variationIndex
                      )
                    "
                  >
                    <option value="0">0</option>
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
                <td class="hidden sm:table-cell md:table-cell lg:table-cell border px-2 py-2">
                  <div class="flex justify-center">
                    <img class="w-36 h-16" :src="itemWithVariations.image_url" />
                  </div>
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
              <td colspan="2" class="table-cell sm:hidden md:hidden lg:hidden p-2">
                <button
                  @click="clearCart"
                  class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 focus:outline-none rounded"
                >
                  Clear Cart
                </button>
              </td>

              <td class="p-2 hidden sm:table-cell md:table-cell lg:table-cell">
                <button
                  @click="clearCart"
                  class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 focus:outline-none rounded"
                >
                  Clear Cart
                </button>
              </td>
              <td class="hidden sm:table-cell md:table-cell lg:table-cell" colspan="2"></td>
              <td>Total</td>
              <td>
                <p>{{ toPrice(cartTotalPrice) }}</p>
              </td>
            </tr>
            <tr class="border px-4 py-2">
              <td colspan="1" class="table-cell sm:hidden md:hidden lg:hidden p-2 text-left">
              <td colspan="4" class="hidden sm:table-cell md:table-cell lg:table-cell p-2 text-right">
                <p class="text-red-500 text-sm" v-if="serverErrorMessage">
                  {{ serverErrorMessage }}
                </p>
              </td>
              <!-- <td colspan="2" class="p-2 text-right"> -->
              <td colspan="3" class="p-2 table-cell sm:hidden md:hidden lg:hidden text-right">
                <button
                  @click="buyNow"
                  class="bg-yellow-300 mr-3 sm:mr-0 md:mr-0 lg:mr-0 hover:bg-yellow-400 text-white font-bold py-2 px-4 focus:outline-none rounded"
                >
                  Buy Now
                </button>
              </td>
              <td class="p-2 hidden sm:table-cell md:table-cell lg:table-cell text-right">  
                <button
                  @click="buyNow"
                  class="bg-yellow-300 mr-3 sm:mr-0 md:mr-0 lg:mr-0 hover:bg-yellow-400 text-white font-bold py-2 px-4 focus:outline-none rounded"
                >
                  Buy Now
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <CardModal 
        :showing="paymentModal" 
        @close="$store.commit('changeShowPaymentModal', false)"
        :callbackWhenShow="stripeSetup"
      >
        <input 
          class="mb-10 focus:outline-none placeholder-iron-mountain text-tiny font-serif" 
          id="card-holder-name" 
          type="text" 
          placeholder="Card holder name"
        />

        <div id="card-element"></div>        
        
        <p class="mt-5 text-red-500 text-sm" v-if="cardNotificationMessage">
          {{ cardNotificationMessage }}
        </p>

        <button 
          id="card-button" 
          class="mt-10 bg-red-600 hover:bg-red-700 text-white font-bold py-2 w-full focus:outline-none rounded"
        >
          Confirm Order
        </button>
      </CardModal>

      <CardModal 
        :showing="orderConfirmedModal" 
        @close="closeOrderConfirmedModalBox"
      >
        <p> Thank you for buy our delicious pizzas! <br /> You can pick up the pizza in thirty minutes.</p>
      </CardModal>
    </div>
  </div>
</template>

<script>
import Dinero from "dinero.js";
import helpers from "../helpers";
import stripeSetup from "../services/Stripe";
import CardModal from "../components/CardModal";

export default {
  components: {
    CardModal,
  },

  data() {
    return {
      serverErrorMessage: "",
      stripeSetup,
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
      return !!this.$store.state.user;
    },

    cartVerifiedByServer() {
      return this.$store.state.cartVerifiedByServer;
    },
    
    cardNotificationMessage() {
      return this.$store.state.cardNotificationMessage;
    },

    paymentModal() {
      return this.$store.state.showPaymentModal;
    },

    orderConfirmedModal() {
      return this.$store.state.showOrderConfirmedModal;
    }
  },

  methods: {
    changeProductQuantityChoosed(event, productIndex, variationIndex) {
      const quantityChoosed = event.target.value;

      this.$store.commit("setCartVerification", false);

      if (event.target.value == 0) {
        this.$store.commit("removeVariationFromCart", {
          productIndex,
          variationIndex,
        });
        this.$store.commit("sumItemsVariationsPrice");
        this.$store.commit("updateTotalItems");
        helpers.updateLocalStorageCart(this.$store.state.cart);
        return;
      }

      this.$store.commit("changeVariationQuantity", {
        productCartIndex: productIndex,
        variationCartIndex: variationIndex,
        variationQuantityChoosed: parseInt(quantityChoosed),
        operation: 'equal',
      });

      this.$store.commit("changeVariationTotalPrice", {
        productCartIndex: productIndex,
        variationCartIndex: variationIndex,
        variationQuantityChoosed: parseInt(quantityChoosed),
      });

      this.$store.commit("sumItemsVariationsPrice");
      this.$store.commit("updateTotalItems");
      helpers.updateLocalStorageCart(this.$store.state.cart);
    },

    clearCart() {
      this.$store.dispatch("clearCart").then((response) => {
        this.$router.push({
          name: "products",
        });
      });
    },

    buyNow() {
      if (!this.isUserLogged) {
        this.$router.push({
          name: "login",
        });
      }

      this.$store
        .dispatch("checkPurchase")
        .then((response) => {
          const changeTotalPrice = response.data;

          this.$store.commit("changeTotalPrice", {value: changeTotalPrice, operation: 'equal'});
          this.$store.commit("setCartVerification", true);

          helpers.updateLocalStorageCart(this.$store.state.cart);
          this.showPaymentModalBox();
        })
        .catch((error) => {
          this.serverErrorMessage = error.response.data;
          this.$store.commit("setCartVerification", false);
        });
    },

    counterLimit(numberOfOptions) {
      return numberOfOptions < 10 ? 10 : numberOfOptions;
    },

    toPrice(amount) {
      return Dinero({ amount, currency: "EUR" }).toFormat("$0,0.00");
    },

    showPaymentModalBox() {
      this.$store.commit('changeShowPaymentModal', true);
    },

    closeOrderConfirmedModalBox() {
      this.$store.commit('changeShowOrderConfirmedModal', false)

      this.$router.push({
        name: "products",
      });
    },
  },
};
</script>

<style lang="scss" scoped>
  #card-button:active { 
    box-shadow: 7px 6px 28px 1px rgba(0, 0, 0, 0.24); 
    transform: translateY(3px);
  }
</style>