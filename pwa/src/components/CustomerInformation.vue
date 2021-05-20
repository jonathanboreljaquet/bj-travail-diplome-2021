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
            <b-button
              class="btnAdmin"
              v-if="authAdministrator"
              v-b-modal.modal-add-document
            >
              Ajouter un document
            </b-button>
          </b-card>

          <b-card style="padding: 10px; width: 100%">
            <div v-for="document in documents" :key="document.id">
              <b-row style="text-align: center">
                <b-col>
                  <a @click="downloadDocument(document.document_serial_id)">
                    {{ document.type }}_{{ document.document_serial_id }}
                  </a>
                </b-col>
              </b-row>
              <hr style="margin-bottom: 0px; margin-top: 0px" />
            </div>
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
                      bottom
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
                <div v-if="!dog.picture_serial_id && authAdministrator">
                  <hr />
                  <b-row>
                    <b-col>
                      <b-button
                        class="btnAdmin"
                        style="margin-bottom: 10px"
                        v-b-modal.modal-add-picture-dog
                        @click="sendDogId(dog.id)"
                      >
                        Ajouter une photo
                      </b-button>
                    </b-col>
                  </b-row>
                </div>
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
      <b-modal id="modal-add-dog" title="Ajouter un chien" :hide-footer="true">
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
      <b-modal
        id="modal-add-picture-dog"
        title="Ajouter une photo de chien"
        :hide-footer="true"
      >
        <b-form @submit.prevent="uploadDogPictureByDogId(selectedDogId)">
          <b-form-group
            id="input-group-dog-picture-file"
            label="Photo :"
            label-for="input-dog-picture-file"
          >
            <b-form-file
              required
              v-model="dogPictureFile"
              :capture="true"
              placeholder="Aucun fichier choisi"
              browse-text="Ajouter"
              id="input-dog-picture-file"
              size="sm"
            ></b-form-file>
          </b-form-group>

          <b-button block type="submit" variant="outline-primary">
            Ajouter la photo
          </b-button>
        </b-form>
      </b-modal>
      <b-modal
        id="modal-add-document"
        title="Ajouter un document"
        :hide-footer="true"
        @hidden="resetModalAddDocument"
      >
        <b-form @submit.prevent="createDocument()">
          <b-form-group
            id="input-group-document-type"
            label="Type du document :"
            label-for="input-document-type"
          >
            <b-form-select
              id="input-document-type"
              v-model="addDocumentForm.type"
              :options="documentTypes"
              required
            ></b-form-select>
          </b-form-group>

          <div v-if="addDocumentForm.type == 'conditions_inscription'">
            <b-form-group
              id="input-group-document-package-number"
              label="Forfait :"
              label-for="input-document-package-number"
            >
              <b-form-select
                id="input-document-package-number"
                v-model="addDocumentForm.packageNumber"
                :options="packageNumbers"
                required
              ></b-form-select>
            </b-form-group>
            <sketchpad @saveSignature="saveSignature"></sketchpad>
            <b-button block type="submit" variant="outline-primary">
              Créer les conditions d'inscription
            </b-button>
          </div>
          <div v-else>
            <b-form-group
              id="input-group-document-file"
              label="Document PDF :"
              label-for="input-dog-document-file"
            >
              <b-form-file
                required
                v-model="documentFile"
                placeholder="Aucun fichier choisi"
                browse-text="Ajouter"
                id="input-document-file"
                size="sm"
              ></b-form-file>
            </b-form-group>
            <b-button block type="submit" variant="outline-primary">
              Ajouter le document
            </b-button>
          </div>
        </b-form>
      </b-modal>
    </b-container>
  </div>
</template>

<script>
import { BIconArrowReturnLeft } from "bootstrap-vue";
import Sketchpad from "./Sketchpad.vue";

