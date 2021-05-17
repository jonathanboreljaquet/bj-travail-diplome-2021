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
            <b-form-select v-model="selected" @change="onChange()">
              <b-form-select-option
                v-for="educator in educators"
                :key="educator.id"
                :value="educator.id"
                >{{ educator.firstname }} {{ educator.lastname }}</b-form-select-option
              >
            </b-form-select>
          </b-form-group>
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
    </b-container>
  </div>
</template>

<script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import frLocale from "@fullcalendar/core/locales/fr";

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
        eventClick: function (info) {
          this.gotoDate(info.event.endStr);
          this.changeView("timeGridDay");
        },
      },
      selected: 1,
      educators: [],
    };
  },
  methods: {
    loadEducators() {
      this.$http
        .get(
          "https://api-rest-douceur-de-chien.boreljaquet.ch/users/educators/"
        )
        .then((response) => (this.educators = response.data));
    },
    loadEducatorEvents(idEducator){
      this.$http
        .get(
          "https://api-rest-douceur-de-chien.boreljaquet.ch/plannings/" +
            idEducator
        )
        .then((response) => {
          const vm = this;
          vm.calendarOptions.events = [];
          this.$jquery.each(response.data, function (index) {
            vm.calendarOptions.events.push({
              title: "Disponible",
              start:
                response.data[index].date +
                " " +
                response.data[index].time_start,
              end:
                response.data[index].date + " " + response.data[index].time_end,
            });
          });
        });
    },
    onChange(){
        this.loadEducatorEvents(this.selected);
    }
  },
  mounted() {
    this.loadEducators();
    this.loadEducatorEvents(this.selected);
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
