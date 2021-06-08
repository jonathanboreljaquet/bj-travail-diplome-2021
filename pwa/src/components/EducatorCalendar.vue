<!--
  EducatorCalendar.vue

  Component representing the page of the personal calendar of a dog trainer.

  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
-->

<template>
  <div>
    <b-container>
      <b-row id="title" class="text-center">
        <b-col>
          <h1>Mes rendez-vous</h1>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <b-button
            id="btnCreateAppointment"
            style="margin-bottom: 10px"
            variant="outline-primary"
            v-b-modal.modal-create-appointment
            block
          >
            Planifier un rendez-vous
          </b-button>
        </b-col>
      </b-row>
      <b-row class="text-center">
        <b-col>
          <FullCalendar ref="fullCalendar" :options="calendarOptions" />
        </b-col>
      </b-row>

      <!-- MODAL CREATE APPOINTMENT  -->
      <b-modal
        id="modal-create-appointment"
        title="Planifier un rendez-vous"
        :hide-footer="true"
      >
        <b-form
          @submit.prevent="
            createAppointment(
              formCreateAppointment.date,
              formCreateAppointment.timestart,
              formCreateAppointment.durationInHour,
              formCreateAppointment.customer,
              $store.state.user_id
            )
          "
        >
          <b-row>
            <b-col>
              <b-form-group label="Client :">
                <b-form-select
                  id="customerSelect"
                  v-model="formCreateAppointment.customer"
                >
                  <b-form-select-option
                    v-for="customer in customers"
                    :key="customer.id"
                    :value="customer.id"
                    >{{ customer.firstname }}
                    {{ customer.lastname }}</b-form-select-option
                  >
                </b-form-select>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col class="d-flex justify-content-center">
              <h5>Date</h5>
            </b-col>
          </b-row>
          <b-row>
            <b-col style="margin-bottom: 10px">
              <b-calendar
                block
                id="date-calendar"
                label-help=""
                v-model="formCreateAppointment.date"
              ></b-calendar>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <b-form-group
                id="input-group-timeslot-timestart"
                label="L'heure de début :"
                label-for="input-timeslot-timestart"
              >
                <b-form-select
                  id="input-timeslot-timestart"
                  v-model="formCreateAppointment.timestart"
                  :options="timeOptions"
                >
                </b-form-select>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <b-form-group
                id="input-group-timeslot-timeend"
                label="La durée du rendez-vous (en heure) :"
                label-for="input-timeslot-timeend"
              >
                <b-form-select
                  id="input-timeslot-timeend"
                  v-model="formCreateAppointment.durationInHour"
                  :options="timeDurationOptions"
                >
                </b-form-select>
              </b-form-group>
            </b-col>
          </b-row>
          <b-button block type="submit" variant="outline-primary">
            Planifier le rendez-vous
          </b-button>
        </b-form>
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
            name: "customerAppointment",
            params: { userId: info.event.extendedProps.customerId },
          });
        },
      },
      customers: [],
      formCreateAppointment: {
        customer: null,
        date: null,
        timestart: "07",
        durationInHour: 2,
      },
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
      timeDurationOptions: [
        { value: 1, text: "01h" },
        { value: 2, text: "02h" },
        { value: 3, text: "03h" },
        { value: 4, text: "04h" },
      ],
    };
  },
  methods: {
    loadEducatorAppointments() {
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token
        },
      };
      this.$http
        .get(this.$API_URL + "appointments/", config)
        .then((response) => {
          this.calendarOptions.events = [];
          response.data.forEach((appointment) => {
            this.calendarOptions.events.push({
              title: "Disponible",
              start: moment(appointment.datetime_appointment).format(
                "YYYY-MM-DD HH:mm:ss"
              ),
              end: moment(appointment.datetime_appointment)
                .add(appointment.duration_in_hour, "hour")
                .format("YYYY-MM-DD HH:mm:ss"),
              extendedProps: {
                customerId: appointment.user_id_customer,
              },
            });
          });
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    loadCustomers() {
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token
        },
      };
      this.$http
        .get(this.$API_URL + "users/", config)
        .then((response) => {
          this.customers = response.data;
          this.formCreateAppointment.customer = this.customers[0].id;
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    createAppointment(
      date,
      timestart,
      durationInHour,
      user_id_customer,
      user_id_educator
    ) {
      const params = new URLSearchParams();
      params.append("datetime_appointment", date + " " + timestart + ":00:00");
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
          this.loadEducatorAppointments(this.selected);
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
  },
  mounted() {
    this.loadCustomers();
    this.loadEducatorAppointments();
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
