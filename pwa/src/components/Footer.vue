<!--
  Footer.vue

  Footer component included in all application previews.

  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
-->

<template>
  <div id="footer">
    <b-container>
      <b-row class="text-center">
        <b-col>
          <h1>Contact</h1>
        </b-col>
      </b-row>
      <b-row class="text-center">
        <b-col cols="12" sm="4">
          <h5>Téléphone</h5>
          <h6><a href="tel:+330624003368">06.24.00.33.68</a></h6>
        </b-col>
        <b-col cols="12" sm="4">
          <h5>Email</h5>
          <h6>
            <a href="mailto:douceurdechien@douceurdechien.com"
              >contact@douceurdechien.com</a
            >
          </h6>
        </b-col>
        <b-col cols="12" sm="4">
          <h5>Zone d'intervention</h5>
          <h6>Montréjeau et alentours</h6>
        </b-col>
      </b-row>
    </b-container>
    <b-container fluid>
      <b-row class="text-center">
        <b-col style="background-color: white; color: #3ea3d8">
          ©2021 Douceur de chien -
          <RouterLink class="link" to="/privacy_policy"
            >politique de confidentialité</RouterLink
          >
          -
          <RouterLink class="link" to="/privacy_policy"
            >conditions d'utilisation
          </RouterLink>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
export default {
  name: "Footer",
};
</script>

<style scoped>
a {
  color: white;
  text-decoration: underline;
}
#footer {
  background-color: #3ea3d8;
  color: white;
  margin-top: 10px;
}
.link {
  color: #0051ff;
}
</style>
