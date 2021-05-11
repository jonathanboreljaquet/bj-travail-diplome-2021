<template>
<div class="content">
  <b-container>
    <b-row align-h="center">
      <b-col class="text-content" cols="12" sm="6">
        <h1 class="font-weight-bold display-4">{{ textH1 }}</h1>
        <h3 class="font-weight-bold">{{ textH3 }}</h3>
        <h5>{{ textH5 }}</h5>
        <h6>{{ textH6 }}</h6>
        <p class="text-justify">{{ textParagraph }}</p>
        <b-button id="button" :to="routeBouton" pill v-if="textButton != ''"> {{ textButton }}</b-button>
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
    props: ['textParagraph','textH1','textH3','textH5','textH6','routeBouton','textButton','imageSource'] ,
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
padding-bottom: 20px;
color: #3ea3d8;
}
#button{
  background-color:#3ea3d8 ;
}
</style>
