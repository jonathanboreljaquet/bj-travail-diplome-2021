<template>
  <div>
    <b-container>
      <b-row id="title" class="text-center">
        <b-col>
          <h1 class="font-weight-bold">{{ title }}</h1>
        </b-col>
      </b-row>
      <b-row id="title">
        <b-col
          md="4"
          style="margin-bottom: 10px"
          class="text-center d-flex flex-column align-items-center"
        >
          <b-card
            style="padding: 10px; width: 100%; margin-bottom: 10px"
            img-src="./../assets/img/user-profile.png"
            img-top
            :title="firstname"
          >
            <p class="text-secondary mb-1" v-if="code_role == '1'">Client</p>
            <p class="text-secondary mb-1">{{ address }}</p>
            <b-button
              class="btnAdmin"
              v-if="authAdministrator"
              style="margin-bottom: 10px"
              v-b-modal.modal-add-dog
            >
              Ajouter un chien
            </b-button>
            <b-button class="btnAdmin" v-if="authAdministrator">
              Ajouter un document
            </b-button>
          </b-card>

          <b-card style="padding: 10px; width: 100%">
            <b-row style="text-align: center">
              <b-col>condition_inscription.pdf</b-col>
            </b-row>
            <hr style="margin-bottom: 0px; margin-top: 0px" />
            <b-row style="text-align: center">
              <b-col>poster.pdf</b-col>
            </b-row>
            <hr style="margin-bottom: 0px; margin-top: 0px" />
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
              <b-card style="margin-bottom: 10px">
                <b-row no-gutters>
                  <b-col md="12">
                    <b-card-img
                      v-if="dog.picture_serial_id"
                      :src="
                        'https://api-rest-douceur-de-chien.boreljaquet.ch/dogs/downloadPicture/' +
                        dog.picture_serial_id
                      "
                      alt="Image"
                      style="margin-bottom: 15px"
                    ></b-card-img>
                    <b-card-img
                      v-else
                      :src="require('../assets/img/placeholder-dog.png')"
                      alt="placeholder"
                      style="margin-bottom: 15px"
                    ></b-card-img>
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
      <b-button v-if="authAdministrator" class="btnReturn" to="/administration">
        <p class="h4">
          <b-icon-arrow-return-left></b-icon-arrow-return-left>
        </p>
      </b-button>
      <b-modal id="modal-add-dog" title="Ajouter un chien">
        <b-form
          @submit.prevent="addDogForCustomerWithUserId($route.params.userId)"
        >
          <b-form-group
            id="input-group-dog-name"
            label="Nom :"
            label-for="input-dog-name"
          >
            <b-form-input
              id="input-dog-name"
              v-model="addDogForm.name"
              type="text"
              placeholder="Entrez le nom du chien"
              required
            ></b-form-input>
          </b-form-group>

          <b-form-group
            id="input-group-dog-breed"
            label="Race :"
            label-for="input-dog-breed"
          >
            <b-form-input
              id="input-dog-breed"
              v-model="addDogForm.breed"
              type="text"
              placeholder="Entrez la race du chien"
              required
            ></b-form-input>
          </b-form-group>

          <b-form-group
            id="input-group-dog-sex"
            label="Sexe :"
            label-for="input-dog-sex"
          >
            <b-form-select
              id="input-dog-sex"
              v-model="addDogForm.sex"
              :options="sex"
              required
            ></b-form-select>
          </b-form-group>

          <b-form-group
            id="input-group-dog-chip-id"
            label="Numéro de puce :"
            label-for="input-dog-chip-id"
          >
            <b-form-input
              id="input-dog-chip-id"
              v-model="addDogForm.chip_id"
              type="text"
              placeholder="Entrez le numéro de puce du chien"
              required
            ></b-form-input>
          </b-form-group>

          <b-button block type="submit" variant="outline-primary">
            Ajouter le chien
          </b-button>
        </b-form>
      </b-modal>
    </b-container>
  </div>
</template>

<script>
import { BIconArrowReturnLeft } from "bootstrap-vue";
export default {
  components: {
    BIconArrowReturnLeft,
  },
  name: "CustomerInformation",
  data() {
    return {
      title: null,
      id: null,
      email: null,
      firstname: null,
      lastname: null,
      phonenumber: null,
      address: null,
      code_role: null,
      dogs: [],
      addDogForm: {
        name: "",
        breed: "",
        sex: "",
        chip_id: "",
      },
      sex: ["Mâle", "Femelle"],
    };
  },
  methods: {
    loadAuthCustomerInformations() {
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token
        },
      };
      this.$http
        .get(
          "https://api-rest-douceur-de-chien.boreljaquet.ch/users/me/",
          config
        )
        .then((response) => {
          this.id = response.data.id;
          this.email = response.data.email;
          this.firstname = response.data.firstname;
          this.lastname = response.data.lastname;
          this.phonenumber = response.data.phonenumber;
          this.address = response.data.address;
          this.code_role = response.data.code_role;
          this.dogs = response.data.dogs;
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
        .get(
          "https://api-rest-douceur-de-chien.boreljaquet.ch/users/" + userId,
          config
        )
        .then((response) => {
          this.id = response.data.id;
          this.email = response.data.email;
          this.firstname = response.data.firstname;
          this.lastname = response.data.lastname;
          this.phonenumber = response.data.phonenumber;
          this.address = response.data.address;
          this.code_role = response.data.code_role;
          this.dogs = response.data.dogs;
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    addDogForCustomerWithUserId(userId) {
      const params = new URLSearchParams();
      params.append("name", this.addDogForm.name);
      params.append("breed", this.addDogForm.breed);
      params.append("sex", this.addDogForm.sex);
      params.append("chip_id", this.addDogForm.chip_id);
      params.append("user_id", userId);
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token,
          "Content-Type": "application/x-www-form-urlencoded",
        },
      };
      this.$http
        .post(
          "https://api-rest-douceur-de-chien.boreljaquet.ch/dogs/",
          params,
          config
        )
        .then((response) => {
          console.log(response);
          this.dogs.push({
            name: this.addDogForm.name,
            breed: this.addDogForm.breed,
            sex: this.addDogForm.sex,
            chip_id: this.addDogForm.chip_id,
          });
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
  },
  computed: {
    authAdministrator() {
      return this.$store.getters.ifAdministratorAuthenticated;
    },
  },
  mounted() {
    if (this.authAdministrator && this.$route.params.userId) {
      this.title = "Informations du client";
      this.loadCustomerInformationsByUserId(this.$route.params.userId);
    } else {
      this.title = "Mes informations";
      this.loadAuthCustomerInformations();
    }
  },
};
</script>

<style scoped>
#title {
  margin-top: 20px;
  color: #3ea3d8;
}
.btnReturn {
  padding-top: 18px;
  position: fixed;
  bottom: 100px;
  right: 15px;
  z-index: 5;
  display: block;
  height: 70px;
  width: 70px;
  border-radius: 50%;
  background-color: #008afc;
  box-shadow: -1px -1px 15px 1px rgba(0, 0, 0, 0.7);
}
.btnAdmin {
  width: 100%;
}
</style>
