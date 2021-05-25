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
                    <p>{{ appoitment.summary }}</p>
                    <div v-if="authAdministrator">
                      <h3>Notes textuelles du rendez-vous</h3>
                      <p>{{ appoitment.noteText }}</p>
                      <div v-if="appoitment.noteGraphicalSerialId">
                        <!-- <h3>Notes graphiques du rendez-vous</h3>
                        <b-img :src="image" alt="Image"
                          fluid
                        ></b-img> -->
                      </div>
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
      pad: null,
      title: "",
      appoitments: [],
      messageNoAppoitments: "Aucun rendez-vous",
      image: null,
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
          this.loadNoteGraphicalPicture();
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    loadAppoitmentsData(appoitments) {
      appoitments.forEach((appoitment) => {
        let date = new Date(appoitment.datetime_appoitment);
        var options = {
          weekday: "long",
          year: "numeric",
          month: "long",
          day: "numeric",
        };
        this.appoitments.push({
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
        }
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
