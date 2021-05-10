<template>
<div>
  <left-section-content :textH1="texth1content1" :textH2="texth2content1" :textH5="texth5content1" :textH6="texth6content1" :textParagraph="textParagraphContent1" :textButton="textButtonContent1" :imageSource="imageSource1"></left-section-content>
  <right-section-content :textH1="texth1content2" :textH2="texth2content2" :textH5="texth5content2" :textH6="texth6content2" :textParagraph="textParagraphContent2" :textButton="textButtonContent2" :imageSource="imageSource2"></right-section-content>
</div>
</template>

<script>
import LeftSectionContent  from './LeftSectionContent.vue';
import Hyron from '../assets/hyron.webp'
import RightSectionContent from './RightSectionContent.vue';
import BorisWithDogs from '../assets/borisWithDogs.webp'
export default {
  components: { LeftSectionContent, RightSectionContent },
  name: "Home",
  data() {
    return {
      texth1content1: "Douceur de chien",
      texth2content1: "Educateur & comportementaliste canin",
      texth5content1: "Vous voulez prendre rendez-vous ?",
      texth6content1: "Accéder aux calendriers des éducateurs canins",
      textButtonContent1: "Calendriers",
      textParagraphContent1: "",
      imageSource1: Hyron,

      texth1content2: "À propos de nous",
      texth2content2: "",
      texth5content2: "",
      texth6content2: "",
      textButtonContent2: "Lire plus",
      textParagraphContent2: "Je m'appelle Boris Gourdoux, je suis le fondateur de Douceur De Chien. Accompagné de Hyron, Staffie mâle et Jaya, Rodhesian Ridgeback femelle. J'ai fais de l'éducation bienveillante et moderne mon leitmotiv afin d'aider tous les Maîtres de chiens qui feront appel à mes services. Formé et diplômé en rééducation comportementale, je me ferais un plaisir de vous accompagner dans l'éducation de votre chien.",
      imageSource2: BorisWithDogs,
    }
  }
};
</script>

<style scoped>
</style>
