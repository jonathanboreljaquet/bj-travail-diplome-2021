<!--
  Home.vue

  Component representing the home page of the application.

  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
-->

<template>
  <div>
    <left-section-content
      :textH1="texth1LeftSectionContent"
      :textH3="texth3LeftSectionContent"
      :textH5="texth5LeftSectionContent"
      :textH6="texth6LeftSectionContent"
      :routeBouton="routeBoutonLeftSectionContent"
      :textButton="textButtonLeftSectionContent"
      :imageSource="imageSourceLeftSectionContent"
    ></left-section-content>
    <right-section-content
      :textH3="texth3RightSectionContent"
      :textParagraph="textParagraphRightSectionContent"
      :routeBouton="routeBoutonRightSectionContent"
      :textButton="textButtonRightSectionContent"
      :imageSource="imageSourceRightSectionContent"
    ></right-section-content>
  </div>
</template>

<script>
import LeftSectionContent from "./LeftSectionContent.vue";
import Hyron from "../assets/img/hyron.png";
import RightSectionContent from "./RightSectionContent.vue";
import BorisWithDogs from "../assets/img/borisWithDogs.png";

export default {
  components: { LeftSectionContent, RightSectionContent },
  name: "Home",
  data() {
    return {
      texth1LeftSectionContent: "Douceur de chien",
      texth3LeftSectionContent: "Educateur & comportementaliste canin",
      texth5LeftSectionContent: "Vous voulez prendre rendez-vous ?",
      texth6LeftSectionContent: "Accéder aux agendas des éducateurs canins",
      textButtonLeftSectionContent: "Agendas",
      imageSourceLeftSectionContent: Hyron,
      routeBoutonLeftSectionContent: "calendar",

      texth3RightSectionContent: "À propos de nous",
      textButtonRightSectionContent: "Lire plus",
      textParagraphRightSectionContent:
        "Je m'appelle Boris Gourdoux, je suis le fondateur de Douceur de Chien. Accompagné de Hyron, Staffie mâle et Jaya, Rodhesian Ridgeback femelle, je fais de l'éducation bienveillante et moderne mon leitmotiv afin d'aider tous les maîtres de chiens qui feront appel à mes services. Formé et diplômé en rééducation comportementale, je me ferais un plaisir de vous accompagner dans l'éducation de votre chien.",
      imageSourceRightSectionContent: BorisWithDogs,
      routeBoutonRightSectionContent: "about",
    };
  },
};
</script>

<style scoped></style>
