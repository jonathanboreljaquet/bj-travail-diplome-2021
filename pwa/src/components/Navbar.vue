<template>
  <div>
    <b-navbar fixed="top" toggleable="lg" type="dark" variant="dark">
      <b-img
        src="../assets/img/logoblancblanc.png"
        v-bind="logo"
        fluid
        alt="Responsive image"
      ></b-img>
      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
      <b-collapse id="nav-collapse" is-nav>
        <b-navbar-nav>
          <b-nav-item class="navbar-style" to="/" href="#">Accueil</b-nav-item>
          <b-nav-item to="/about" href="#">À propos</b-nav-item>
          <b-nav-item to="/calendar" href="#">Agenda</b-nav-item>
          <b-nav-item
            @click="installer()"
            :style="{ ' display ': installBtn }"
            href="#"
            >Installer
          </b-nav-item>
        </b-navbar-nav>
        <b-navbar-nav class="ml-auto">
          <b-nav-item id="logoutBtn" v-if="auth" @click="logout()" href="#">
            Deconnexion
          </b-nav-item>
          <b-nav-item id="loginBtn" v-if="!auth" to="/connection" href="#">
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
  },
  methods: {
    logout() {
      this.$store.dispatch("logout");
    },
  },
  data() {
    return {
      logo: { width: 80 },
      installBtn: "none",
      installer: undefined,
    };
  },
  created() {
    let installPrompt;

    window.addEventListener("beforeinstallprompt", (e) => {
      e.preventDefault();
      installPrompt = e;
      this.installBtn = "block";
    });

    this.installer = () => {
      this.installBtn = "none";
      installPrompt.prompt();
      installPrompt.userChoice.then((result) => {
        if (result.outcome === "accepted") {
          console.log("Install accepted!");
        } else {
          console.log("Install denied!");
        }
      });
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
}
.navbar-dark .navbar-nav .router-link-exact-active {
  color: #ee8d83;
}
.navbar-dark .navbar-nav .nav-link:hover {
  color: #ee8d83;
}
.navbar-dark .navbar-nav #logoutBtn .nav-link {
  color: red;
}
.navbar-dark .navbar-nav #loginBtn .nav-link {
  color: green;
}
</style>
