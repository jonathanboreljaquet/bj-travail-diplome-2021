<template>
  <div class="ComponnentContent">
    <b-container>
      <b-row class="text-center">
        <b-col>
          <h1>{{ title }} {{ customerFirstname }} {{ customerLastname }}</h1>
        </b-col>
      </b-row>
      <b-row v-if="appointments.length > 0" class="content">
        <b-col>
          <div class="card">
            <div class="card-body">
              <div id="content">
                <ul class="timeline">
                  <li
                    class="event"
                    v-for="appointment in appointments"
                    :key="appointment.id"
                    :data-date="
                      appointment.hourStart + ' - ' + appointment.hourEnd
                    "
                  >
                    <h4>{{ appointment.date }}</h4>
                    <h3>Résumé du rendez-vous</h3>
                    <p v-if="appointment.summary != null">
                      {{ appointment.summary }}
                    </p>
                    <p v-else>Aucune réssumé</p>
                    <div v-if="authAdministrator">
                      <h3>Notes textuelles personnelles du rendez-vous</h3>
                      <p v-if="appointment.noteText != null">
                        {{ appointment.noteText }}
                      </p>
                      <p v-else>Aucunes notes textuelles</p>
                      <!-- <div v-if="appointment.noteGraphicalSerialId">
                        <h3>Notes graphiques du rendez-vous</h3>
                        <b-img :src="'data:image/jpeg;base64,'+arrayimg['oshAZXL']" alt="Image"
                          fluid
                        ></b-img>
                      </div> -->
                      <b-button
                        variant="outline-primary"
                        block
                        v-b-modal.modal-update-appointment-informations
                        @click="
                          sendAppointmentInformations(
                            appointment.id,
                            appointment.summary,
                            appointment.noteText,
                            appointment.noteGraphicalSerialId
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
          {{ messageNoAppointments }}
        </b-col>
      </b-row>
      <button-return
        v-if="authAdministrator"
        :to="{
          name: 'customerInformation',
          params: { userId: $route.params.userId },
        }"
      ></button-return>

      <!-- MODAL UPDATE APPOINTMENT INFORMATIONS-->
      <b-modal
        id="modal-update-appointment-informations"
        title="Modifier le notes du rendez-vous"
        :hide-footer="true"
      >
        <b-form
          @submit.prevent="
            updateAppointmentByAppointmentId(
              selectedAppointmentId,
              selectedAppointmentSummary,
              selectedAppointmentNoteText
            )
          "
        >
          <b-form-group
            id="input-group-appointment-summary"
            label="Résumé :"
            label-for="input-appointment-summary"
          >
            <b-form-textarea
              id="input-appointment-summary"
              v-model="selectedAppointmentSummary"
              placeholder="Entrer le résumé du cours"
              rows="8"
              max-rows="8"
            ></b-form-textarea>
          </b-form-group>

          <b-form-group
            id="input-group-appointment-note-text"
            label="Notes textuelles personnelles :"
            label-for="input-appointment-note-text"
          >
            <b-form-textarea
              id="input-appointment-note-text"
              v-model="selectedAppointmentNoteText"
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
  name: "CustomerAppointment",
  components: {
    ButtonReturn,
  },
  data() {
    return {
      title: "",
      appointments: [],
      messageNoAppointments: "Aucun rendez-vous",
      customerFirstname: "",
      customerLastname: "",
      selectedAppointmentId: "",
      selectedAppointmentSummary: "",
      selectedAppointmentNoteText: "",
      selectedAppotimentNoteGraphicalSerialId: "",
      arrayimg: {},
    };
  },
  methods: {
    loadAuthCustomerAppointment() {
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token
        },
      };
      this.$http
        .get(this.$API_URL + "appointments/", config)
        .then((response) => {
          console.log(response);
          this.loadAppointmentsData(response.data);
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
          this.customerFirstname = response.data.firstname;
          this.customerLastname = response.data.lastname;

          this.loadAppointmentsData(response.data.appointments);
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    loadAppointmentsData(appointments) {
      this.appointments = [];
      appointments.forEach((appointment) => {
        var date = new Date(appointment.datetime_appointment);
        var options = {
          weekday: "long",
          year: "numeric",
          month: "long",
          day: "numeric",
        };
        this.appointments.push({
          id: appointment.id,
          hourStart: date.getHours() + "h",
          hourEnd: date.getHours() + appointment.duration_in_hour + "h",
          date: date.toLocaleDateString("fr-CH", options),
          summary: appointment.summary,
          noteText: appointment.note_text,
          noteGraphicalSerialId: appointment.note_graphical_serial_id,
        });
        if (appointment.note_graphical_serial_id) {
          this.loadNoteGraphicalPicture(appointment.note_graphical_serial_id);
        }
      });
    },
    loadNoteGraphicalPicture(noteGraphicalSerialId) {
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token
        },
        responseType: "arraybuffer",
      };
      this.$http
        .get(
          this.$API_URL + "appointments/downloadNoteGraphical/" + noteGraphicalSerialId,
          config
        )
        .then((response) => {
          var base64 = Buffer.from(response.data, "binary").toString("base64");
          this.arrayimg[noteGraphicalSerialId] = base64;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    updateAppointmentByAppointmentId(
      appointmentId,
      appointmentSummary,
      appointmentNoteText
    ) {
      const params = new URLSearchParams();
      params.append("summary", appointmentSummary);
      params.append("note_text", appointmentNoteText);
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .patch(this.$API_URL + "appointments/" + appointmentId, params, config)
        .then((response) => {
          console.log(response);
          this.loadCustomerInformationsByUserId(this.$route.params.userId);
          this.$alertify.success("Informfations de rendez-vous modifiés");
          this.$bvModal.hide("modal-update-appointment-informations");
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },

    sendAppointmentInformations(
      appointmentId,
      appointmentSummary,
      appointmentNoteText,
      appotimentNoteGraphicalSerialId
    ) {
      this.selectedAppointmentId = appointmentId;
      this.selectedAppointmentSummary = appointmentSummary;
      this.selectedAppointmentNoteText = appointmentNoteText;
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
    if (this.authAdministrator && this.$route.params.userId) {
      this.title = "Rendez-vous du client";
      this.loadCustomerInformationsByUserId(this.$route.params.userId);
    } else {
      this.title = "Mes rendez-vous";
      this.loadAuthCustomerAppointment();
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
