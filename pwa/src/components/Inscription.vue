<template>
  <div class="content">
    <b-container>
      <b-row id="title" class="text-center">
        <b-col>
          <h1>S'incrire</h1>
        </b-col>
      </b-row>
      <b-row class="text-center text-secondary">
        <b-col>
          Déjà membre ?
          <router-link to="/connection">
            <span role="link">Se connecter</span>
          </router-link>
        </b-col>
      </b-row>
      <b-row id="title" class="justify-content-center">
        <b-col lg="6">
          <b-form @submit.prevent="onSubmit">
            <b-form-group
              id="input-group-1"
              label="Adresse email :"
              label-for="input-1"
            >
              <b-form-input
                id="input-1"
                v-model="form.email"
                type="email"
                placeholder="Entrez l'adresse email"
                required
              ></b-form-input>
            </b-form-group>
            <b-form-group
              id="input-group-2"
              label="Prénom :"
              label-for="input-2"
            >
              <b-form-input
                id="input-2"
                v-model="form.firstname"
                type="text"
                placeholder="Entrez votre prénom"
                required
              ></b-form-input>
            </b-form-group>
            <b-form-group
              id="input-group-3"
              label="Nom de famille :"
              label-for="input-3"
            >
              <b-form-input
                id="input-3"
                v-model="form.lastname"
                type="text"
                placeholder="Entrez votre nom de famille"
                required
              ></b-form-input>
            </b-form-group>
            <b-form-group
              id="input-group-4"
              label="Numéro de téléphone :"
              label-for="input-4"
            >
              <b-form-input
                id="input-4"
                v-model="form.phonenumber"
                type="text"
                placeholder="Entrez votre numéro de téléphone"
                required
              ></b-form-input>
            </b-form-group>
            <b-form-group
              id="input-group-5"
              label="Adresse :"
              label-for="input-5"
            >
              <b-form-input
                id="input-5"
                v-model="form.address"
                type="text"
                placeholder="Entrez votre addresse"
                required
              ></b-form-input>
            </b-form-group>
            <b-form-group
              id="input-group-6"
              label="Mot de passe :"
              label-for="input-6"
            >
              <b-form-input
                id="input-6"
                v-model="form.password"
                type="password"
                placeholder="Entrez le mot de passe"
                required
              ></b-form-input>
            </b-form-group>
            <b-form-group
              id="input-group-7"
              label="Confirmation du mot de passe :"
              label-for="input-7"
            >
              <b-form-input
                id="input-7"
                v-model="form.repeatPassword"
                type="password"
                placeholder="Entrez le mot de passe"
                required
              ></b-form-input>
            </b-form-group>
            <b-form-group
              id="input-group-8"
              label="Captcha :"
              label-for="input-8"
            >
              <label for="robot"></label>
              <vue-recaptcha
                ref="recaptcha"
                @verify="onVerify"
                sitekey="6LdDq9saAAAAAG7jxON5LMecQiHSpHXddkE6-Jek"
              >
              </vue-recaptcha>
            </b-form-group>
            <b-button block type="submit" variant="outline-primary">
              Inscription
            </b-button>
          </b-form>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
import VueRecaptcha from "vue-recaptcha";

export default {
  name: "Inscription",
  components: {
    VueRecaptcha,
  },
  data() {
    return {
      form: {
        email: "",
        firstname: "",
        lastname: "",
        phonenumber: "",
        address: "",
        password: "",
        repeatPassword: "",
        robot: false,
      },
    };
  },
  methods: {
    onVerify(response) {
      if (response) {
        this.form.robot = true;
      }
    },
    onSubmit() {
      if (this.form.password != this.form.repeatPassword) {
        this.$alertify.error("les mots de passes ne corréspondent pas");
        return;
      }

      if (!this.form.robot) {
        this.$alertify.error("la vérification du captcha a échoué");
        return;
      }

      this.$store.dispatch("inscription", {
        email: this.form.email,
        firstname: this.form.firstname,
        lastname: this.form.lastname,
        phonenumber: this.form.phonenumber,
        address: this.form.address,
        password: this.form.password,
      });
    },
  },
  mounted() {
    let recaptchaScript = document.createElement("script");
    recaptchaScript.setAttribute(
      "src",
      "https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit"
    );
    document.head.appendChild(recaptchaScript);
  },
};
</script>

<style scoped>
#title {
  margin-top: 20px;
  color: #3ea3d8;
}
</style>
