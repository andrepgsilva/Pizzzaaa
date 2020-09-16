<template>
  <div class="flex justify-center h-full">
    <div class="container flex justify-start flex-wrap">
      <Card 
        v-for="product in products" 
        :key="product.id" 
        :imagesrc="product.image_url"
        :title="product.name"
        :description="product.description"
      ></Card>
    </div>
  </div>
</template>

<script>
  import Card from '../components/Card';

  export default {
    components: {
      Card
    },

    data() {
      return {
        products: [],
      };
    },

    created() {
      this.$store.dispatch("getProducts").then((response) => {
        this.products = this.$store.state.products;
      });
    },
  };
</script>