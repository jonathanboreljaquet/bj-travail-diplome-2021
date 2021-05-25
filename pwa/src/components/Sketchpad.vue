<template>
  <div class="content">
    <b-container id="container">
      <div :class="isBlocked ? blocked : sketchpad" ref="sketchpad"></div>
      <b-row id="buttonRow" class="text-center">
        <b-col>
          <b-button
            block
            type="button"
            @click="clearSketchpad"
            variant="outline-danger"
          >
            Effacer
          </b-button>
        </b-col>
        <b-col>
          <b-button
            :disabled="isBlocked"
            block
            type="button"
            @click="getSketchpad"
            variant="outline-success"
          >
            Valider la signature
          </b-button>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
import Sketchpad from "responsive-sketchpad";

export default {
  name: "Sketchpad",
  data() {
    return {
      pad: null,
      dataURL: "",
      isBlocked: false,
      blocked: "blockedSketchpad",
      sketchpad: "sketchpad",
    };
  },
  mounted() {
    this.pad = new Sketchpad(this.$refs["sketchpad"]);
  },
  methods: {
    getSketchpad() {
      this.isBlocked = true;
      this.pad.setReadOnly(true);
      this.dataURL = this.pad.toDataURL();
      this.$emit("getSignature", this.dataURL, this.isBlocked);
    },
    clearSketchpad() {
      this.pad.clear();
      this.isBlocked = false;
      this.pad.setReadOnly(false);
      this.$emit("getSignature", this.dataURL, this.isBlocked);
    },
  },
};
</script>

<style scoped>
.sketchpad {
  border: 0.2rem solid #c2c2c2;
  background-color: #ffffff !important;
}
.blockedSketchpad {
  border: 0.2rem solid #c2c2c2;
  background-color: #cccccc !important;
}
#container {
  padding: 0px;
}
#buttonRow {
  margin-top: 10px;
  margin-bottom: 10px;
}
</style>
