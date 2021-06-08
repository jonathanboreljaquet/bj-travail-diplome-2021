<!--
  LeftSectionContent.vue

  Reusable component representing an aesthetic content displayed with textual content on the left and an image on the right.

  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
-->

<template>
  <div class="content">
    <b-container>
      <b-row>
        <b-col class="text-content" cols="12" sm="12" lg="6">
          <h1 class="font-weight-bold display-4">{{ textH1 }}</h1>
          <h3 class="font-weight-bold">{{ textH3 }}</h3>
          <h5>{{ textH5 }}</h5>
          <h6>{{ textH6 }}</h6>
          <p class="text-justify">{{ textParagraph }}</p>
          <b-button id="button" :to="routeBouton" pill v-if="textButton != ''">
            {{ textButton }}</b-button
          >
        </b-col>
        <b-col
          class="d-flex align-items-center justify-content-center"
          cols="12"
          sm="12"
          lg="6"
        >
          <b-img
            v-if="imageSource != null"
            class="circleImage"
            :src="imageSource"
            fluid
            rounded="circle"
            alt="Circle image"
          ></b-img>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
export default {
  props: {
    textParagraph: {
      type: String,
      default: "",
    },
    textH1: {
      type: String,
      default: "",
    },
    textH3: {
      type: String,
      default: "",
    },
    textH5: {
      type: String,
      default: "",
    },
    textH6: {
      type: String,
      default: "",
    },
    routeBouton: {
      type: String,
      default: "",
    },
    textButton: {
      type: String,
      default: "",
    },
    imageSource: {
      type: String,
      default: "",
    },
  },
  name: "LeftSectionContent",
};
</script>

<style scoped>
.content {
  background-color: #fef6ec;
  padding-top: 20px;
  padding-bottom: 20px;
  color: #3ea3d8;
}
#button {
  background-color: #3ea3d8;
}
</style>
