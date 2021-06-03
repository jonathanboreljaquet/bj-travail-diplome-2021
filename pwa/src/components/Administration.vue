<template>
  <div>
    <b-container>
      <b-row id="title" class="text-center">
        <b-col>
          <h1>Administration</h1>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <b-button
            id="btnUpdatePassword"
            variant="outline-primary"
            v-b-modal.modal-update-password
            block
          >
            Modifier mon mot de passe
          </b-button>
          <b-button
            id="btnAddClient"
            style="margin-bottom: 10px; margin-top: 10px"
            variant="outline-primary"
            block
            v-b-modal.modal-add-client
          >
            Ajouter un client
          </b-button>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <b-input-group size="sm" style="margin-bottom: 10px">
            <b-form-input
              id="filter-input"
              v-model="filter"
              type="search"
              placeholder="Tapez pour rechercher"
            ></b-form-input>

            <b-input-group-append>
              <b-button :disabled="!filter" @click="filter = ''">
                Effacer
              </b-button>
            </b-input-group-append>
          </b-input-group>
        </b-col>
      </b-row>
      <b-pagination
        v-model="currentPage"
        align="fill"
        size="sm"
        class="my-0"
        :total-rows="totalRows"
      ></b-pagination>
      <b-table
        ref="tableuser"
        :items="items"
        :busy="isBusy"
        :fields="fields"
        :sort-by.sync="sortBy"
        :sort-desc.sync="sortDesc"
        :filter="filter"
        :filter-included-fields="filterOn"
        :perPage="10"
        :current-page="currentPage"
        sort-icon-left
        striped
        responsive="sm"
      >
        <template #table-busy>
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong> Chargement...</strong>
          </div>
        </template>

        <template #cell(chiens)="row">
          <b-button size="sm" @click="row.toggleDetails" class="mr-2 tableBtn">
            {{ row.detailsShowing ? "Cacher" : "Afficher" }}
          </b-button>
        </template>
        <template #cell(edition)="row">
          <b-button
            :to="{
              name: 'customerInformation',
              params: { userId: row.item.id },
            }"
            class="tableBtn"
          >
            <b-icon-pencil-square></b-icon-pencil-square>
          </b-button>
        </template>
        <template #row-details="row">
          <div v-if="row.item.dogs.length > 0">
            <b-card
              style="margin-bottom: 5px"
              v-for="dog in row.item.dogs"
              :key="dog.id"
            >
              <b-row>
                <b-col><b>Nom du chien:</b></b-col>
                <b-col>{{ dog.name }}</b-col>
              </b-row>
              <b-row>
                <b-col><b>Photo du chien:</b></b-col>
                <b-col v-if="dog.picture_serial_id">
                  <b-img
                    :src="
                      $API_URL + 'dogs/downloadPicture/' + dog.picture_serial_id
                    "
                    rounded
                    fluid
                    :alt="dog.name + ' picture'"
                  ></b-img>
                </b-col>
                <b-col v-else> Le chien n'a pas encore de photo</b-col>
              </b-row>
            </b-card>
          </div>
          <b-card v-else>
            <b-row>
              <b-col>L'utilisateur n'a aucun chiens pour l'instant</b-col>
            </b-row>
          </b-card>
        </template>
      </b-table>

      <!-- MODAL ADD CLIENT  -->
      <b-modal
        id="modal-add-client"
        title="Ajouter un client"
        :hide-footer="true"
      >
        <b-form
          @submit.prevent="
            createUser(
              form.email,
              form.firstname,
              form.lastname,
              form.phonenumber,
              form.address
            )
          "
        >
          <b-form-group
            id="input-group-client-email"
            label="Adresse e-mail :"
            label-for="input-client-email"
          >
            <b-form-input
              id="input-client-email"
              v-model="form.email"
              type="email"
              placeholder="Entrez l'adresse e-mail du client"
              required
            ></b-form-input>
          </b-form-group>
          <b-form-group
            id="input-group-client-firstname"
            label="Prénom :"
            label-for="input-client-firstname"
          >
            <b-form-input
              id="input-client-firstname"
              v-model="form.firstname"
              type="text"
              placeholder="Entrez le prénom du client"
              required
            ></b-form-input>
          </b-form-group>
          <b-form-group
            id="input-group-client-lastname"
            label="Nom de famille :"
            label-for="input-client-lastname"
          >
            <b-form-input
              id="input-client-lastname"
              v-model="form.lastname"
              type="text"
              placeholder="Entrez le nom de famille du client"
              required
            ></b-form-input>
          </b-form-group>
          <b-form-group
            id="input-group-client-phonenumber"
            label="Numéro de téléphone :"
            label-for="input-client-phonenumber"
          >
            <b-form-input
              id="input-client-phonenumber"
              v-model="form.phonenumber"
              type="text"
              placeholder="Entrez le numéro de téléphone du client"
              required
            ></b-form-input>
          </b-form-group>
          <b-form-group
            id="input-group-client-address"
            label="Adresse :"
            label-for="input-client-address"
          >
            <b-form-input
              id="input-client-address"
              v-model="form.address"
              type="text"
              placeholder="Entrez l'addresse du client"
              required
            ></b-form-input>
          </b-form-group>
          <b-button block type="submit" variant="outline-primary">
            Ajouter le client
          </b-button>
        </b-form>
      </b-modal>

      <!-- MODAL UPDATE PASSWORD-->
      <b-modal
        id="modal-update-password"
        title="Modifier le mot de passe"
        :hide-footer="true"
      >
        <b-form
          @submit.prevent="updateAuthCustomerPassword(password, repeatPassword)"
        >
          <b-form-group
            id="input-group-inscription-password"
            label="Mot de passe :"
            label-for="input-inscription-password"
          >
            <b-form-input
              id="input-inscription-password"
              v-model="password"
              type="password"
              placeholder="Entrez le mot de passe"
              required
            ></b-form-input>
          </b-form-group>
          <b-form-group
            id="input-group-inscription-second-password"
            label="Confirmation du mot de passe :"
            label-for="input-inscription-second-password"
          >
            <b-form-input
              id="input-inscription-second-password"
              v-model="repeatPassword"
              type="password"
              placeholder="Entrez le mot de passe"
              required
            ></b-form-input>
          </b-form-group>

          <b-button block type="submit" variant="outline-primary">
            Modifier le mot de passe
          </b-button>
        </b-form>
      </b-modal>
    </b-container>
  </div>
