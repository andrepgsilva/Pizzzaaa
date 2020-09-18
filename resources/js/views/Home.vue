<template>
  <div class="banner w-100">
    <div class="flex items-center sm:items-baseline md:items-baseline lg:items-baseline banner-image">
      <div class="justify-start w-full">
        <div class="flex flex-col sm:w-1/3 md:w-1/3 lg:w-1/3 sm:mt-44 md:mt-44 lg:mt-44 mx-10 p-6 rounded bg-yellow-300">
          <h1 class="banner-title leading-snug text-6xl text-gray-900">Are You <br> Hungry?</h1>
          <h2 class="banner-subtitle my-2 text-3xl text-gray-900">Buy Our Delicious Pizzas</h2>
          <router-link 
            class="focus:outline-none outline-none mt-4 text-center text-2xl p-4 text-gray-100 bg-red-600 hover:bg-red-700 rounded"
            :to="{ name: 'products' }"
          >I Want!</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {}
</script>

<style lang="scss" scoped>
  .banner {
    height: calc(100vh - 64px);
  }

  .banner-image {
    background-image: url('/img/pizzzaaa-banner.jpg');
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
    height: 100%;
  }
</style>