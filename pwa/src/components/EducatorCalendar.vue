<template>
  <div>
    <b-container>
      <b-row id="title" class="text-center">
        <b-col>
          <h1>Mes rendez-vous</h1>
        </b-col>
      </b-row>
      <b-row class="text-center">
        <b-col>
          <FullCalendar ref="fullCalendar" :options="calendarOptions" />
        </b-col>
      </b-row>
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
  name: "EducatorCalendar",
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
        eventBackgroundColor: "#3ea3d8",
        eventBorderColor: "#e08f25",
        eventClick: (info) => {
          this.$router.push({
            name: "customerAppoitment",
            params: { userId: info.event.extendedProps.customerId },
          });
        },
      },
    };
  },
  methods: {
    loadEducatorEvents() {
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token
        },
      };
      this.$http
        .get(this.$API_URL + "appoitments/", config)
        .then((response) => {
          console.log(response.data);
          this.calendarOptions.events = [];
          response.data.forEach((appoitment) => {
            this.calendarOptions.events.push({
              title: "Disponible",
              start: moment(appoitment.datetime_appoitment).format(
                "YYYY-MM-DD HH:mm:ss"
              ),
              end: moment(appoitment.datetime_appoitment)
                .add(appoitment.duration_in_hour, "hour")
                .format("YYYY-MM-DD HH:mm:ss"),
              extendedProps: {
                customerId: appoitment.user_id_customer,
              },
            });
          });
          console.log(this.calendarOptions.events);
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
  },
  mounted() {
    this.loadEducatorEvents();
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
