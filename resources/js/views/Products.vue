<template>
  <div class="flex justify-center h-full my-10">
    <div class="container flex flex-col items-center">
      <div class="flex flex-wrap sm:ml-1/12 md:ml-1/12 lg:ml-1/12 sm:w-3/4 md:w-3/4 lg:w-3/4">
        <Card
          v-for="(product, index) in products"
          :key="product.id"
          :imagesrc="product.image_url"
          :title="product.name"
          :description="product.description"
          :subDescription="setTextLimit(product.ingredients, '25')"
        >
          <div class="flex justify-between mt-2">
            <select
              name="pizzaSizes"
              class="bg-white block border border-gray-400 focus:outline-none hover:border-gray-500 leading-tight mt-3 pr-8 px-4 py-1 rounded shadow"
              @change="changeProductSizeChoosed($event, index)"
            >
              <option v-for="logistic in product.logistics" :key="logistic.size_id" :value="logistic.size_id">{{ logistic.size_name }}</option>
            </select>
            <select
              name="quantity"
              class="bg-white block border border-gray-400 focus:outline-none hover:border-gray-500 leading-tight mt-3 pr-8 px-4 py-1 rounded shadow"
              @change="changeProductQuantityChoosed($event, index)"
            >
              <option
                v-for="counter in 10" 
                :key="counter" 
                :value="counter"
              >
                {{ counter }}
              </option>
            </select>
          </div>
          <div class="flex justify-between items-center mt-5">
            <p>{{
              product.hasOwnProperty('productVariationPrice') 
              ? toPrice(product['productVariationPrice'])
              : toPrice(productPrice(index))
            }}</p>

            <button
              class="card-button bg-red-600 hover:bg-red-500 px-6 py-2 rounded text-gray-100 font-semibold focus:outline-none"
              @click="addItemToCart(index)"
            > Add to Cart</button>
          </div>
        </Card>
      </div>
      <paginate
        v-if="pagination.pageRange > 1"
        :page-count="pagination.pageRange"
        :click-handler="paginationChangeCallback"
        :prev-text="'Prev'"
        :next-text="'Next'"
        :container-class="'pagination'"
        :page-class="'page-item'"
        class="flex self-center ml-4 mt-10"
      ></paginate>
    </div>
  </div>
</template>

<script>
import Card from "../components/Card";
import Dinero from 'dinero.js'

export default {
  components: {
    Card,
  },

  data() {
    return {
      products: [],

      pagination: {
        pageRange: null,
      },
    };
  },

  created() {
    this.$store.dispatch("getProducts").then((response) => {
      this.pagination.pageRange = response.last_page;

      this.products = response.data;
    });

    // this.$store.dispatch('routeForTest');
  },

  methods: {
    addItemToCart(productIndex) {
      const product = this.products[productIndex];      
      let sizeChoosed = product.logistics['0'].size_id; 

      if (product.hasOwnProperty('sizeSelected')) {
        sizeChoosed = product['sizeSelected'];
      }

      const variationIndex = this.findProductVariationIndex(productIndex, sizeChoosed);

      if (! product.logistics[variationIndex].hasOwnProperty('productQuantityChoosed')) {
        product.logistics[variationIndex]['productQuantityChoosed'] = 1;
      }

      if (product.hasOwnProperty('currentQuantityChoosed')) {
        product.logistics[variationIndex]['productQuantityChoosed'] = product['currentQuantityChoosed'];
      }

      this.$store.dispatch('addItemToCart', {
        id: product.id,
        name: product.name,
        description: product.description,
        image_url: product.image_url,
        ingredients: product.ingredients,
        
        logistics: [
          JSON.parse(JSON.stringify(product.logistics[variationIndex]))
        ],
      });
    },

    changeProductQuantityChoosed(event, productIndex) {
      const product = this.products[productIndex];
      product['currentQuantityChoosed'] = event.target.value; 

      if (product.hasOwnProperty('sizeSelected')) {
        const sizeSelected = product['sizeSelected'];
        const variationIndex = this.findProductVariationIndex(productIndex, sizeSelected);
        
        product.logistics[variationIndex]['productQuantityChoosed'] = event.target.value;
      }
    },

    changeProductSizeChoosed(event, productIndex) {
      const sizeChoosedId = event.target.value;
      
      this.products[productIndex]['sizeSelected'] = sizeChoosedId;
      this.productPrice(productIndex);
    },

    productPrice(productIndex) {
      const products = this.products[productIndex];

      if (! products.hasOwnProperty('productVariationPrice')) {
        products['sizeSelected'] = products.logistics[0].size_id;
        
        return this.$set(products, 'productVariationPrice', products.logistics[0].price);
      }

      const productVariationPrice = products.logistics.filter((variation, index) => {
        return variation.size_id == products['sizeSelected']
      })[0].price;

      products['productVariationPrice'] = productVariationPrice;
    },

    findProductVariationIndex(productIndex, sizeSelected) {
      let productVariationIndex = null;

        this.products[productIndex].logistics.forEach((variation, index) => {
          if (variation.size_id == sizeSelected) {
            productVariationIndex = index;
          } 
        });

        return productVariationIndex;
    },

    toPrice(amount) {
      return Dinero({ amount, currency: 'EUR'}).toFormat('$0,0.00');
    },

    setTextLimit(text, limit) {
      return text.length > limit ? text.substr(0, limit) + '...' : text; 
    },

    paginationChangeCallback(toPage) {
      this.$store.dispatch("getProducts", toPage).then((response) => {
        this.products = response.data;
      });
    },
  },
};
</script>

<style lang="scss">
.page-item {
  padding: 6px;
  background-color: #e02424;
  width: 32px;
  color: #fdf5d8;
  text-align: center;
  border: 1px solid #63606042;
  border-radius: 2px;
}

.page-item:hover {
  background-color: #e02424e8;
}

.page-item > a:focus,
.page-item > a:active {
  outline: none;
}

.page-item.active {
  background-color: #faca15;
}

.pagination li:first-child,
.pagination li:last-child {
  padding: 6px;
  background-color: #e02424;
  color: #fdf5d8;
  text-align: center;
  border: 1px solid #63606042;
  border-radius: 2px;
}

.pagination li:first-child:hover,
.pagination li:last-child:hover {
  background-color: #e02424e8;
}

.pagination li:first-child.disabled,
.pagination li:last-child.disabled {
  background-color: #b51919;
}

.pagination li:first-child > a:focus,
.pagination li:last-child > a:focus {
  outline: none;
}

.card-button:active { 
  box-shadow: 7px 6px 28px 1px rgba(0, 0, 0, 0.24); 
  transform: translateY(3px);
}
</style>