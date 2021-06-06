<template>
  <div class="content">
    <b-container id="container">
      <VueSignaturePad
        id="pad"
        :customStyle="customStyle"
        ref="signaturePad"
        :options="signaturePadOptions"
      />
      <b-row id="buttonRow" class="text-center">
        <b-col>
          <b-button
            id="btnClearSignature"
            block
            type="button"
            @click="clearSignaturePad"
            variant="outline-danger"
          >
            Effacer
          </b-button>
        </b-col>
        <b-col>
          <b-button
            id="btnValidateSignature"
            :disabled="isBlocked"
            block
            type="button"
            @click="getSignaturePad"
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
export default {
  name: "SignaturePad",
  data() {
    return {
      dataURL: "",
      isBlocked: false,
      customStyle: { border: "#c2c2c2 3px solid" },
      signaturePadOptions: {
        onBegin: () => {
          this.$refs.signaturePad.resizeCanvas();
        },
        backgroundColor: "rgb(255, 255, 255)",
      },
    };
  },
  methods: {
    getSignaturePad() {
      this.isBlocked = true;
      this.$refs.signaturePad.lockSignaturePad();
      this.dataURL = this.$refs.signaturePad.saveSignature("image/jpeg").data;
      this.$emit("getSignaturePad", this.dataURL, this.isBlocked);
    },
    clearSignaturePad() {
      this.$refs.signaturePad.clearSignature();
      this.isBlocked = false;
      this.$refs.signaturePad.openSignaturePad();
      this.$emit("getSignaturePad", this.dataURL, this.isBlocked);
    },
    resizeSignaturePadToSquare() {
      this.$refs.signaturePad.resizeCanvas();
      var padWidth = this.$jquery("#pad").width();
      this.$jquery("#pad").css({ height: padWidth + "px" });
    },
  },
  destroyed() {
    window.removeEventListener("resize", this.resizeSignaturePadToSquare);
  },
  mounted() {
    window.addEventListener("resize", this.resizeSignaturePadToSquare);
    this.resizeSignaturePadToSquare();
  },
};
</script>

<style scoped>
#container {
  padding: 0px;
}
#buttonRow {
  margin-top: 10px;
  margin-bottom: 10px;
}
</style>
