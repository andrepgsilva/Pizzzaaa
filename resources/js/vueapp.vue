<template>
  <div>
    <Navbar logosrc="img/pizzzaaa-logo.png" />
    <main>
      <router-view></router-view>
    </main>
  </div>
</template>

<script>
  import Navbar from './components/Navbar'

  export default {
    components: {
      Navbar
    }
  }
</script>