<template>
  <div>
    <b-container>
      <b-row id="title" class="text-center">
        <b-col>
          <h1 class="font-weight-bold">Mes informations</h1>
        </b-col>
      </b-row>
      <b-row id="title">
        <b-col md="4" style="margin-bottom: 10px" class="text-center d-flex flex-column align-items-center text-center">
          <b-card
            style="padding: 10px; width: 100%"
            img-src="./../assets/img/user-profile.png"
            img-top
            :title="firstname"
          >
            <p class="text-secondary mb-1" v-if="code_role == '1'">Client</p>
            <p class="text-secondary mb-1">{{ address }}</p>
          </b-card>
        </b-col>
        <b-col md="8">
          <b-card style="margin-bottom: 10px">
            <b-row>
              <b-col sm="3">
                <h6 class="mb-0">Nom complet</h6>
              </b-col>
              <b-col sm="9" class="text-secondary">
                {{ firstname }} {{ lastname }}
              </b-col>
            </b-row>
            <hr />
            <b-row>
              <b-col sm="3">
                <h6 class="mb-0">Adresse e-mail</h6>
              </b-col>
              <b-col sm="9" class="text-secondary">{{ email }}</b-col>
            </b-row>
            <hr />
            <b-row>
              <b-col sm="3">
                <h6 class="mb-0">Numéro de téléphone</h6>
              </b-col>
              <b-col sm="9" class="text-secondary">{{ phonenumber }}</b-col>
            </b-row>
            <hr />
            <b-row>
              <b-col sm="3">
                <h6 class="mb-0">Adresse de domicile</h6>
              </b-col>
              <b-col sm="9" class="text-secondary">{{ address }}</b-col>
            </b-row>
          </b-card>
          <b-row>
            <b-col md="6" v-for="dog in dogs" :key="dog.id">
              <b-card>
                <b-row no-gutters>
                  <b-col md="12">
                    <b-card-img v-if="dataLoading" :src="require('../assets/img/placeholder-image.png')" alt="Imagedasd" style="margin-bottom:15px"></b-card-img>
                    <b-card-img v-else :src="dog.base64_picture" alt="Image" style="margin-bottom:15px"></b-card-img>
                  </b-col>
                </b-row>
                <b-row>
                  <b-col sm="4">
                    <h6 class="mb-0">Nom</h6>
                  </b-col>
                  <b-col sm="8" class="text-secondary">{{ dog.name }}</b-col>
                </b-row>
                <hr />
                <b-row>
                  <b-col sm="4">
                    <h6 class="mb-0">Race</h6>
                  </b-col>
                  <b-col sm="8" class="text-secondary">{{ dog.breed }}</b-col>
                </b-row>
                <hr />
                <b-row>
                  <b-col sm="4">
                    <h6 class="mb-0">Sexe</h6>
                  </b-col>
                  <b-col sm="8" class="text-secondary">{{ dog.sex }}</b-col>
                </b-row>
                <hr />
                <b-row>
                  <b-col sm="4">
                    <h6 class="mb-0">Numéro de puce</h6>
                  </b-col>
                  <b-col sm="8" class="text-secondary">{{ dog.chip_id }}</b-col>
                </b-row>
              </b-card>
            </b-col>
          </b-row>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
export default {
  name: "CustomerInformation",
  data() {
    return {
      id: null,
      email: null,
      firstname: null,
      lastname: null,
      phonenumber: null,
      address: null,
      code_role: null,
      dogs: [],
      dataLoading: true,
    };
  },
  methods: {
    loadAuthCustomerInformations() {
      const config = {
        headers: {
          "Authorization" : this.$store.state.api_token
        },
      };
      this.$http
        .get("https://api-rest-douceur-de-chien.boreljaquet.ch/users/me/", config)
        .then((response) => {
          const vm = this;
          let url = "https://api-rest-douceur-de-chien.boreljaquet.ch/dogs/downloadPicture/";
          let promisedEvents = [];
          vm.$jquery.each(response.data.dogs, function (index, item) {
            if (item.picture_serial_id) {
              promisedEvents.push(vm.$http.get(url + item.picture_serial_id));
            }
          });
          vm.id = response.data.id;
          vm.email = response.data.email;
          vm.firstname = response.data.firstname;
          vm.lastname = response.data.lastname;
          vm.phonenumber = response.data.phonenumber;
          vm.address = response.data.address;
          vm.code_role = response.data.code_role;
          vm.dogs = response.data.dogs;
          return Promise.all(promisedEvents);
        })
        .then((response) => {
          const vm = this;
          this.$jquery.each(response, function (index, item) {
            var parts = item.config.url.split("/");
            var picture_serial_id = parts[parts.length - 1];
            const dog = vm.dogs.find(element => element.picture_serial_id == picture_serial_id);
            dog["base64_picture"] = item.data;
          });
          this.dataLoading = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  mounted() {
    this.loadAuthCustomerInformations();
  },
};
</script>

<style scoped>
#title {
  margin-top: 20px;
  color: #3ea3d8;
}
</style>
