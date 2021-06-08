<!--
  CustomerInformation.vue

  Component representing the personal information page of a client.

  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
-->

<template>
  <div>
    <b-container>
      <b-row class="text-center content">
        <b-col>
          <h1>{{ title }}</h1>
        </b-col>
      </b-row>
      <b-row class="content">
        <b-col
          md="4"
          style="margin-bottom: 10px"
          class="text-center d-flex flex-column align-items-center"
        >
          <b-card
            style="padding: 10px; width: 100%; margin-bottom: 10px"
            img-src="./../assets/img/user-profile.png"
            img-top
            :title="customer.firstname"
          >
            <p class="text-secondary mb-1" v-if="customer.code_role == '1'">
              Client
            </p>
            <p class="text-secondary mb-1">{{ customer.address }}</p>
            <div v-if="authCustomer">
              <b-button
                id="btnUpdatePassword"
                variant="outline-primary"
                v-b-modal.modal-update-password
                block
              >
                Modifier mon mot de passe
              </b-button>
            </div>
            <div v-if="authAdministrator">
              <b-button
                id="btnGoToAppointment"
                :to="{
                  name: 'customerAppointment',
                  params: { userId: $route.params.userId },
                }"
                variant="outline-primary"
                class="btnAdmin"
                v-if="authAdministrator"
                >Rendez-vous
              </b-button>
              <b-button
                id="btnAddDog"
                variant="outline-primary"
                class="btnAdmin"
                v-if="authAdministrator"
                v-b-modal.modal-add-dog
              >
                Ajouter un chien
              </b-button>
              <b-button
                id="btnAddDocument"
                variant="outline-primary"
                class="btnAdmin"
                v-if="authAdministrator"
                v-b-modal.modal-add-document
              >
                Ajouter un document
              </b-button>
              <b-button
                id="btnDeleteUser"
                variant="outline-danger"
                class="btnAdmin"
                v-if="authAdministrator"
                v-b-modal.modal-delete-user
              >
                Supprimer l'utilisateur
              </b-button>
            </div>
          </b-card>

          <b-card
            v-if="customer.documents.length > 0"
            style="padding: 10px; width: 100%"
          >
            <div v-for="document in customer.documents" :key="document.id">
              <b-row style="text-align: center; margin-top: 10px">
                <b-col>
                  <b-button
                    @click="
                      downloadDocument(
                        document.type,
                        document.document_serial_id
                      )
                    "
                    class="btnAdmin"
                    block
                    variant="outline-primary"
                  >
                    {{ document.type }}_{{ document.document_serial_id }}
                    <b-icon-download></b-icon-download>
                  </b-button>
                  <b-button
                    class="btnAdmin"
                    block
                    variant="outline-danger"
                    v-if="authAdministrator"
                    v-b-modal.modal-delete-document
                    @click="sendDocumentId(document.id)"
                  >
                    Supprimer le document
                  </b-button>
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
                {{ customer.firstname }} {{ customer.lastname }}
              </b-col>
            </b-row>
            <hr />
            <b-row>
              <b-col sm="3">
                <h6 class="mb-0">Adresse e-mail</h6>
              </b-col>
              <b-col sm="9" class="text-secondary">{{ customer.email }}</b-col>
            </b-row>
            <hr />
            <b-row>
              <b-col sm="3">
                <h6 class="mb-0">Numéro de téléphone</h6>
              </b-col>
              <b-col sm="9" class="text-secondary">{{
                customer.phonenumber
              }}</b-col>
            </b-row>
            <hr />
            <b-row>
              <b-col sm="3">
                <h6 class="mb-0">Adresse de domicile</h6>
              </b-col>
              <b-col sm="9" class="text-secondary">{{
                customer.address
              }}</b-col>
            </b-row>
            <div v-if="authAdministrator">
              <hr />
              <b-button
                id="btnUpdateUser"
                variant="outline-primary"
                class="btnAdmin"
                v-b-modal.modal-update-user
              >
                Modifier l'utilisateur
              </b-button>
            </div>
          </b-card>
          <b-row>
            <b-col md="6" v-for="dog in customer.dogs" :key="dog.id">
              <b-card style="margin-bottom: 10px">
                <b-row no-gutters>
                  <b-col md="12">
                    <b-card-img
                      v-if="dog.picture_serial_id"
                      :src="
                        $API_URL +
                        'dogs/downloadPicture/' +
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
                  <b-col sm="8" class="text-secondary">
                    <span v-if="dog.chip_id != ''">{{ dog.chip_id }}</span>
                    <span v-else>-</span>
                  </b-col>
                </b-row>
                <div v-if="authAdministrator">
                  <hr />
                  <b-button
                    id="btnUpdateDog"
                    variant="outline-primary"
                    class="btnAdmin"
                    v-b-modal.modal-update-dog
                    @click="
                      sendDogId(dog.id);
                      sendDogInformations(
                        dog.name,
                        dog.breed,
                        dog.sex,
                        dog.chip_id
                      );
                    "
                  >
                    Modifier le chien
                  </b-button>
                  <b-button
                    id="btnAddPictureDog"
                    class="btnAdmin"
                    variant="outline-primary"
                    v-b-modal.modal-add-picture-dog
                    @click="sendDogId(dog.id)"
                  >
                    Modifier la photo
                  </b-button>
                  <b-button
                    id="btnDeleteDog"
                    variant="outline-danger"
                    class="btnAdmin"
                    v-b-modal.modal-delete-dog
                    @click="sendDogId(dog.id)"
                  >
                    Supprimer le chien
                  </b-button>
                </div>
              </b-card>
            </b-col>
          </b-row>
        </b-col>
      </b-row>
      <button-return
        v-if="authAdministrator"
        :to="{
          name: 'administration',
        }"
      ></button-return>

      <!-- MODAL ADD DOG-->
      <b-modal id="modal-add-dog" title="Ajouter un chien" :hide-footer="true">
        <b-form
          @submit.prevent="
            createDogForCustomerByUserId(
              addDogForm.name,
              addDogForm.breed,
              addDogForm.sex,
              addDogForm.chip_id,
              $route.params.userId
            )
          "
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
              :options="sexDogOptions"
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
            ></b-form-input>
          </b-form-group>

          <b-button block type="submit" variant="outline-primary">
            Ajouter le chien
          </b-button>
        </b-form>
      </b-modal>

      <!-- MODAL ADD DOG PICTURE-->
      <b-modal
        id="modal-add-picture-dog"
        title="Ajouter une photo de chien"
        :hide-footer="true"
      >
        <b-form
          @submit.prevent="
            uploadDogPictureByDogId(selectedDog.id, selectedDog.pictureFile)
          "
        >
          <b-form-group
            id="input-group-dog-picture-file"
            label="Photo :"
            label-for="input-dog-picture-file"
          >
            <b-form-file
              required
              v-model="selectedDog.pictureFile"
              accept="image/jpeg, image/png"
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

      <!-- MODAL ADD DOCUMENT-->
      <b-modal
        id="modal-add-document"
        title="Ajouter un document"
        :hide-footer="true"
        @hidden="resetModalAddDocument"
      >
        <b-form
          @submit.prevent="
            createDocumentForCustomerByUserId(
              $route.params.userId,
              addDocumentForm.type,
              signatureBase64,
              addDocumentForm.packageNumber,
              addDocumentForm.file
            )
          "
        >
          <b-form-group
            id="input-group-document-type"
            label="Type du document :"
            label-for="input-document-type"
          >
            <b-form-select
              id="input-document-type"
              v-model="addDocumentForm.type"
              :options="documentOptions"
              required
            ></b-form-select>
          </b-form-group>

          <div v-if="addDocumentForm.type == 'conditions_inscription'">
            <a href="./pdf/conditionsInscription.pdf" target="_blank">
              Conditions d'inscription
            </a>
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
            <signature-pad @getSignaturePad="saveSignature"></signature-pad>
            <b-form-checkbox
              id="checkbox-validation"
              v-model="checkboxValidationStatus"
              name="checkbox-validation"
              @change="refreshStateOfcreateConditionsButton()"
              style="margin: 10px"
            >
              <h5>Lu et approuvé</h5>
            </b-form-checkbox>
            <b-button
              :disabled="createConditionsButtonisDisabled"
              block
              type="submit"
              variant="outline-primary"
            >
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
                v-model="addDocumentForm.file"
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

      <!-- MODAL DELETE USER-->
      <b-modal
        id="modal-delete-user"
        title="Confirmation de supression d'utilisateur"
        :hide-footer="true"
        :hide-header="true"
      >
        <h5 style="text-align: center">Supprimer l'utilisateur ?</h5>
        <b-form @submit.prevent="deleteUserById($route.params.userId)">
          <b-row>
            <b-col>
              <b-button
                block
                variant="danger"
                @click="$bvModal.hide('modal-delete-user')"
              >
                Non
              </b-button>
            </b-col>
            <b-col>
              <b-button block type="submit" variant="success">Oui</b-button>
            </b-col>
          </b-row>
        </b-form>
      </b-modal>

      <!-- MODAL DELETE DOCUMENT-->
      <b-modal
        id="modal-delete-document"
        title="Confirmation de supression d'utilisateur"
        :hide-footer="true"
        :hide-header="true"
      >
        <h5 style="text-align: center">Supprimer le document ?</h5>
        <b-form @submit.prevent="deleteDocumentById(selectedDocument.id)">
          <b-row>
            <b-col>
              <b-button
                block
                variant="danger"
                @click="$bvModal.hide('modal-delete-document')"
              >
                Non
              </b-button>
            </b-col>
            <b-col>
              <b-button
                block
                type="submit"
                variant="success"
                @click="$bvModal.hide('modal-delete-document')"
              >
                Oui
              </b-button>
            </b-col>
          </b-row>
        </b-form>
      </b-modal>

      <!-- MODAL DELETE DOG-->
      <b-modal
        id="modal-delete-dog"
        title="Confirmation de supression d'utilisateur"
        :hide-footer="true"
        :hide-header="true"
      >
        <h5 style="text-align: center">Supprimer le chien ?</h5>
        <b-form @submit.prevent="deleteDogById(selectedDog.id)">
          <b-row>
            <b-col>
              <b-button
                block
                variant="danger"
                @click="$bvModal.hide('modal-delete-dog')"
              >
                Non
              </b-button>
            </b-col>
            <b-col>
              <b-button
                @click="$bvModal.hide('modal-delete-dog')"
                block
                type="submit"
                variant="success"
              >
                Oui
              </b-button>
            </b-col>
          </b-row>
        </b-form>
      </b-modal>

      <!-- MODAL UPDATE USER-->
      <b-modal
        id="modal-update-user"
        title="Modifier l'utilisateur"
        :hide-footer="true"
      >
        <b-form
          @submit.prevent="
            updateUserById(
              $route.params.userId,
              customer.firstname,
              customer.lastname,
              customer.email,
              customer.phonenumber,
              customer.address
            )
          "
        >
          <b-form-group
            id="input-group-user-firstname"
            label="Prénom :"
            label-for="input-user-firstname"
          >
            <b-form-input
              id="input-user-firstname"
              v-model="customer.firstname"
              type="text"
              placeholder="Entrez le prénom de l'utilisateur"
              required
            ></b-form-input>
          </b-form-group>

          <b-form-group
            id="input-group-user-lastname"
            label="Nom :"
            label-for="input-user-lastname"
          >
            <b-form-input
              id="input-user-lastname"
              v-model="customer.lastname"
              type="text"
              placeholder="Entrez la nom de l'utilisateur"
              required
            ></b-form-input>
          </b-form-group>

          <b-form-group
            id="input-group-user-email"
            label="Adresse e-mail :"
            label-for="input-user-email"
          >
            <b-form-input
              id="input-user-email"
              v-model="customer.email"
              type="email"
              placeholder="Entrez l'adresse e-mail de l'utilisateur"
              required
            ></b-form-input>
          </b-form-group>

          <b-form-group
            id="input-group-user-phonenumber"
            label="Numéro de téléphone :"
            label-for="input-user-phonenumber"
          >
            <b-form-input
              id="input-user-phonenumber"
              v-model="customer.phonenumber"
              type="text"
              placeholder="Entrez le numéro de téléphone de l'utilisateur"
              required
            ></b-form-input>
          </b-form-group>

          <b-form-group
            id="input-group-user-address"
            label="Adresse de domicile :"
            label-for="input-user-address"
          >
            <b-form-input
              id="input-user-address"
              v-model="customer.address"
              type="text"
              placeholder="Entrez l'adresse de domicile de l'utilisateur"
              required
            ></b-form-input>
          </b-form-group>

          <b-button block type="submit" variant="outline-primary">
            Modifier l'utilisateur
          </b-button>
        </b-form>
      </b-modal>

      <!-- MODAL UPDATE DOG-->
      <b-modal
        id="modal-update-dog"
        title="Modifier le chien"
        :hide-footer="true"
      >
        <b-form
          @submit.prevent="
            updateDogById(
              selectedDog.id,
              selectedDog.name,
              selectedDog.breed,
              selectedDog.sex,
              selectedDog.chipId
            )
          "
        >
          <b-form-group
            id="input-group-dog-name"
            label="Nom :"
            label-for="input-dog-name"
          >
            <b-form-input
              id="input-dog-name"
              v-model="selectedDog.name"
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
              v-model="selectedDog.breed"
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
              v-model="selectedDog.sex"
              :options="sexDogOptions"
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
              v-model="selectedDog.chipId"
              type="text"
              placeholder="Entrez le numéro de puce du chien"
              required
            ></b-form-input>
          </b-form-group>

          <b-button block type="submit" variant="outline-primary">
            Modifier le chien
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
import { BIconDownload } from "bootstrap-vue";
import SignaturePad from "./SignaturePad.vue";
import ButtonReturn from "./ButtonReturn.vue";

