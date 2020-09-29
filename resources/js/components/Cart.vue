<template>
  <div class="bg-white border-2 border-yellow-300 flex h-8 items-center justify-end mr-1 px-2 rounded">
    <img class="mx-1 w-6" src="img/shopping-cart.svg" alt="Shopping Cart Icon">
    <p class="font-semibold text-yellow-300 pr-1">Cart</p>
    <p 
      class="bg-yellow-300 font-semibold rounded-full text-center text-white w-6"
      v-text="totalQuantity < 100 ? totalQuantity : '99+'"
    ></p>
  </div>
</template>

<script>
export default {
  name: 'Cart',

  props: ['totalQuantity'],
}
</script>