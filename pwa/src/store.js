import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";
import router from "./router";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    api_token: null,
    code_role: null,
  },
  mutations: {
    authUser(state, userData) {
      state.api_token = userData.api_token;
      state.code_role = userData.code_role;
    },
    clearAuth(state) {
      state.api_token = null;
      state.code_role = null;
    },
  },
  actions: {
    login({ commit }, authData) {
      const params = new URLSearchParams();
      params.append("email", authData.email);
      params.append("password", authData.password);
      const config = {
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
      };
      axios
        .post(
          "https://api-rest-douceur-de-chien.boreljaquet.ch/connection/",
          params,
          config
        )
        .then((res) => {
          localStorage.setItem("api_token", res.data.api_token);
          localStorage.setItem("code_role", res.data.code_role);
          commit("authUser", {
            api_token: res.data.api_token,
            code_role: res.data.code_role,
          });
          Vue.prototype.$alertify.success("Vous êtes connecté");
          if (this.state.code_role == "2") {
            router.push("/administration");
          }
          if (this.state.code_role == "1") {
            router.push("/customer_information");
          }
        })
        .catch((error) => {
          Vue.prototype.$alertify.error(
            "Le nom d'utilisateur et le mot de passe que vous avez entrés ne correspondent pas à ceux présents dans nos fichiers. Veuillez vérifier et réessayer."
          );
          console.log(error);
        });
    },
    logout({ commit }) {
      commit("clearAuth");
      localStorage.removeItem("api_token");
      localStorage.removeItem("code_role");
      router.replace("/");
    },
    autoLogin({ commit }) {
      const api_token = localStorage.getItem("api_token");
      const code_role = localStorage.getItem("code_role");
      if (!api_token || !code_role) {
        return;
      }
      commit("authUser", {
        api_token: api_token,
        code_role: code_role,
      });
    },
  },
  getters: {
    ifCustomerAuthenticated(state) {
      console.log(state);
      return state.api_token !== null && state.code_role == "1";
    },
    ifAdministratorAuthenticated(state) {
      return state.api_token !== null && state.code_role == "2";
    },
    ifAuthenticated(state) {
      return state.api_token !== null;
    },
  },
});
