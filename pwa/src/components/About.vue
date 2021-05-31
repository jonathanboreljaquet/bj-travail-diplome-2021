<template>
  <div>
    <left-section-content
      :textH1="texth1content1"
      :textH3="texth3content1"
      :textH5="texth5content1"
      :textH6="texth6content1"
      :textParagraph="textParagraphContent1"
      :routeBouton="routeBouton1"
      :textButton="textButtonContent1"
      :imageSource="imageSource1"
    ></left-section-content>
    <right-section-content
      :textH1="texth1content2"
      :textH3="texth3content2"
      :textH5="texth5content2"
      :textH6="texth6content2"
      :textParagraph="textParagraphContent2"
      :routeBouton="routeBouton2"
      :textButton="textButtonContent2"
      :imageSource="imageSource2"
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
      texth1content1: "Bonjour, je suis Boris Gourdoux",
      texth3content1: "",
      texth5content1: "+ de 300 couples Maître/chien à mon actif",
      texth6content1: "",
      textButtonContent1: "",
      textParagraphContent1:
        "Depuis ma toute petite enfance, ma relation avec le chien à été salvatrice. Elle m'a permis de dépasser bien des difficultés et de trouver une véritable complicité. Diplômé et spécialisé en rééducation et comportement, vous bénéficiez de toute mon expertise et expérience dans l'éducation canine, fruit de nombreuses formations dans ce qui se fait de mieux dans le domaine. L'objectif chez Douceur De Chien est de vous permettre de créer un lien renforcé et durable avec votre compagnon à 4 pattes et ceci en utilisant uniquement de la patience, de la cohérence et beaucoup de passion. Ainsi chez Douceur De Chien vous n'obtiendrez aucun conseil sur l'utilisation de colliers étrangleur ou même de la force pour éduquer votre chien.",
      imageSource1: BorisEducator,
      routeBouton1: "",

      texth1content2: "",
      texth3content2: "",
      texth5content2: "Une méthode moderne",
      texth6content2: "",
      textButtonContent2: "",
      textParagraphContent2:
        "Je base ma méthode en respectant les besoins et l'émotionnel du chien, c'est pourquoi je met l'accent sur une coopération totale de ce dernier en lui permettant de faire ces propres choix et ainsi VOUS permettre d'obtenir de votre chien, écoute et attention en toutes circonstances",
      imageSource2: BorisWithHyron,
      routeBouton2: "about",
    };
  },
};
</script>

<style scoped></style>
