<template>
  <div>
    <b-container>
      <b-row id="title" class="text-center">
        <b-col>
          <h1>Mon planning</h1>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <h4>Calendriers hebdomadaires</h4>
          <ul>
            <li v-for="weeklySchedule in weeklySchedules" :key="weeklySchedule.id">du {{ weeklySchedule.date_valid_from }} au {{ weeklySchedule.date_valid_to }}
              <ul>
                <div v-for="timeslot in timeSlots" :key="timeslot.id">
                  <li v-if="timeslot.id_weekly_schedule == weeklySchedule.id">
                    {{ timeslot.code_day }} {{ timeslot.time_start }} jusqu'à {{ timeslot.time_end }}
                  </li>
                </div>
                <b-button
                  variant="outline-primary"
                  v-b-modal.modal-add-timeslot-weeklyschedule
                  block
                  @click="sendWeeklyScheduleId(weeklySchedule.id)"
                >
                  Ajouter un créneau horaire
                </b-button>
              </ul>
            </li>
          </ul>
          <h4>Exception d'horaire</h4>
          <ul>
            <li v-for="scheduleOverride in scheduleOverrides" :key="scheduleOverride.id">{{ scheduleOverride.date_schedule_override }}
              <ul>
                <div v-for="timeslot in timeSlots" :key="timeslot.id">
                  <li v-if="timeslot.id_schedule_override == scheduleOverride.id">
                    {{ timeslot.code_day }} {{ timeslot.time_start }} jusqu'à {{ timeslot.time_end }}
                  </li>
                </div>
              </ul>
            </li>
          </ul>
          <h4>Vacances</h4>
          <ul>
            <li v-for="absence in absences" :key="absence.id">
              {{ absence.date_absence_from }} jusqu'à {{ absence.date_absence_to }}
            </li>
          </ul>
        </b-col>
      </b-row>

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
            label="Code day :"
            label-for="input-timeslot-codeday"
          >
            <b-form-input
              id="input-timeslot-codeday"
              v-model="formTimeSlot.code_day"
              type="text"
              placeholder="Entrez le code day"
              required
            ></b-form-input>
          </b-form-group>
          <b-form-group
            id="input-group-timeslot-timestart"
            label="L'heure de début :"
            label-for="input-timeslot-timestart"
          >
            <b-form-input
              id="input-timeslot-timestart"
              v-model="formTimeSlot.time_start"
              type="text"
              placeholder="Entrez l'heure de début"
              required
            ></b-form-input>
          </b-form-group>
          <b-form-group
            id="input-group-timeslot-timeend"
            label="L'heure de fin :"
            label-for="input-timeslot-timeend"
          >
            <b-form-input
              id="input-timeslot-timeend"
              v-model="formTimeSlot.time_end"
              type="text"
              placeholder="Entrez l'heure de fin"
              required
            ></b-form-input>
          </b-form-group>

          <b-button block type="submit" variant="outline-primary">
            Créer le créneau horaire
          </b-button>
        </b-form>
      </b-modal>
    </b-container>
  </div>
</template>

<script>
export default {
  name: "Planning",
  data() {
    return {
      weeklySchedules: [],
      timeSlots: [],
      scheduleOverrides: [],
      absences: [],
      selectedWeeklyScheduleId: null,
      formTimeSlot: {
        code_day: null,
        time_start: null,
        time_end: null,
      },
    };
  },
  methods: {
    loadEducatorWeeklySchedules() {
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .get(this.$API_URL + "weeklySchedules/", config)
        .then((response) => {
          this.weeklySchedules = response.data;
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    loadEducatorScheduleOverrides() {
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .get(this.$API_URL + "scheduleOverrides/", config)
        .then((response) => {
          this.scheduleOverrides = response.data;
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    loadEducatorTimeSlots() {
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .get(this.$API_URL + "timeSlots/", config)
        .then((response) => {
          this.timeSlots = response.data;
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
          this.absences = response.data;
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    createTimeSlotForWeeklySchedule(weeklyScheduleId, timeSlotCodeDay, timeSlotTimeStart, timeSlotTimeEnd) {
      const params = new URLSearchParams();
      params.append("id_weekly_schedule", weeklyScheduleId);
      params.append("code_day", timeSlotCodeDay);
      params.append("time_start", timeSlotTimeStart);
      params.append("time_end", timeSlotTimeEnd);
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
          this.loadEducatorTimeSlots();
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    sendWeeklyScheduleId(weeklyScheduleId) {
      this.selectedWeeklyScheduleId = weeklyScheduleId;
    },
  },
  mounted() {
    this.loadEducatorWeeklySchedules();
    this.loadEducatorTimeSlots();
    this.loadEducatorScheduleOverrides();
    this.loadEducatorAbsences();
  },
};
</script>

<style scoped>
#title {
  margin-top: 20px;
  color: #3ea3d8;
}
</style>