export default {
  components: {
    BIconDownload,
    SignaturePad,
    ButtonReturn,
  },
  name: "CustomerInformation",
  data() {
    return {
      title: null,
      customer: {
        id: "",
        email: "",
        firstname: "",
        lastname: "",
        phonenumber: "",
        address: "",
        code_role: null,
        dogs: [],
        documents: [],
      },
      addDogForm: {
        name: "",
        breed: "",
        sex: "Mâle",
        chip_id: "",
        pictureFile: null,
      },
      selectedDog: {
        id: null,
        name: "",
        breed: "",
        sex: "",
        chipId: "",
      },
      sexDogOptions: ["Mâle", "Femelle"],
      selectedDocument: {
        id: null,
      },
      addDocumentForm: {
        type: "poster",
        packageNumber: 1,
        file: null,
      },
      documentOptions: [
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
      signatureIsBlocked: false,
      checkboxValidationStatus: false,
      createConditionsButtonisDisabled: true,
      password: null,
      repeatPassword: null,
    };
  },
  methods: {
    /**
     * Method to load all informations of the authenticated client from the api rest endpoint "GET api/v1/users/me".
     *
     */
    loadAuthCustomerInformations() {
      const config = {
        headers: {
          "Authorization" : this.$store.state.api_token
        },
      };
      this.$http
        .get(this.$API_URL + "users/me/", config)
        .then((response) => {
          this.customer.id = response.data.id;
          this.customer.email = response.data.email;
          this.customer.firstname = response.data.firstname;
          this.customer.lastname = response.data.lastname;
          this.customer.phonenumber = response.data.phonenumber;
          this.customer.address = response.data.address;
          this.customer.code_role = response.data.code_role;
          this.customer.dogs = response.data.dogs;
          this.customer.documents = response.data.documents;
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    /**
     * Method to load all informations of a client from his id from the api rest endpoint "GET api/v1/users/{userId}"
     *
     * @param {number} userId The client's id
     */
    loadCustomerInformationsByUserId(userId) {
      const config = {
        headers: {
          "Authorization" : this.$store.state.api_token
        },
      };
      this.$http
        .get(this.$API_URL + "users/" + userId, config)
        .then((response) => {
          console.log(response);
          this.customer.id = response.data.id;
          this.customer.email = response.data.email;
          this.customer.firstname = response.data.firstname;
          this.customer.lastname = response.data.lastname;
          this.customer.phonenumber = response.data.phonenumber;
          this.customer.address = response.data.address;
          this.customer.code_role = response.data.code_role;
          this.customer.dogs = response.data.dogs;
          this.customer.documents = response.data.documents;
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    /**
     * Method to create a dog for a user with the api rest endpoint "POST api/v1/dogs".
     *
     * @param {string} name The dog's name
     * @param {string} breed The dog's breed
     * @param {string} sex The dog's sex
     * @param {string} chipId The dog's chip id
     * @param {number} userId The identifier of the user owner
     */
    createDogForCustomerByUserId(name, breed, sex, chipId, userId) {
      const params = new URLSearchParams();
      params.append("name", name);
      params.append("breed", breed);
      params.append("sex", sex);
      params.append("chip_id", chipId);
      params.append("user_id", userId);
      const config = {
        headers: {
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .post(this.$API_URL + "dogs/", params, config)
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
    /**
     * Method to upload a picture dog for a dog from the api rest endpoint "POST api/v1/dogs/uploadPicture".
     *
     * @param {number} id The dog id
     * @param {File} pictureFile The dog picture file
     */
    uploadDogPictureByDogId(id, pictureFile) {
      let formData = new FormData();
      formData.append("dog_picture", pictureFile);
      formData.append("dog_id", id);
      const config = {
        headers: {
          "Authorization" : this.$store.state.api_token
        },
      };
      this.$http
        .post(this.$API_URL + "dogs/uploadPicture/", formData, config)
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
    /**
     * Method to create a document for a user from the api rest endpoint "POST api/v1/documents".
     *
     * @param {string} userId The user id
     * @param {string} type The document type
     * @param {?string} signatureBase64 The signature in base64 format for the conditions of registration
     * @param {?number} packageNumber The package number for the conditions of registration
     * @param {?File} file The PDF document file
     */
    createDocumentForCustomerByUserId(
      userId,
      type,
      signatureBase64,
      packageNumber,
      file
    ) {
      var params;
      if (type == "conditions_inscription") {
        params = new URLSearchParams();
        params.append("signature_base64", signatureBase64);
        params.append("package_number", packageNumber);
      } else {
        params = new FormData();
        params.append("document", file);
      }
      params.append("type", type);
      params.append("user_id", userId);

      const config = {
        headers: {
          "Authorization" : this.$store.state.api_token
        },
      };
      this.$http
        .post(this.$API_URL + "documents/", params, config)
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
    /**
     * Method to downlaod a document with the api rest endpoint "GET api/v1/documents/downloadDocument/{documentSerialId}".
     *
     * @param {string} type The document type
     * @param {string} serial_id The document serial id
     */
    downloadDocument(type, serial_id) {
      const config = {
        headers: {
          "Authorization" : this.$store.state.api_token,
        },
        responseType: "blob",
      };
      this.$http
        .get(this.$API_URL + "documents/downloadDocument/" + serial_id, config)
        .then((response) => {
          var blob = new Blob([response.data]);
          var file = window.URL.createObjectURL(blob);
          var a = document.createElement("a");
          a.href = file;
          a.download = type + "_" + serial_id + ".pdf";
          document.body.appendChild(a);
          a.click();
          document.body.removeChild(a);
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    /**
     * Method to delete a user from his id from the api rest endpoint "DELETE api/v1/users/{userId}"
     *
     * @param {string} id The user id
     */
    deleteUserById(id) {
      const config = {
        headers: {
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .delete(this.$API_URL + "users/" + id, config)
        .then((response) => {
          this.$alertify.success("Utilisateur supprimé");
          this.$router.push("/administration");
          console.log(response);
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    /**
     * Method to delete a dog from his id from the api rest endpoint "DELETE api/v1/dogs/{dogId}"
     *
     * @param {string} id The dog id
     */
    deleteDogById(id) {
      const config = {
        headers: {
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .delete(this.$API_URL + "dogs/" + id, config)
        .then((response) => {
          this.loadCustomerInformationsByUserId(this.$route.params.userId);
          this.$alertify.success("Chien supprimé");
          console.log(response);
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    /**
     * Method to delete a document from his id from the api rest endpoint "DELETE api/v1/documents/{documentId}"
     *
     * @param {string} id The document id
     */
    deleteDocumentById(id) {
      const config = {
        headers: {
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .delete(this.$API_URL + "documents/" + id, config)
        .then((response) => {
          this.loadCustomerInformationsByUserId(this.$route.params.userId);
          this.$alertify.success("Document supprimé");
          console.log(response);
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    /**
     * Method to update a user from his id from the api rest endpoint "PATCH api/v1/users/{userId}"
     *
     * @param {string} id The user id
     * @param {string} firstname The user's first name
     * @param {string} lastname The user's last name
     * @param {string} email The user's email address
     * @param {string} phonenumber The user's phonenumber
     * @param {string} address The user's address
     */
    updateUserById(id, firstname, lastname, email, phonenumber, address) {
      const params = new URLSearchParams();
      params.append("firstname", firstname);
      params.append("lastname", lastname);
      params.append("email", email);
      params.append("phonenumber", phonenumber);
      params.append("address", address);
      const config = {
        headers: {
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .patch(this.$API_URL + "users/" + id, params, config)
        .then((response) => {
          console.log(response);
          this.loadCustomerInformationsByUserId(this.$route.params.userId);
          this.$alertify.success("Utilisateur modifié");
          this.$bvModal.hide("modal-update-user");
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    /**
     * Method to update a dog from his id from the api rest endpoint "PATCH api/v1/dogs/{dogId}"
     *
     * @param {string} id The dog id
     * @param {string} name The dog's name
     * @param {string} breed The dog's breed
     * @param {string} sex The dog's sex
     * @param {string} chipId The dog's chip id
     */
    updateDogById(id, name, breed, sex, chipId) {
      const params = new URLSearchParams();
      params.append("name", name);
      params.append("breed", breed);
      params.append("sex", sex);
      params.append("chip_id", chipId);
      const config = {
        headers: {
          "Authorization" : this.$store.state.api_token,
        },
      };
      this.$http
        .patch(this.$API_URL + "dogs/" + id, params, config)
        .then((response) => {
          console.log(response);
          this.loadCustomerInformationsByUserId(this.$route.params.userId);
          this.$alertify.success("Chien modifié");
          this.$bvModal.hide("modal-update-dog");
        })
        .catch((error) => {
          this.$alertify.error(error.response.data.error);
        });
    },
    /**
     * Method to update the password of the authenticated user with the api rest endpoint "PATCH api/v1/users/me/changePassword".
     *
     * @param {string} password The new password
     * @param {string} repeatPassword Repetition of the new password
     */
    updateAuthCustomerPassword(password, repeatPassword) {
      if (password != repeatPassword) {
        this.$alertify.error("les mots de passes ne corréspondent pas");
        return;
      }
      const params = new URLSearchParams();
      params.append("password", password);
      const config = {
        headers: {
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
    resetModalAddDocument() {
      this.addDocumentForm.type = "poster";
      this.signatureIsBlocked = false;
      this.checkboxValidationStatus = false;
    },
    refreshStateOfcreateConditionsButton() {
      if (this.signatureIsBlocked && this.checkboxValidationStatus) {
        this.createConditionsButtonisDisabled = false;
      } else {
        this.createConditionsButtonisDisabled = true;
      }
    },
    /**
     * Method to allow the component to read the id of a dog in order to proceed to a modification or a deletion from a modal.
     *
     * @param {number} id The dog id
     */
    sendDogId(id) {
      this.selectedDog.id = id;
    },
    /**
     * Method to allow the component to read the data of a dog in order to proceed to a modification or a deletion from a modal.
     *
     * @param {string} name The dog's name
     * @param {string} breed The dog's breed
     * @param {string} sex The dog's sex
     * @param {string} chipId The dog's chip id
     */
    sendDogInformations(name, breed, sex, chipId) {
      this.selectedDog.name = name;
      this.selectedDog.breed = breed;
      this.selectedDog.sex = sex;
      this.selectedDog.chipId = chipId;
    },
    /**
     * Method to allow the component to read the id of a document in order to proceed to a modification or a deletion from a modal.
     *
     * @param {number} id The document id
     */
    sendDocumentId(id) {
      this.selectedDocument.id = id;
    },
    saveSignature(...args) {
      const [base64, isBlocked] = args;
      this.signatureBase64 = base64;
      this.signatureIsBlocked = isBlocked;
    },
  },
  watch: {
    signatureIsBlocked: function () {
      this.refreshStateOfcreateConditionsButton();
    },
  },
  computed: {
    authAdministrator() {
      return this.$store.getters.ifAdministratorAuthenticated;
    },
    authCustomer() {
      return this.$store.getters.ifCustomerAuthenticated;
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
.content {
  margin-top: 20px;
  color: #3ea3d8;
}
.btnAdmin {
  width: 100%;
  margin-bottom: 10px;
}
</style>
