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
          <b-input-group size="sm">
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
      <b-table
        :items="items"
        :fields="fields"
        :sort-by.sync="sortBy"
        :sort-desc.sync="sortDesc"
        :filter="filter"
        :filter-included-fields="filterOn"
        sort-icon-left
        striped
        responsive="sm"
      >
        <template #cell(chiens)="row">
          <b-button size="sm" @click="row.toggleDetails" class="mr-2">
            {{ row.detailsShowing ? "Cacher les" : "Afficher les"}} chiens
          </b-button>
        </template>
        <template #cell(details)="row">
          <b-button :value="row.item.id"> Afficher les détails</b-button>
        </template>

        <template #row-details="row">
          <div v-if="row.item.dogs.length > 0">
            <b-card style="margin-bottom: 5px" v-for="dog in row.item.dogs" :key="dog.id">
              <b-row>
                <b-col><b>Nom du chien:</b></b-col>
                <b-col>{{ dog.name }}</b-col>
              </b-row>
              <b-row>
                <b-col><b>Photo du chien:</b></b-col>
                <b-col v-if="dog.base64_picture">
                  <b-img
                    :src="dog.base64_picture"
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
              <b-col>
                <p>L'utilisateur n'a aucun chiens pour l'instant</p>
              </b-col>
            </b-row>
          </b-card>
        </template>
      </b-table>
    </b-container>
  </div>
</template>

<script>
export default {
  name: "Administration",
  data() {
    return {
      sortBy: "nom",
      sortDesc: false,
      fields: [
        { key: "nom", sortable: true },
        { key: "prenom", sortable: true },
        { key: "chiens", sortable: false },
        { key: "details", sortable: false },
      ],
      items: [],
      filter: null,
      filterOn: ["nom", "prenom"],
    };
  },
  methods: {
    loadCustomersWithDogs() {
      const config = {
        headers: {
          "Authorization" : this.$store.state.api_token
        },
      };
      this.$http
        .get("https://api-rest-douceur-de-chien.boreljaquet.ch/users/", config)
        .then((response) => {
          const vm = this;
          let url = "https://api-rest-douceur-de-chien.boreljaquet.ch/dogs/downloadPicture/";
          var promisedEvents = [];
          this.$jquery.each(response.data, function (index, item) {
            vm.$jquery.each(item.dogs, function (index, item) {
              if (item.picture_serial_id) {
                promisedEvents.push(vm.$http.get(url + item.picture_serial_id));
              }
            });
            vm.items.push({
              id: item.id,
              prenom: item.firstname,
              nom: item.lastname,
              dogs: item.dogs,
            });
          });
          return Promise.all(promisedEvents);
        })
        .then((response) => {
          const vm = this;
          vm.$jquery.each(response, function (index, promise_result) {
            vm.$jquery.each(vm.items, function (index, item) {
              var parts = promise_result.config.url.split("/");
              var picture_serial_id = parts[parts.length - 1];
              const dog = item.dogs.find(element => element.picture_serial_id == picture_serial_id);
              if (dog) {
                dog["base64_picture"] = promise_result.data;
              }
            });
          });
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  mounted() {
    this.loadCustomersWithDogs();
  },
};
</script>

<style scoped>
#title {
  margin-top: 20px;
  color: #3ea3d8;
}
</style>
