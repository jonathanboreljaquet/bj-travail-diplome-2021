<template>
  <div class="ComponnentContent">
    <b-container>
      <b-row class="text-center">
        <b-col>
          <h1>{{ title }}</h1>
        </b-col>
      </b-row>
      <b-row v-if="appoitments.length > 0" class="content">
        <b-col>
          <div class="card">
            <div class="card-body">
              <div id="content">
                <ul class="timeline">
                  <li
                    class="event"
                    v-for="appoitment in appoitments"
                    :key="appoitment.id"
                    :data-date="
                      appoitment.hourStart + ' - ' + appoitment.hourEnd
                    "
                  >
                    <h4>{{ appoitment.date }}</h4>
                    <h3>Résumé du rendez-vous</h3>
                    <p v-if="appoitment.summary != null">
                      {{ appoitment.summary }}
                    </p>
                    <p v-else>Aucune réssumé</p>
                    <div v-if="authAdministrator">
                      <h3>Notes textuelles personnelles du rendez-vous</h3>
                      <p v-if="appoitment.noteText != null">
                        {{ appoitment.noteText }}
                      </p>
                      <p v-else>Aucunes notes textuelles</p>
                      <!-- <div v-if="appoitment.noteGraphicalSerialId">
                        <h3>Notes graphiques du rendez-vous</h3>
                        <b-img :src="image" alt="Image"
                          fluid
                        ></b-img>
                      </div> -->
                      <b-button
                        variant="outline-primary"
                        block
                        v-b-modal.modal-update-appoitment-informations
                        @click="
                          sendAppoitmentInformations(
                            appoitment.id,
                            appoitment.summary,
                            appoitment.noteText,
                            appoitment.noteGraphicalSerialId
                          )
                        "
                      >
                        Modifier les notes
                      </b-button>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </b-col>
      </b-row>
      <b-row v-else class="content">
        <b-col>
          {{ messageNoAppoitments }}
        </b-col>
      </b-row>
      <button-return
        v-if="authAdministrator"
        :to="{
          name: 'customerInformation',
          params: { userId: $route.params.userId },
        }"
      ></button-return>

      <!-- MODAL UPDATE APPOITMENT INFORMATIONS-->
      <b-modal
        id="modal-update-appoitment-informations"
        title="Modifier le notes du rendez-vous"
        :hide-footer="true"
      >
        <b-form
          @submit.prevent="
            updateAppoitmentByAppoitmentId(
              selectedAppoitmentId,
              selectedAppoitmentSummary,
              selectedAppoitmentNoteText
            )
          "
        >
          <b-form-group
            id="input-group-appoitment-summary"
            label="Résumé :"
            label-for="input-appoitment-summary"
          >
            <b-form-textarea
              id="input-appoitment-summary"
              v-model="selectedAppoitmentSummary"
              placeholder="Entrer le résumé du cours"
              rows="8"
              max-rows="8"
            ></b-form-textarea>
          </b-form-group>

          <b-form-group
            id="input-group-appoitment-note-text"
            label="Notes textuelles personnelles :"
            label-for="input-appoitment-note-text"
          >
            <b-form-textarea
              id="input-appoitment-note-text"
              v-model="selectedAppoitmentNoteText"
              placeholder="Entrer les notes textuelles du cours"
              rows="8"
              max-rows="8"
            ></b-form-textarea>
          </b-form-group>

          <b-button block type="submit" variant="outline-primary">
            Modifier les notes
          </b-button>
        </b-form>
      </b-modal>
    </b-container>
  </div>
</template>

<script>
import ButtonReturn from "./ButtonReturn.vue";
import "@/assets/css/timeline.css";

export default {
  name: "CustomerAppoitment",
  components: {
    ButtonReturn,
  },
  data() {
    return {
      title: "",
      appoitments: [],
      messageNoAppoitments: "Aucun rendez-vous",
      selectedAppoitmentId: "",
      selectedAppoitmentSummary: "",
      selectedAppoitmentNoteText: "",
      selectedAppotimentNoteGraphicalSerialId: "",
    };
  },
  methods: {
    loadAuthCustomerAppoitment() {
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token
        },
      };
      this.$http
        .get(this.$API_URL + "appoitments/", config)
        .then((response) => {
          console.log(response);
          this.loadAppoitmentsData(response.data);
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    loadCustomerInformationsByUserId(userId) {
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token
        },
      };
      this.$http
        .get(this.$API_URL + "users/" + userId, config)
        .then((response) => {
          console.log(response);
          this.loadAppoitmentsData(response.data.appoitments);
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    loadAppoitmentsData(appoitments) {
      this.appoitments = [];
      appoitments.forEach((appoitment) => {
        let date = new Date(appoitment.datetime_appoitment);
        var options = {
          weekday: "long",
          year: "numeric",
          month: "long",
          day: "numeric",
        };
        this.appoitments.push({
          id: appoitment.id,
          hourStart: date.getHours() + "h",
          hourEnd: date.getHours() + appoitment.duration_in_hour + "h",
          date: date.toLocaleDateString("fr-CH", options),
          summary: appoitment.summary,
          noteText: appoitment.note_text,
          noteGraphicalSerialId: appoitment.note_graphical_serial_id,
        });
      });
    },
    loadNoteGraphicalPicture() {
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token
        },
      };
      this.$http
        .get(
          this.$API_URL + "appoitments/downloadNoteGraphical/oshAZXLb",
          config
        )
        .then((response) => {
          this.image = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    updateAppoitmentByAppoitmentId(
      appoitmentId,
      appoitmentSummary,
      appoitmentNoteText
    ) {
      const params = new URLSearchParams();
      params.append("summary", appoitmentSummary);
      params.append("note_text", appoitmentNoteText);
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .patch(this.$API_URL + "appoitments/" + appoitmentId, params, config)
        .then((response) => {
          console.log(response);
          this.loadCustomerInformationsByUserId(this.$route.params.userId);
          this.$alertify.success("Informfations de rendez-vous modifiés");
          this.$bvModal.hide("modal-update-appoitment-informations");
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },

    sendAppoitmentInformations(
      appoitmentId,
      appoitmentSummary,
      appoitmentNoteText,
      appotimentNoteGraphicalSerialId
    ) {
      this.selectedAppoitmentId = appoitmentId;
      this.selectedAppoitmentSummary = appoitmentSummary;
      this.selectedAppoitmentNoteText = appoitmentNoteText;
      this.selectedAppotimentNoteGraphicalSerialId =
        appotimentNoteGraphicalSerialId;
    },
  },
  computed: {
    authAdministrator() {
      return this.$store.getters.ifAdministratorAuthenticated;
    },
  },
  mounted() {
    //this.loadNoteGraphicalPicture();
    if (this.authAdministrator && this.$route.params.userId) {
      this.title = "Rendez-vous du client";
      this.loadCustomerInformationsByUserId(this.$route.params.userId);
    } else {
      this.title = "Mes rendez-vous";
      this.loadAuthCustomerAppoitment();
    }
  },
};
</script>

<style scoped>
.ComponnentContent {
  margin-top: 20px;
  color: #3ea3d8;
}
</style>
