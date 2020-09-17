<template>
  <div class="flex justify-center h-full my-10">
    <div class="container flex flex-col items-center">
      <div class="flex flex-wrap sm:ml-1/12 md:ml-1/12 lg:ml-1/12 sm:w-3/4 md:w-3/4 lg:w-3/4">
          <Card
            v-for="product in products"
            :key="product.id"
            :imagesrc="product.image_url"
            :title="product.name"
            :description="product.description"
          ></Card>
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

export default {
  components: {
    Card,
  },

  data() {
    return {
      products: [],

      pagination: {
        pageRange: null,
      }
    };
  },

  created() {
    this.$store.dispatch("getProducts").then((response) => {
      this.pagination.pageRange = response.meta.last_page;

      this.products = response.data;
    });
  },

  methods: {
    paginationChangeCallback (toPage) {
      this.$store.dispatch("getProducts", toPage).then((response) => {
        this.products = response.data;
      });
    }
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

  .page-item>a:focus, .page-item>a:active {
    outline: none;
  }

  .page-item.active {
    background-color: #FACA15;
  }

  .pagination li:first-child, .pagination li:last-child {
    padding: 6px;
    background-color: #e02424;
    color: #fdf5d8;
    text-align: center;
    border: 1px solid #63606042;
    border-radius: 2px;
  }

  .pagination li:first-child:hover, .pagination li:last-child:hover {
    background-color: #e02424e8;
  }

  .pagination li:first-child.disabled, .pagination li:last-child.disabled {
    background-color: #b51919;
  }

  .pagination li:first-child>a:focus, .pagination li:last-child>a:focus {
    outline: none;
  }
</style>