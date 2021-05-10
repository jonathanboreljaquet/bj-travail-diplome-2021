<template>
<div class="content">
  <b-container>
    <b-row align-h="center">
      <b-col class="text-content" cols="12" sm="6">
        <h1>{{ textH1 }}</h1>
        <h2>{{ textH2 }}</h2>
        <p>{{ textParagraph }}</p>
        <h5>{{ textH5 }}</h5>
        <h6>{{ textH6 }}</h6>
        <b-button id="button" pill v-if="textButton != null"> {{ textButton }}</b-button>
      </b-col>
      <b-col cols="12" sm="6">
        <b-img v-if="imageSource != null" id="circleImage" :src="imageSource" fluid  rounded="circle" alt="Circle image"></b-img>
      </b-col>
      
    </b-row>
  </b-container>
  </div>
</template>

<script>

export default {
    props: ['textParagraph','textH1','textH2','textH5','textH6','textButton','imageSource'] ,
    name: "LeftSectionContent",
};
</script>

<style scoped>
#circleImage{
  width: 440;
  height: 440;
}
.content{
background-color:#fef6ec;
padding-top: 20px;
padding-bottom: 20px
}
#button{
  background-color:#3ea3d8 ;
}
</style>
