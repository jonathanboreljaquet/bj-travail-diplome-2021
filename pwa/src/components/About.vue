<!--
  About.vue

  Component representing the page about of the application.

  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
-->

<template>
  <div>
    <left-section-content
      :textH1="texth1LeftSectionContent"
      :textH5="texth5LeftSectionContent"
      :textParagraph="textParagraphLeftSectionContent"
      :imageSource="imageSourceLeftSectionContent"
    ></left-section-content>
    <right-section-content
      :textH5="texth5RightSectionContent"
      :textParagraph="textParagraphRightSectionContent"
      :routeBouton="routeBoutonRightSectionContent"
      :imageSource="imageSourceRightSectionContent"
    ></right-section-content>
  </div>
</template>

<script>
import LeftSectionContent from "./LeftSectionContent.vue";
import BorisEducator from "../assets/img/boriseducateurcanin.png";
import RightSectionContent from "./RightSectionContent.vue";
import BorisWithHyron from "../assets/img/boriswithhyron.png";

export default {
  components: { LeftSectionContent, RightSectionContent },
  name: "About",
  data() {
    return {
      texth1LeftSectionContent: "Bonjour, je suis Boris Gourdoux",
      texth5LeftSectionContent: "+ de 300 couples Maître/chien à mon actif",
      textParagraphLeftSectionContent:
        "Depuis ma toute petite enfance, ma relation avec le chien à été salvatrice. Elle m'a permis de dépasser bien des difficultés et de trouver une véritable complicité. Diplômé et spécialisé en rééducation et comportement, vous bénéficiez de toute mon expertise et expérience dans l'éducation canine, fruit de nombreuses formations dans ce qui se fait de mieux dans le domaine. L'objectif chez Douceur de Chien est de vous permettre de créer un lien renforcé et durable avec votre compagnon à 4 pattes et ceci en utilisant uniquement de la patience, de la cohérence et beaucoup de passion. Ainsi chez Douceur de Chien vous n'obtiendrez aucun conseil sur l'utilisation de colliers étrangleurs ou même de la force pour éduquer votre chien.",
      imageSourceLeftSectionContent: BorisEducator,

      texth5RightSectionContent: "Une méthode moderne",
      textParagraphRightSectionContent:
        "Je base ma méthode en respectant les besoins et l'émotionnel du chien. C'est pourquoi, je met l'accent sur une coopération totale de ce dernier en lui permettant de faire ses propres choix et ainsi vous permettre d'obtenir de votre chien, écoute et attention en toutes circonstances.",
      imageSourceRightSectionContent: BorisWithHyron,
      routeBoutonRightSectionContent: "about",
    };
  },
};
</script>

<style scoped></style>
