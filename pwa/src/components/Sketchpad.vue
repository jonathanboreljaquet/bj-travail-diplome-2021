<template>
  <div class="content">
    <b-container id="container">
      <div id="sketchpad" ref="sketchpad"></div>
      <b-row id="buttonRow" class="text-center">
        <b-col>
          <b-button
            block
            type="button"
            @click="getSketchpad"
            variant="outline-success"
          >
            Charger
          </b-button>
        </b-col>
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
    };
  },
  mounted() {
    this.pad = new Sketchpad(this.$refs["sketchpad"], {
      line: {
        color: "#f44335",
        size: 5,
      },
    });
    this.pad.setLineColor("#000000");
  },
  methods: {
    getSketchpad() {
      var dataURL = this.pad.toDataURL();
      this.$emit("saveSignature", dataURL);
    },
    clearSketchpad() {
      this.pad.clear();
    },
  },
};
</script>

<style scoped>
#sketchpad {
  border: 0.2rem solid #c2c2c2;
}
#container {
  padding: 0px;
}
#buttonRow {
  margin-top: 10px;
  margin-bottom: 10px;
}
</style>
