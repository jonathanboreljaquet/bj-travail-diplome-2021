<template>
  <div>
    <b-table
      :items="items"
      :fields="fields"
      :sort-by.sync="sortBy"
      :sort-desc.sync="sortDesc"
      sort-icon-left
      responsive="sm"
    ></b-table>
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
      ],
      items: []
    }
  },
  methods: {
    loadCustomers() {
      const config = {
        headers: {
          "Authorization" : this.$store.state.api_token
        }};
      this.$http
        .get("https://api-rest-douceur-de-chien.boreljaquet.ch/users/", config)
        .then((response) => {
          const vm = this;
          this.$jquery.each(response.data, function (index) {
            vm.items.push({
              prenom: response.data[index].firstname,
              nom: response.data[index].lastname,
            });
          });
          console.log(response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    }
  },
  mounted() {
    this.loadCustomers();
  },
};
</script>

<style scoped>
#title {
  margin-top: 20px;
  color: #3ea3d8;
}
</style>
