<template>
  <div>
    <b-navbar fixed="top" toggleable="lg" type="dark" variant="dark">
      <b-img
        src="../assets/img/logoblancblanc.png"
        v-bind="logo"
        fluid
        @click="$router.push('/')"
        alt="Responsive image"
      ></b-img>
      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
      <b-collapse id="nav-collapse" is-nav>
        <b-navbar-nav>
          <b-nav-item to="/" href="#">Accueil</b-nav-item>
          <b-nav-item to="/about" href="#">À propos</b-nav-item>
          <b-nav-item to="/calendar" href="#">Agenda</b-nav-item>
          <b-nav-item to="/customer_information" v-if="authCustomer" href="#">
            Mes informations
          </b-nav-item>
          <b-nav-item to="/customer_appointment" v-if="authCustomer" href="#">
            Mes rendez-vous
          </b-nav-item>
          <b-nav-item to="/administration" v-if="authAdministrator" href="#">
            Administration
          </b-nav-item>
          <b-nav-item to="/planning" v-if="authAdministrator" href="#">
            Mon planning
          </b-nav-item>
          <b-nav-item to="/educator_calendar" v-if="authAdministrator" href="#">
            Mes rendez-vous
          </b-nav-item>
        </b-navbar-nav>
        <b-navbar-nav class="ml-auto">
          <b-nav-item v-if="auth" @click="logout()" href="#">
            Déconnexion
          </b-nav-item>
          <b-nav-item v-if="!auth" to="/connection" href="#">
            Connexion
          </b-nav-item>
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>
  </div>
</template>

<script>
export default {
  name: "Navbar",
  computed: {
    auth() {
      return this.$store.getters.ifAuthenticated;
    },
    authCustomer() {
      return this.$store.getters.ifCustomerAuthenticated;
    },
    authAdministrator() {
      return this.$store.getters.ifAdministratorAuthenticated;
    },
  },
  methods: {
    logout() {
      this.$store.dispatch("logout");
    },
  },
  data() {
    return {
      logo: { width: 80 },
    };
  },
};
</script>

<style scoped>
.navbar.navbar-dark.bg-dark {
  background-color: #3ea3d8 !important;
  font-size: 20px;
}
.navbar-dark .navbar-nav .nav-link {
  color: white;
  -webkit-transition: color 0.2s ease-in;
  -moz-transition: color 0.2s ease-in;
  -o-transition: color 0.2s ease-in;
  transition: color 0.2s ease-in;
  text-align: center;
}
.navbar-dark .navbar-nav .router-link-exact-active {
  color: #e08f25;
  font-weight: 500;
}
.navbar-dark .navbar-nav .nav-link:hover {
  color: #e08f25;
  font-weight: 500;
}
.navbar-collapse .nav-item .nav-link {
  margin: auto;
  font-weight: 500;
}
</style>
