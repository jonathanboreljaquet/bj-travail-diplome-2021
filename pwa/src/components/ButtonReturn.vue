<!--
  ButtonReturn.vue

  Reusable component representing a static button on a page allowing to redirect the user to another component.

  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
-->

<template>
  <div>
    <b-button class="btnReturn" :to="to">
      <p class="h4">
        <b-icon-arrow-return-left></b-icon-arrow-return-left>
      </p>
    </b-button>
  </div>
</template>

<script>
import { BIconArrowReturnLeft } from "bootstrap-vue";

export default {
  components: {
    BIconArrowReturnLeft,
  },
  name: "ButtonReturn",
  props: {
    to: Object,
  },
};
</script>

<style scoped>
.btnReturn {
  padding-top: 18px;
  position: fixed;
  bottom: 100px;
  right: 15px;
  z-index: 5;
  display: block;
  height: 70px;
  width: 70px;
  border-radius: 50%;
  background-color: #008afc;
  box-shadow: -1px -1px 15px 1px rgba(0, 0, 0, 0.7);
}
</style>
