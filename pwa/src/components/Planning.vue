<template>
  <div>
    <b-container>
      <b-row>
        <b-col>
          <h1 class="title text-center">Mon planning</h1>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <h3 class="title text-center">Calendriers hebdomadaires</h3>
        </b-col>
      </b-row>
      <b-row>
        <b-table
          table-variant="primary"
          striped
          hover
          :items="weeklySchedules"
          :fields="tblWeeklySchedulesFields"
        >
          <template #cell(créneaux_horaires)="row">
            <b-button
              size="sm"
              @click="row.toggleDetails"
              class="mr-2 tableBtn"
            >
              {{ row.detailsShowing ? "Cacher" : "Afficher" }}
            </b-button>
          </template>
          <template #row-details="row">
            <b-table
              table-variant="light"
              striped
              hover
              :items="row.item.timeSlots"
              :fields="tblTimeSlotsWeeklySchedulesFields"
            >
              <template #cell(supprimer)="row">
                <b-button
                  v-b-modal.modal-delete-timeslot
                  @click="sendTimeSlotId(row.item.id)"
                  variant="danger"
                >
                  <b-icon-trash></b-icon-trash>
                </b-button>
              </template>
            </b-table>
            <b-button
              variant="primary"
              class="bg-light text-primary"
              v-b-modal.modal-add-timeslot-weeklyschedule
              block
              @click="sendWeeklyScheduleId(row.item.id)"
            >
              Ajouter un créneau horaire
            </b-button>
          </template>
          <template #cell(supprimer)="row">
            <b-button
              v-b-modal.modal-delete-weeklyschedule
              @click="sendWeeklyScheduleId(row.item.id)"
              variant="danger"
            >
              <b-icon-trash></b-icon-trash>
            </b-button>
          </template>
        </b-table>
      </b-row>
      <b-row>
        <b-col>
          <b-button v-b-modal.modal-add-weeklyschedule variant="primary" block>
            Ajouter un calendrier hebdomadaire
          </b-button>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <h3 class="title text-center">Exceptions d'horaire</h3>
        </b-col>
      </b-row>
      <b-row>
        <b-table
          table-variant="primary"
          striped
          hover
          :items="scheduleOverrides"
          :fields="tblScheduleOverridesFields"
        >
          <template #cell(créneaux_horaires)="row">
            <b-button
              size="sm"
              @click="row.toggleDetails"
              class="mr-2 tableBtn"
            >
              {{ row.detailsShowing ? "Cacher" : "Afficher" }}
            </b-button>
          </template>
          <template #row-details="row">
            <b-table
              table-variant="light"
              striped
              hover
              :items="row.item.timeSlots"
              :fields="tblTimeSlotsScheduleOverridesFields"
            >
              <template #cell(supprimer)="row">
                <b-button
                  v-b-modal.modal-delete-timeslot
                  @click="sendTimeSlotId(row.item.id)"
                  variant="danger"
                >
                  <b-icon-trash></b-icon-trash>
                </b-button>
              </template>
            </b-table>
            <b-button
              variant="primary"
              class="bg-light text-primary"
              v-b-modal.modal-add-timeslot-scheduleoverride
              block
              @click="sendScheduleOverrideId(row.item.id)"
            >
              Ajouter un créneau horaire
            </b-button>
          </template>
          <template #cell(supprimer)="row">
            <b-button
              v-b-modal.modal-delete-scheduleoverride
              @click="sendScheduleOverrideId(row.item.id)"
              variant="danger"
            >
              <b-icon-trash></b-icon-trash>
            </b-button>
          </template>
        </b-table>
      </b-row>
      <b-row>
        <b-col>
          <b-button
            v-b-modal.modal-add-scheduleoverride
            variant="primary"
            block
          >
            Ajouter une exception d'horaire
          </b-button>
        </b-col>
      </b-row>

      <b-row>
        <b-col>
          <h3 class="title text-center">Vacances</h3>
        </b-col>
      </b-row>
      <b-row>
        <b-table
          table-variant="primary"
          striped
          hover
          :items="absences"
          :fields="tblAbsencesFields"
        >
          <template #cell(supprimer)="row">
            <b-button
              v-b-modal.modal-delete-absence
              @click="sendAbsenceId(row.item.id)"
              variant="danger"
            >
              <b-icon-trash></b-icon-trash>
            </b-button>
          </template>
        </b-table>
      </b-row>
      <b-row>
        <b-col>
          <b-button v-b-modal.modal-add-absence variant="primary" block>
            Ajouter des vacances
          </b-button>
        </b-col>
      </b-row>

      <!-- MODAL ADD WEEKLYSCHEDULE -->
      <b-modal
        id="modal-add-weeklyschedule"
        title="Ajouter un calendrier hebdomadaire"
        :hide-footer="true"
      >
        <b-form
          @submit.prevent="
            createWeeklySchedule(
              formWeeklySchedule.date_valid_from,
              formWeeklySchedule.date_valid_to
            )
          "
        >
          <b-form-group
            id="input-group-weeklyschedule-datevalidfrom"
            label="Date de début :"
            label-for="input-weeklyschedule-datevalidfrom"
          >
            <b-form-datepicker
              id="input-weeklyschedule-datevalidfrom"
              v-model="formWeeklySchedule.date_valid_from"
              menu-class="w-100"
              label-no-date-selected="Aucune date selectionnée"
              calendar-width="100%"
              class="mb-2"
            >
            </b-form-datepicker>
          </b-form-group>
          <b-form-group
            id="input-group-weeklyschedule-datevalidto"
            label="Date de fin :"
            label-for="input-weeklyschedule-datevalidto"
          >
            <b-form-datepicker
              id="input-weeklyschedule-datevalidto"
              v-model="formWeeklySchedule.date_valid_to"
              menu-class="w-100"
              label-no-date-selected="Aucune date selectionnée"
              calendar-width="100%"
              class="mb-2"
            >
            </b-form-datepicker>
          </b-form-group>

          <b-button block type="submit" variant="outline-primary">
            Créer le calendrier hebdomadaire
          </b-button>
        </b-form>
      </b-modal>

      <!-- MODAL ADD SCHEDULEOVERRIDE -->
      <b-modal
        id="modal-add-scheduleoverride"
        title="Ajouter un exception d'horaire"
        :hide-footer="true"
      >
        <b-form
          @submit.prevent="
            createScheduleOverride(formScheduleOverride.date_schedule_override)
          "
        >
          <b-form-group
            id="input-group-scheduleoverride-datescheduleoverride"
            label="Date de début :"
            label-for="input-scheduleoverride-datescheduleoverride"
          >
            <b-form-datepicker
              id="input-scheduleoverride-datescheduleoverride"
              v-model="formScheduleOverride.date_schedule_override"
              menu-class="w-100"
              label-no-date-selected="Aucune date selectionnée"
              calendar-width="100%"
              class="mb-2"
            >
            </b-form-datepicker>
          </b-form-group>

          <b-button block type="submit" variant="outline-primary">
            Créer l'exception d'horaire
          </b-button>
        </b-form>
      </b-modal>

      <!-- MODAL ADD ABSENCE -->
      <b-modal
        id="modal-add-absence"
        title="Ajouter des vacances"
        :hide-footer="true"
      >
        <b-form
          @submit.prevent="
            createAbsence(
              formAbsence.date_absence_from,
              formAbsence.date_absence_to
            )
          "
        >
          <b-form-group
            id="input-group-absence-dateabsencefrom"
            label="Date de début :"
            label-for="input-absence-dateabsencefrom"
          >
            <b-form-datepicker
              id="input-absence-dateabsencefrom"
              v-model="formAbsence.date_absence_from"
              menu-class="w-100"
              label-no-date-selected="Aucune date selectionnée"
              calendar-width="100%"
              class="mb-2"
            >
            </b-form-datepicker>
          </b-form-group>

          <b-form-group
            id="input-group-absence-dateabsenceto"
            label="Date de début :"
            label-for="input-absence-dateabsenceto"
          >
            <b-form-datepicker
              id="input-absence-dateabsenceto"
              v-model="formAbsence.date_absence_to"
              menu-class="w-100"
              label-no-date-selected="Aucune date selectionnée"
              calendar-width="100%"
              class="mb-2"
            >
            </b-form-datepicker>
          </b-form-group>

          <b-button block type="submit" variant="outline-primary">
            Créer les vacances
          </b-button>
        </b-form>
      </b-modal>

      <!-- MODAL ADD TIMESLOT FOR WEEKLYSCHEDULE -->
      <b-modal
        id="modal-add-timeslot-weeklyschedule"
        title="Ajouter un créneau horaire"
        :hide-footer="true"
      >
        <b-form
          @submit.prevent="
            createTimeSlotForWeeklySchedule(
              selectedWeeklyScheduleId,
              formTimeSlot.code_day,
              formTimeSlot.time_start,
              formTimeSlot.time_end
            )
          "
        >
          <b-form-group
            id="input-group-timeslot-codeday"
            label="Jour de la semaine :"
            label-for="input-timeslot-codeday"
          >
            <b-form-select
              id="input-timeslot-codeday"
              v-model="formTimeSlot.code_day"
              :options="dayOptions"
            >
            </b-form-select>
          </b-form-group>
          <b-form-group
            id="input-group-timeslot-timestart"
            label="L'heure de début :"
            label-for="input-timeslot-timestart"
          >
            <b-form-select
              id="input-timeslot-timestart"
              v-model="formTimeSlot.time_start"
              :options="timeOptions"
            >
            </b-form-select>
          </b-form-group>
          <b-form-group
            id="input-group-timeslot-timeend"
            label="L'heure de fin :"
            label-for="input-timeslot-timeend"
          >
            <b-form-select
              id="input-timeslot-timeend"
              v-model="formTimeSlot.time_end"
              :options="timeOptions"
            >
            </b-form-select>
          </b-form-group>

          <b-button block type="submit" variant="outline-primary">
            Créer le créneau horaire
          </b-button>
        </b-form>
      </b-modal>

      <!-- MODAL ADD TIMESLOT FOR SCHEDULE OVERRIDE -->
      <b-modal
        id="modal-add-timeslot-scheduleoverride"
        title="Ajouter un créneau horaire"
        :hide-footer="true"
      >
        <b-form
          @submit.prevent="
            createTimeSlotForScheduleOverride(
              selectedScheduleOverrideId,
              formTimeSlot.time_start,
              formTimeSlot.time_end
            )
          "
        >
          <b-form-group
            id="input-group-timeslot-timestart"
            label="L'heure de début :"
            label-for="input-timeslot-timestart"
          >
            <b-form-select
              id="input-timeslot-timestart"
              v-model="formTimeSlot.time_start"
              :options="timeOptions"
            >
            </b-form-select>
          </b-form-group>
          <b-form-group
            id="input-group-timeslot-timeend"
            label="L'heure de fin :"
            label-for="input-timeslot-timeend"
          >
            <b-form-select
              id="input-timeslot-timeend"
              v-model="formTimeSlot.time_end"
              :options="timeOptions"
            >
            </b-form-select>
          </b-form-group>

          <b-button block type="submit" variant="outline-primary">
            Créer le créneau horaire
          </b-button>
        </b-form>
      </b-modal>

      <!-- MODAL DELETE WEEKLY SCHEDULE-->
      <b-modal
        id="modal-delete-weeklyschedule"
        title="Confirmation de supression d'un calendrier hebdomadaire"
        :hide-footer="true"
        :hide-header="true"
      >
        <h5 style="text-align: center">
          Supprimer le calendrier hebdomadaire ?
        </h5>
        <b-form
          @submit.prevent="deleteWeeklyScheduleById(selectedWeeklyScheduleId)"
        >
          <b-row>
            <b-col>
              <b-button
                block
                variant="danger"
                @click="$bvModal.hide('modal-delete-weeklyschedule')"
              >
                Non
              </b-button>
            </b-col>
            <b-col>
              <b-button
                @click="$bvModal.hide('modal-delete-weeklyschedule')"
                block
                type="submit"
                variant="success"
              >
                Oui
              </b-button>
            </b-col>
          </b-row>
        </b-form>
      </b-modal>

      <!-- MODAL DELETE SCHEDULE OVERRIDE-->
      <b-modal
        id="modal-delete-scheduleoverride"
        title="Confirmation de supression d'une exception d'horaire"
        :hide-footer="true"
        :hide-header="true"
      >
        <h5 style="text-align: center">Supprimer l'exception d'horaire ?</h5>
        <b-form
          @submit.prevent="
            deleteScheduleOverrideById(selectedScheduleOverrideId)
          "
        >
          <b-row>
            <b-col>
              <b-button
                block
                variant="danger"
                @click="$bvModal.hide('modal-delete-scheduleoverride')"
              >
                Non
              </b-button>
            </b-col>
            <b-col>
              <b-button
                @click="$bvModal.hide('modal-delete-scheduleoverride')"
                block
                type="submit"
                variant="success"
              >
                Oui
              </b-button>
            </b-col>
          </b-row>
        </b-form>
      </b-modal>

      <!-- MODAL DELETE ABSENCE-->
      <b-modal
        id="modal-delete-absence"
        title="Confirmation de supression des vacances"
        :hide-footer="true"
        :hide-header="true"
      >
        <h5 style="text-align: center">Supprimer les vacances ?</h5>
        <b-form @submit.prevent="deleteAbsenceById(selectedAbsenceId)">
          <b-row>
            <b-col>
              <b-button
                block
                variant="danger"
                @click="$bvModal.hide('modal-delete-absence')"
              >
                Non
              </b-button>
            </b-col>
            <b-col>
              <b-button
                @click="$bvModal.hide('modal-delete-absence')"
                block
                type="submit"
                variant="success"
              >
                Oui
              </b-button>
            </b-col>
          </b-row>
        </b-form>
      </b-modal>

      <!-- MODAL DELETE TIME SLOT -->
      <b-modal
        id="modal-delete-timeslot"
        title="Confirmation de supression d'un créneau horaire"
        :hide-footer="true"
        :hide-header="true"
      >
        <h5 style="text-align: center">Supprimer le créneau horaire ?</h5>
        <b-form @submit.prevent="deleteTimeSlotById(selectedTimeSlotId)">
          <b-row>
            <b-col>
              <b-button
                block
                variant="danger"
                @click="$bvModal.hide('modal-delete-timeslot')"
              >
                Non
              </b-button>
            </b-col>
            <b-col>
              <b-button
                @click="$bvModal.hide('modal-delete-timeslot')"
                block
                type="submit"
                variant="success"
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
import { BIconTrash } from "bootstrap-vue";
import moment from "moment";
moment.locale("fr-ch");

