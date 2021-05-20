<template>
  <div id="app">
    <div id="content">
      <Navbar />
      <router-view />
    </div>
    <Footer />
  </div>
</template>

<script>
import Footer from "./components/Footer.vue";
import Navbar from "./components/Navbar.vue";

export default {
  name: "app",
  components: {
    Navbar,
    Footer,
  },
  created() {
    this.$store.dispatch("autoLogin");
  },
};
</script>

<style>
#content {
  min-height: calc(100vh - 260px);
}
</style>
