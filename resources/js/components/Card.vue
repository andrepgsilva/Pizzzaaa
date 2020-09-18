<template>
  <div class="card rounded overflow-hidden shadow-lg mt-5 pb-16 ml-4 w-11/12 sm:w-1/4 md:w-1/4 lg:w-1/4">
    <img class="w-full h-30" :src="imagesrc" />
    <div class="px-6 py-4">
      <div class="font-bold text-xl mb-2">{{ title }}</div>
      <p class="text-gray-700 text-lg">{{ description }}</p>

      <p class="mt-3 text-gray-800 italic font-semibold text-base">{{ subDescription }}</p>

      <!-- Pass some special code below if you need !-->
      <slot></slot>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'Card',

    props: {
      imagesrc: String,
      title: String,
      description: String,
      subDescription: String,
    }
  };
</script>

<style lang="scss" scoped>
  .card {
    min-width: 273px;
    max-height: 520px;
  }
</style>