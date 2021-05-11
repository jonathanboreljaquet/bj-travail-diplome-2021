<template>
  <div>
    <b-navbar fixed="top" toggleable="lg" type="dark" variant="dark">
      <b-img src="../assets/img/logoblancblanc.png" v-bind="logo" fluid alt="Responsive image"></b-img>
      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
      <b-collapse id="nav-collapse" is-nav>
        <b-navbar-nav>
          <b-nav-item to="/" href="#">Accueil</b-nav-item>
          <b-nav-item to="/about" href="#">À propos</b-nav-item>
          <b-nav-item to="/calendar" href="#">Agenda</b-nav-item>
        </b-navbar-nav>
        <b-navbar-nav class="ml-auto"> </b-navbar-nav>
      </b-collapse>
    </b-navbar>
  </div>
</template>

<script>
export default {
  name: "Navbar",
  data(){
    return {
      logo : {width : 80}
    }
  }
};
</script>

<style scoped>
 .navbar.navbar-dark.bg-dark{
    background-color: #3ea3d8!important;
    color: white;
    font-size: 22px;
 }
</style>
