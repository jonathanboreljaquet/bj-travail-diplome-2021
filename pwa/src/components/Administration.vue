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
                      'https://api-rest-douceur-de-chien.boreljaquet.ch/dogs/downloadPicture/' +
                      dog.picture_serial_id
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
        .get("https://api-rest-douceur-de-chien.boreljaquet.ch/users/", config)
        .then((response) => {
          const vm = this;
          this.$jquery.each(response.data, function (index, item) {
            vm.items.push({
              id: item.id,
              prenom: item.firstname,
              nom: item.lastname,
              dogs: item.dogs,
            });
          });
          this.toggleBusy();
          this.totalRows = this.items.length;
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    toggleBusy() {
      this.isBusy = !this.isBusy;
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
