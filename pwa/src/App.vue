<template>
  <div id="app">
    <Navbar />
    <router-view />
    <Footer />
  </div>
</template>

<script>
import Footer from './components/Footer.vue'
import Navbar from './components/Navbar.vue'

export default {
  name: 'app',
  components: {
    Navbar,
    Footer
  }
}
</script>

<style>
</style>
