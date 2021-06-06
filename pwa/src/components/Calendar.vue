<template>
  <div>
    <b-container>
      <b-row id="title" class="text-center">
        <b-col>
          <h1>Agenda des éducateurs canins de la société</h1>
        </b-col>
      </b-row>
      <b-row id="title" class="text-center">
        <b-col>
          <b-form-group label="Éducateur canin :">
            <b-form-select
              id="educatorSelect"
              v-model="selected"
              @change="onChange()"
            >
              <b-form-select-option
                v-for="educator in educators"
                :key="educator.id"
                :value="educator.id"
                >{{ educator.firstname }}
                {{ educator.lastname }}</b-form-select-option
              >
            </b-form-select>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row class="text-center">
        <b-col>
          <p class="font-weight-light">
            Vous voulez prendre un rendez-vous avec un des éducateurs canins de
            la société ? <br />
            Il vous suffit de cliquer sur un des créneaux horaires une fois
            connecté afin de réserver votre rendez-vous
          </p>
        </b-col>
      </b-row>
      <b-row class="text-center">
        <b-col>
          <b-badge id="badge-free" variant="success">Rendez-vous libre</b-badge>
        </b-col>
      </b-row>
      <b-row class="text-center">
        <b-col>
          <FullCalendar ref="fullCalendar" :options="calendarOptions" />
        </b-col>
      </b-row>
      <b-modal
        id="modal-create-appointment"
        title="Réserver un rendez-vous"
        :hide-footer="true"
      >
        <div v-if="auth">
          <b-form
            @submit.prevent="
              createAppointment(
                selectedAppointment.datetime,
                selectedAppointment.durationInHour,
                $store.state.user_id,
                selectedAppointment.idEducator
              )
            "
          >
            <b-row>
              <b-col class="d-flex justify-content-center">
                <h5>Éducateur canin</h5>
              </b-col>
            </b-row>
            <b-row>
              <b-col class="d-flex justify-content-center">
                <p>
                  {{ this.$jquery("#educatorSelect option:selected").text() }}
                </p>
              </b-col>
            </b-row>
            <b-row>
              <b-col class="d-flex justify-content-center">
                <h5>Date</h5>
              </b-col>
            </b-row>
            <b-row>
              <b-col>
                <b-calendar
                  block
                  id="date-calendar"
                  label-help=""
                  :readonly="true"
                  :value="selectedAppointment.datetime"
                ></b-calendar>
              </b-col>
            </b-row>
            <b-row>
              <b-col class="d-flex justify-content-center">
                <h5>Créneau horaire</h5>
              </b-col>
            </b-row>
            <b-row>
              <b-col class="d-flex justify-content-center">
                <p>
                  {{ selectedAppointment.startHour }}h à
                  {{ selectedAppointment.endHour }}h
                </p>
              </b-col>
            </b-row>
            <b-button block type="submit" variant="outline-primary">
              Réserver le rendez-vous
            </b-button>
          </b-form>
        </div>
        <div v-else>
          <p>Connectez-vous afin de pouvoir planifier votre rendez-vous.</p>
          <b-button to="/connection" variant="primary">Se connecter</b-button>
        </div>
      </b-modal>
    </b-container>
  </div>
</template>

<script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import frLocale from "@fullcalendar/core/locales/fr";
import moment from "moment";
moment.locale("fr-ch");

export default {
  components: {
    FullCalendar,
  },
  name: "Calendar",
  data() {
    return {
      calendarOptions: {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialView: "dayGridMonth",
        headerToolbar: {
          left: "prev,next today",
          center: "title",
          right: "dayGridMonth,timeGridWeek,timeGridDay",
        },
        height: "auto",
        locale: frLocale,
        eventDisplay: "block",
        allDaySlot: false,
        slotMinTime: "06:00:00",
        slotMaxTime: "20:00:00",
        events: [],
        eventBackgroundColor: "green",
        eventClick: this.handleDateClick,
      },
      selectedAppointment: {
        datetime: null,
        startHour: null,
        endHour: null,
        durationInHour: null,
        idEducator: null,
      },
      selected: null,
      educators: [],
    };
  },
  methods: {
    loadEducators() {
      this.$http.get(this.$API_URL + "users/educators/").then((response) => {
        this.educators = response.data;
        this.selected = this.educators[0].id;
        this.loadEducatorPlanning(this.selected);
      });
    },
    loadEducatorPlanning(idEducator) {
      this.$http
        .get(this.$API_URL + "plannings/" + idEducator)
        .then((response) => {
          this.calendarOptions.events = [];
          response.data.forEach((event) => {
            this.calendarOptions.events.push({
              title: "Disponible",
              start: event.date + " " + event.time_start,
              end: event.date + " " + event.time_end,
              extendedProps: {
                idEducator: idEducator,
              },
            });
          });
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    handleDateClick: function (info) {
      {
        let calendarApi = this.$refs.fullCalendar.getApi();
        if (info.view.type == "dayGridMonth") {
          calendarApi.gotoDate(info.event.endStr);
          calendarApi.changeView("timeGridDay");
        }
        if (
          info.view.type == "timeGridWeek" ||
          info.view.type == "timeGridDay"
        ) {
          var startDate = moment(info.event.start);
          var endDate = moment(info.event.end);
          var datetime = startDate.format("YYYY-MM-DD HH:mm:ss");
          var startHour = startDate.format("HH");
          var endHour = endDate.format("HH");
          var durationDifference = moment.duration(
            moment(info.event.end).diff(moment(info.event.start))
          );
          var durationInHour = durationDifference.asHours();
          this.sendAppointmentInformations(
            datetime,
            startHour,
            endHour,
            durationInHour,
            info.event.extendedProps.idEducator
          );
          this.$bvModal.show("modal-create-appointment");
        }
      }
    },
    createAppointment(
      datetime,
      durationInHour,
      user_id_customer,
      user_id_educator
    ) {
      const params = new URLSearchParams();
      params.append("datetime_appointment", datetime);
      params.append("duration_in_hour", durationInHour);
      params.append("user_id_customer", user_id_customer);
      params.append("user_id_educator", user_id_educator);
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .post(this.$API_URL + "appointments/", params, config)
        .then((response) => {
          console.log(response);
          this.$alertify.success("Rendez-vous reservé avec succès");
          this.$bvModal.hide("modal-create-appointment");
          this.loadEducatorPlanning(this.selected);
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    onChange() {
      this.loadEducatorPlanning(this.selected);
    },
    sendAppointmentInformations(
      appointmentdatetime,
      appointmentStartHour,
      appointmentEndHour,
      appointmentDurationInHour,
      appointmentIdEducator
    ) {
      this.selectedAppointment.datetime = appointmentdatetime;
      this.selectedAppointment.startHour = appointmentStartHour;
      this.selectedAppointment.endHour = appointmentEndHour;
      this.selectedAppointment.durationInHour = appointmentDurationInHour;
      this.selectedAppointment.idEducator = appointmentIdEducator;
    },
  },
  mounted() {
    this.loadEducators();
  },
  computed: {
    auth() {
      return this.$store.getters.ifAuthenticated;
    },
  },
};
</script>

<style scoped>
#badge-free {
  padding: 10px;
  margin-bottom: 10px;
  background-color: #008000;
  border: #3788d8 1px solid;
}
#title {
  margin-top: 20px;
  color: #3ea3d8;
}
</style>