export default {
  components: {
    BIconArrowReturnLeft,
    Sketchpad,
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
      documents: [],
      addDogForm: {
        name: "",
        breed: "",
        sex: "Mâle",
        chip_id: "",
      },
      selectedDogId: "",
      dogPictureFile: null,
      sex: ["Mâle", "Femelle"],
      documentFile: null,
      addDocumentForm: {
        type: "poster",
        packageNumber: 1,
      },
      documentTypes: [
        { value: "poster", text: "Document pdf" },
        { value: "conditions_inscription", text: "Conditions d'inscription" },
      ],
      packageNumbers: [
        { value: 1, text: "Bilan d’évaluation : 70 € / 80 CHF" },
        { value: 2, text: "Bilan + 1 séance d’éducation : 125 € / 140 CHF" },
        { value: 3, text: "Forfait bilan + 3 séances : 230 € / 250 CHF" },
        { value: 4, text: "Forfait bilan + 6 séances : 400 € / 440 CHF" },
        { value: 5, text: "Forfait bilan + 9 séances : 520 € / 560 CHF" },
      ],
      signatureBase64: "",
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
          this.documents = response.data.documents;
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
          console.log(response);
          this.id = response.data.id;
          this.email = response.data.email;
          this.firstname = response.data.firstname;
          this.lastname = response.data.lastname;
          this.phonenumber = response.data.phonenumber;
          this.address = response.data.address;
          this.code_role = response.data.code_role;
          this.dogs = response.data.dogs;
          this.documents = response.data.documents;
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
          this.loadCustomerInformationsByUserId(this.$route.params.userId);
          this.$alertify.success("Chien ajouté avec succès");
          this.$bvModal.hide("modal-add-dog");
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    uploadDogPictureByDogId(dogId) {
      let formData = new FormData();
      formData.append("dog_picture", this.dogPictureFile);
      formData.append("dog_id", dogId);
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token
        },
      };
      this.$http
        .post(
          "https://api-rest-douceur-de-chien.boreljaquet.ch/dogs/uploadPicture/",
          formData,
          config
        )
        .then((response) => {
          console.log(response);
          this.loadCustomerInformationsByUserId(this.$route.params.userId);
          this.$alertify.success("Photo du chien ajouté avec succès");
          this.$bvModal.hide("modal-add-picture-dog");
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    createDocument() {
      var params;
      if (this.addDocumentForm.type == "conditions_inscription") {
        params = new URLSearchParams();
        params.append("type", this.addDocumentForm.type);
        params.append("signature_base64", this.signatureBase64);
        params.append("package_number", this.addDocumentForm.packageNumber);
        params.append("user_id", this.$route.params.userId);
      } else {
        params = new FormData();
        params.append("type", this.addDocumentForm.type);
        params.append("document", this.documentFile);
        params.append("user_id", this.$route.params.userId);
      }

      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token
        },
      };
      this.$http
        .post(
          "https://api-rest-douceur-de-chien.boreljaquet.ch/documents/",
          params,
          config
        )
        .then((response) => {
          console.log(response);
          this.loadCustomerInformationsByUserId(this.$route.params.userId);
          this.$alertify.success("Document ajouté avec succès");
          this.$bvModal.hide("modal-add-document");
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    downloadDocument(document_serial_id) {
      const config = {
        headers: {
          // eslint-disable-next-line prettier/prettier
          "Authorization" : this.$store.state.api_token
        },
      };
      this.$http
        .get(
          "https://api-rest-douceur-de-chien.boreljaquet.ch/documents/downloadDocument/" +
            document_serial_id,
          config
        )
        .then((response) => {
          var blob = new Blob([response.data]);
          const link = document.createElement('a');
          // create a blobURI pointing to our Blob
          link.href = URL.createObjectURL(blob);
          link.download = "fileName.pdf";
          // some browser needs the anchor to be in the doc
          document.body.append(link);
          link.click();
          link.remove();
          // in case the Blob uses a lot of memory
          setTimeout(() => URL.revokeObjectURL(link.href), 7000);
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    resetModalAddDocument() {
      this.addDocumentForm.type = "poster";
    },
    sendDogId(dogId) {
      this.selectedDogId = dogId;
    },
    saveSignature(value) {
      this.signatureBase64 = value;
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