export default {
  components: {
    BIconTrash,
  },
  name: "Planning",
  data() {
    return {
      weeklySchedules: [],
      timeSlots: [],
      scheduleOverrides: [],
      absences: [],
      selectedWeeklyScheduleId: null,
      selectedScheduleOverrideId: null,
      selectedTimeSlotId: null,
      selectedAbsenceId: null,
      formTimeSlot: {
        code_day: 2,
        time_start: "07",
        time_end: "09",
      },
      formWeeklySchedule: {
        date_valid_from: null,
        date_valid_to: null,
      },
      formScheduleOverride: {
        date_schedule_override: null,
      },
      formAbsence: {
        date_absence_from: null,
        date_absence_to: null,
      },
      dayOptions: [
        { value: 2, text: "Lundi" },
        { value: 3, text: "Mardi" },
        { value: 4, text: "Mercredi" },
        { value: 5, text: "Jeudi" },
        { value: 6, text: "Vendredi" },
        { value: 7, text: "Samedi" },
        { value: 1, text: "Dimanche" },
      ],
      timeOptions: [
        { value: "06", text: "06h" },
        { value: "07", text: "07h" },
        { value: "08", text: "08h" },
        { value: "09", text: "09h" },
        { value: "10", text: "10h" },
        { value: "11", text: "11h" },
        { value: "12", text: "12h" },
        { value: "13", text: "13h" },
        { value: "14", text: "14h" },
        { value: "15", text: "15h" },
        { value: "16", text: "16h" },
        { value: "17", text: "17h" },
        { value: "18", text: "18h" },
      ],
      dayOfWeek: [
        "",
        "Dimanche",
        "Lundi",
        "Mardi",
        "Mercredi",
        "Jeudi",
        "Vendredi",
        "Samedi",
      ],
      tblWeeklySchedulesFields: [
        "date_de_début",
        "date_de_fin",
        "créneaux_horaires",
        "supprimer",
      ],
      tblScheduleOverridesFields: ["date", "créneaux_horaires", "supprimer"],
      tblAbsencesFields: ["date_de_début", "date_de_fin", "supprimer"],
      tblTimeSlotsWeeklySchedulesFields: [
        "jour",
        "heure_de_début",
        "heure_de_fin",
        "supprimer",
      ],
      tblTimeSlotsScheduleOverridesFields: [
        "heure_de_début",
        "heure_de_fin",
        "supprimer",
      ],
    };
  },
  methods: {
    loadEducatorWeeklySchedulesWithTimeSlots() {
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .get(this.$API_URL + "weeklySchedules/", config)
        .then((response) => {
          this.weeklySchedules = [];
          response.data.forEach((weeklySchedule) => {
            weeklySchedule["date_de_début"] = this.formatDate(
              weeklySchedule["date_valid_from"]
            );
            delete weeklySchedule["date_valid_from"];
            weeklySchedule["date_de_fin"] =
              weeklySchedule["date_valid_to"] == undefined
                ? "-"
                : this.formatDate(weeklySchedule["date_valid_to"]);
            delete weeklySchedule["date_valid_to"];

            weeklySchedule["timeSlots"].forEach((timeslot) => {
              timeslot["jour"] = this.dayOfWeek[timeslot["code_day"]];
              delete timeslot["code_day"];
              timeslot["heure_de_début"] = this.formatTime(
                timeslot["time_start"]
              );
              delete timeslot["time_start"];
              timeslot["heure_de_fin"] = this.formatTime(timeslot["time_end"]);
              delete timeslot["time_end"];
            });
          });
          this.weeklySchedules = response.data;
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    loadEducatorScheduleOverridesWithTimeSlots() {
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .get(this.$API_URL + "scheduleOverrides/", config)
        .then((response) => {
          this.scheduleOverrides = [];
          response.data.forEach((scheduleOverride) => {
            scheduleOverride["date"] = this.formatDate(
              scheduleOverride["date_schedule_override"]
            );
            delete scheduleOverride["date_schedule_override"];

            scheduleOverride["timeSlots"].forEach((timeslot) => {
              timeslot["heure_de_début"] = this.formatTime(
                timeslot["time_start"]
              );
              delete timeslot["time_start"];
              timeslot["heure_de_fin"] = this.formatTime(timeslot["time_end"]);
              delete timeslot["time_end"];
            });
          });
          this.scheduleOverrides = response.data;
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    loadEducatorAbsences() {
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .get(this.$API_URL + "absences/", config)
        .then((response) => {
          this.absences = [];
          response.data.forEach((absence) => {
            absence["date_de_début"] = this.formatDate(
              absence["date_absence_from"]
            );
            delete absence["date_absence_from"];
            absence["date_de_fin"] = this.formatDate(
              absence["date_absence_to"]
            );
            delete absence["date_absence_to"];
          });
          this.absences = response.data;
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    createTimeSlotForWeeklySchedule(
      weeklyScheduleId,
      codeDay,
      timeStart,
      timeEnd
    ) {
      const params = new URLSearchParams();
      params.append("id_weekly_schedule", weeklyScheduleId);
      params.append("code_day", codeDay);
      params.append("time_start", timeStart + ":00:00");
      params.append("time_end", timeEnd + ":00:00");
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .post(this.$API_URL + "timeSlots/", params, config)
        .then((response) => {
          console.log(response);
          this.loadEducatorWeeklySchedulesWithTimeSlots();
          this.$alertify.success("Créneau horaire ajouté avec succès");
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    createTimeSlotForScheduleOverride(scheduleOverrideId, timeStart, timeEnd) {
      const params = new URLSearchParams();
      params.append("id_schedule_override", scheduleOverrideId);
      params.append("code_day", 1);
      params.append("time_start", timeStart + ":00:00");
      params.append("time_end", timeEnd + ":00:00");
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .post(this.$API_URL + "timeSlots/", params, config)
        .then((response) => {
          console.log(response);
          this.loadEducatorScheduleOverridesWithTimeSlots();
          this.$alertify.success("Créneau horaire ajouté avec succès");
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    createWeeklySchedule(dateValidFrom, dateValidTo) {
      const params = new URLSearchParams();
      params.append("date_valid_from", dateValidFrom);
      params.append("date_valid_to", dateValidTo);
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .post(this.$API_URL + "weeklySchedules/", params, config)
        .then((response) => {
          console.log(response);
          this.loadEducatorWeeklySchedulesWithTimeSlots();
          this.$alertify.success("Calendrier hebdomadaire ajouté avec succès");
          this.$bvModal.hide("modal-add-weeklyschedule");
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    createScheduleOverride(dateScheduleOverride) {
      const params = new URLSearchParams();
      params.append("date_schedule_override", dateScheduleOverride);
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .post(this.$API_URL + "scheduleOverrides/", params, config)
        .then((response) => {
          console.log(response);
          this.loadEducatorScheduleOverridesWithTimeSlots();
          this.$alertify.success("Exception d'horaire ajouté avec succès");
          this.$bvModal.hide("modal-add-scheduleoverride");
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    createAbsence(dateAbsenceFrom, dateAbsenceTo) {
      const params = new URLSearchParams();
      params.append("date_absence_from", dateAbsenceFrom);
      params.append("date_absence_to", dateAbsenceTo);
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .post(this.$API_URL + "absences/", params, config)
        .then((response) => {
          console.log(response);
          this.loadEducatorAbsences();
          this.$alertify.success("Vacances ajoutées avec succès");
          this.$bvModal.hide("modal-add-absence");
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    deleteWeeklyScheduleById(weeklyScheduleId) {
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .delete(this.$API_URL + "weeklySchedules/" + weeklyScheduleId, config)
        .then((response) => {
          this.loadEducatorWeeklySchedulesWithTimeSlots();
          this.$alertify.success("Calendrier hebdomadaire supprimé");
          console.log(response);
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    deleteScheduleOverrideById(scheduleOverrideId) {
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .delete(
          this.$API_URL + "scheduleOverrides/" + scheduleOverrideId,
          config
        )
        .then((response) => {
          this.loadEducatorScheduleOverridesWithTimeSlots();
          this.$alertify.success("Exception d'horaire supprimé");
          console.log(response);
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    deleteAbsenceById(absenceId) {
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .delete(this.$API_URL + "absences/" + absenceId, config)
        .then((response) => {
          this.loadEducatorAbsences();
          this.$alertify.success("Vacances supprimées");
          console.log(response);
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    deleteTimeSlotById(timeSlotId) {
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .delete(this.$API_URL + "timeSlots/" + timeSlotId, config)
        .then((response) => {
          this.loadEducatorWeeklySchedulesWithTimeSlots();
          this.loadEducatorScheduleOverridesWithTimeSlots();
          this.$alertify.success("Créneau horaire supprimé");
          console.log(response);
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    sendWeeklyScheduleId(weeklyScheduleId) {
      this.selectedWeeklyScheduleId = weeklyScheduleId;
    },
    sendScheduleOverrideId(scheduleOverrideId) {
      this.selectedScheduleOverrideId = scheduleOverrideId;
    },
    sendTimeSlotId(timeSlotId) {
      this.selectedTimeSlotId = timeSlotId;
    },
    sendAbsenceId(absenceId) {
      this.selectedAbsenceId = absenceId;
    },
    formatDate(value) {
      if (value) {
        return moment(value).format("dddd DD MMMM YYYY");
      }
    },
    formatTime(value) {
      if (value) {
        return moment("1970-01-01 " + value).format("HH[h]");
      }
    },
  },
  mounted() {
    this.loadEducatorWeeklySchedulesWithTimeSlots();
    this.loadEducatorScheduleOverridesWithTimeSlots();
    this.loadEducatorAbsences();
  },
};
</script>

<style scoped>
.title {
  margin-top: 20px;
  color: #3ea3d8;
}
.tableBtn {
  background-color: #3ea3d8;
}
</style>