</template>

<script>
import { BIconPencilSquare } from "bootstrap-vue";
export default {
  components: {
    BIconPencilSquare,
  },
  name: "Administration",
  data() {
    return {
      sortBy: "nom",
      sortDesc: false,
      fields: [
        { key: "nom", sortable: true },
        { key: "prenom", sortable: true },
        { key: "chiens", sortable: false },
        { key: "edition", sortable: false },
      ],
      items: [],
      filter: null,
      filterOn: ["nom", "prenom"],
      isBusy: true,
      currentPage: 1,
      totalRows: 1,
      form: {
        email: "",
        firstname: "",
        lastname: "",
        phonenumber: "",
        address: "",
      },
      password: "",
      repeatPassword: "",
    };
  },
  methods: {
    loadCustomersWithDogs() {
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token
        },
      };
      this.$http
        .get(this.$API_URL + "users/", config)
        .then((response) => {
          this.items = [];
          response.data.forEach((user) => {
            user["prenom"] = user["firstname"];
            delete user["firstname"];
            user["nom"] = user["lastname"];
            delete user["lastname"];
          });
          this.items = response.data;
          this.toggleBusy();
          this.totalRows = this.items.length + 10;
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    createUser(email, firstname, lastname, phonenumber, address) {
      const params = new URLSearchParams();
      params.append("email", email);
      params.append("firstname", firstname);
      params.append("lastname", lastname);
      params.append("phonenumber", phonenumber);
      params.append("address", address);
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .post(this.$API_URL + "users/", params, config)
        .then((response) => {
          console.log(response);
          this.loadCustomersWithDogs();
          this.$alertify.success("Client ajouté avec succès");
          this.$bvModal.hide("modal-add-client");
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    updateAuthCustomerPassword(password, repeatPassword) {
      if (password != repeatPassword) {
        this.$alertify.error("les mots de passes ne corréspondent pas");
        return;
      }
      const params = new URLSearchParams();
      params.append("password", password);
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .patch(this.$API_URL + "users/me/changePassword/", params, config)
        .then((response) => {
          console.log(response);
          this.$alertify.success("Mot de passe modifié");
          this.$bvModal.hide("modal-update-password");
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    toggleBusy() {
      this.isBusy = false;
    },
  },
  created() {
    this.loadCustomersWithDogs();
  },
};
</script>

<style scoped>
#title {
  margin-top: 20px;
  color: #3ea3d8;
}
.tableBtn {
  background-color: #3ea3d8;
}
</style>
