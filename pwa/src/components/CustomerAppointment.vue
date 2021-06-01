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
                    <p v-else>Aucune résumé</p>
                    <div v-if="authAdministrator">
                      <div style="position: absolute; top: 5px; right: 5px">
                        <b-button
                          variant="danger"
                          v-b-modal.modal-delete-appointment
                          @click="
                            sendAppointmentInformations(
                              appointment.id,
                              appointment.summary,
                              appointment.noteText,
                              appointment.noteGraphicalSerialId
                            )
                          "
                        >
                          <b-icon-trash></b-icon-trash>
                        </b-button>
                      </div>
                      <h3>Notes textuelles personnelles du rendez-vous</h3>
                      <p v-if="appointment.noteText != null">
                        {{ appointment.noteText }}
                      </p>
                      <p v-else>Aucunes notes textuelles</p>
                      <b-button
                        variant="outline-primary"
                        block
                        v-b-modal.modal-graphical-note
                        @click="
                          sendAppointmentInformations(
                            appointment.id,
                            appointment.summary,
                            appointment.noteText,
                            appointment.noteGraphicalSerialId
                          )
                        "
                      >
                        Notes graphiques
                      </b-button>
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
                        Notes textuelles
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

      <!-- MODAL GRAPHICAL NOTE-->
      <b-modal
        id="modal-graphical-note"
        modal-class="modal-fullscreen"
        title="Notes graphiques"
        :hide-footer="true"
        @shown="
          $refs.signaturePad.resizeCanvas();
          loadNoteGraphicalPicture(selectedAppointmentNoteGraphicalSerialId);
        "
      >
        <b-form
          style="width: 100%"
          @submit.prevent="
            uploadNoteGraphicalByAppointmentId(
              selectedAppointmentId,
              $refs.signaturePad.saveSignature('image/jpeg').data
            )
          "
        >
          <VueSignaturePad
            id="signature"
            width="100%"
            height="80%"
            :customStyle="customStyle"
            ref="signaturePad"
            :options="{ backgroundColor: 'rgb(255, 255, 255)' }"
          />
          <b-row style="margin-top: 10px">
            <b-col>
              <b-button
                block
                @click="clearNoteGraphical"
                variant="outline-danger"
              >
                Effacer
              </b-button>
            </b-col>
            <b-col>
              <b-button block type="submit" variant="outline-success">
                Sauvegarder
              </b-button>
            </b-col>
          </b-row>
        </b-form>
      </b-modal>

      <!-- MODAL DELETE APPOINTMENT-->
      <b-modal
        id="modal-delete-appointment"
        title="Confirmation de supression d'un rendez-vous"
        :hide-footer="true"
        :hide-header="true"
      >
        <h5 style="text-align: center">Supprimer le rendez-vous ?</h5>
        <b-form @submit.prevent="deleteAppointment(selectedAppointmentId)">
          <b-row>
            <b-col>
              <b-button
                block
                variant="danger"
                @click="$bvModal.hide('modal-delete-appointment')"
              >
                Non
              </b-button>
            </b-col>
            <b-col>
              <b-button
                block
                type="submit"
                variant="success"
                @click="$bvModal.hide('modal-delete-appointment')"
              >
                Oui
              </b-button>
            </b-col>
          </b-row>
        </b-form>
      </b-modal>
    </b-container>
  </div>
</template>

<script>
import ButtonReturn from "./ButtonReturn.vue";
import "@/assets/css/timeline.css";
import { BIconTrash } from "bootstrap-vue";
export default {
  name: "CustomerAppointment",
  components: {
    ButtonReturn,
    BIconTrash,
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
      selectedAppointmentNoteGraphicalSerialId: "",
      customStyle: { border: "#c2c2c2 3px solid" },
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
          this.loadAppointmentsData(response.data);
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    loadCustomerAppointmentsByUserId(userId) {
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
          this.$API_URL +
            "appointments/downloadNoteGraphical/" +
            noteGraphicalSerialId,
          config
        )
        .then((response) => {
          var base64 =
            "data:image/png;base64," +
            Buffer.from(response.data, "binary").toString("base64");
          this.$refs.signaturePad.fromDataURL(base64);
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
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
          this.loadCustomerAppointmentsByUserId(this.$route.params.userId);
          this.$alertify.success("Notes graphiques modifiées");
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
      this.selectedAppointmentNoteGraphicalSerialId =
        appotimentNoteGraphicalSerialId;
    },
    uploadNoteGraphicalByAppointmentId(appointmentId, noteGraphicalBase64) {
      fetch(noteGraphicalBase64)
        .then((res) => res.blob())
        .then((blob) => {
          var file = new File([blob], "note", { type: "image/jpeg" });
          let formData = new FormData();
          formData.append("note_graphical", file);
          formData.append("appointment_id", appointmentId);
          const config = {
            headers: {
              // eslint-disable-next-line prettier/prettier
              "Authorization" : this.$store.state.api_token
            },
          };
          this.$http
            .post(
              this.$API_URL + "appointments/uploadNoteGraphical/",
              formData,
              config
            )
            .then((response) => {
              console.log(response);
              this.loadCustomerAppointmentsByUserId(this.$route.params.userId);
              this.$alertify.success("Notes graphiques ajoutées avec succès");
              this.$bvModal.hide("modal-graphical-note");
            })
            .catch((error) => {
              this.$alertify.error(error.response.data.error);
            });
        });
    },
    deleteAppointment(appointmentId) {
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .delete(this.$API_URL + "appointments/" + appointmentId, config)
        .then((response) => {
          this.loadCustomerAppointmentsByUserId(this.$route.params.userId);
          this.$alertify.success("Rendez-vous supprimé");
          console.log(response);
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    clearNoteGraphical() {
      this.$refs.signaturePad.clearSignature();
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
      this.loadCustomerAppointmentsByUserId(this.$route.params.userId);
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
