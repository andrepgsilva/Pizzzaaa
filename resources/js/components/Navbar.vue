<template>
  <header class="bg-red-600">
    <div class="flex items-center justify-between px-4 py-3">
      <div class="flex">
        <img class="h-10" :src="logosrc" alt="Pizzzaaa company logo" />
      </div>
      <div class="hidden md:flex lg:flex items-center">
        <a
          href="#"
          class="inline-block text-md px-4 py-2 leading-none border rounded text-white hover:text-gray-100 bg-yellow-300 border-yellow-300 hover:border-transparent lg:mt-0"
        >Buy now!</a>
        <a
          href="#responsive-header"
          class="block lg:inline-block lg:mt-0 text-white hover:text-white ml-4"
        >Login</a>
        <a
          href="#responsive-header"
          class="block lg:inline-block lg:mt-0 text-white hover:text-white ml-4"
        >Sign up</a>
      </div>

      <div class="sm:block md:hidden lg:hidden">
        <button
          @click="isOpen = !isOpen"
          type="button"
          class="block text-yellow-300 hover:text-yellow-400 focus:text-yellow-400 focus:outline-none"
        >
          <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24">
            <path
              v-if="isOpen"
              fill-rule="evenodd"
              d="M18.278 16.864a1 1 0 0 1-1.414 1.414l-4.829-4.828-4.828 4.828a1 1 0 0 1-1.414-1.414l4.828-4.829-4.828-4.828a1 1 0 0 1 1.414-1.414l4.829 4.828 4.828-4.828a1 1 0 1 1 1.414 1.414l-4.828 4.829 4.828 4.828z"
            />
            <path
              v-if="!isOpen"
              fill-rule="evenodd"
              d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"
            />
          </svg>
        </button>
      </div>
    </div>
    <div :class="isOpen ? 'block' : 'hidden'" class="px-2 pt-2 pb-4">
      <a
        href="#"
        class="inline-block text-md px-4 py-2 leading-none border rounded bg-yellow-400 hover:text-gray-100 border-yellow-400 hover:border-transparent mt-4 lg:mt-0"
      >Buy now!</a>
      <a
        href="#responsive-header"
        class="block mt-4 lg:inline-block lg:mt-0 text-white hover:text-white ml-4"
      >Login</a>
      <a
        href="#responsive-header"
        class="block mt-4 lg:inline-block lg:mt-0 text-white hover:text-white ml-4"
      >Sign up</a>
    </div>
  </header>
</template>

<script>
export default {
  data() {
    return {
      isOpen: false,
    };
  },

  props: {
    logosrc: String,
  },
};
</script>
