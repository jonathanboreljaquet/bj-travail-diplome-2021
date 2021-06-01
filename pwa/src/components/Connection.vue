<template>
  <div class="content">
    <b-container>
      <b-row id="title" class="text-center">
        <b-col>
          <h1>Se connecter</h1>
        </b-col>
      </b-row>
      <b-row class="text-center text-secondary">
        <b-col>
          Nouveau sur ce site ?
          <router-link to="/inscription">
            <span role="link">S'inscrire</span>
          </router-link>
        </b-col>
      </b-row>
      <b-row id="title" class="justify-content-center">
        <b-col lg="6">
          <b-form @submit.prevent="onSubmit" autocomplete="on">
            <b-form-group
              id="input-group-1"
              label="Adresse email:"
              label-for="input-1"
            >
              <b-form-input
                id="input-1"
                v-model="form.email"
                type="email"
                placeholder="Entrez l'adresse email"
                autocomplete="email"
                required
              ></b-form-input>
            </b-form-group>
            <b-form-group
              id="input-group-2"
              label="Mot de passe :"
              label-for="input-2"
            >
              <b-form-input
                id="input-2"
                v-model="form.password"
                type="password"
                placeholder="Entrez le mot de passe"
                autocomplete="current-password"
                required
              ></b-form-input>
            </b-form-group>
            <b-button block type="submit" variant="outline-primary">
              Connexion
            </b-button>
          </b-form>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
export default {
  name: "Connection",
  data() {
    return {
      form: {
        email: "",
        password: "",
      },
    };
  },
  methods: {
    onSubmit() {
      this.$store.dispatch("login", {
        email: this.form.email,
        password: this.form.password,
      });
    },
  },
};
</script>

<style scoped>
#title {
  margin-top: 20px;
  color: #3ea3d8;
}
</style>
