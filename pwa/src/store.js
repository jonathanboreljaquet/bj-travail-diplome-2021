import Vue from "vue";
import Vuex from "vuex";
import router from "./router";
import { CUSTOMER_CODE_ROLE, ADMIN_CODE_ROLE } from "./constants.js";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    api_token: null,
    code_role: null,
    user_id: null,
  },
  mutations: {
    authUser(state, userData) {
      state.api_token = userData.api_token;
      state.code_role = userData.code_role;
      state.user_id = userData.user_id;
    },
    clearAuth(state) {
      state.api_token = null;
      state.code_role = null;
      state.user_id = null;
    },
  },
  actions: {
    inscription({ commit }, authData) {
      const params = new URLSearchParams();
      params.append("email", authData.email);
      params.append("firstname", authData.firstname);
      params.append("lastname", authData.lastname);
      params.append("phonenumber", authData.phonenumber);
      params.append("address", authData.address);
      params.append("password", authData.password);
      params.append(
        "reCAPTCHAuserResponseToken",
        authData.reCAPTCHAuserResponseToken
      );
      Vue.prototype.$http
        .post(Vue.prototype.$API_URL + "users/", params)
        .then((res) => {
          console.log(res);
          localStorage.setItem("api_token", res.data.api_token);
          localStorage.setItem("code_role", res.data.code_role);
          localStorage.setItem("user_id", res.data.user_id);
          commit("authUser", {
            api_token: res.data.api_token,
            code_role: res.data.code_role,
            user_id: res.data.user_id,
          });
          Vue.prototype.$alertify.success("Vous êtes inscrit");
          router.push("/customer_information");
        })
        .catch((error) => {
          Vue.prototype.$alertify.error(error.response.data.error);
        });
    },
    login({ commit }, authData) {
      const params = new URLSearchParams();
      params.append("email", authData.email);
      params.append("password", authData.password);
      Vue.prototype.$http
        .post(Vue.prototype.$API_URL + "connection/", params)
        .then((res) => {
          localStorage.setItem("api_token", res.data.api_token);
          localStorage.setItem("code_role", res.data.code_role);
          localStorage.setItem("user_id", res.data.user_id);
          commit("authUser", {
            api_token: res.data.api_token,
            code_role: res.data.code_role,
            user_id: res.data.user_id,
          });
          Vue.prototype.$alertify.success("Vous êtes connecté");
          if (this.state.code_role == ADMIN_CODE_ROLE) {
            router.push("/administration");
          }
          if (this.state.code_role == CUSTOMER_CODE_ROLE) {
            router.push("/customer_information");
          }
        })
        .catch((error) => {
          Vue.prototype.$alertify.error(error.response.data.error);
        });
    },
    logout({ commit }) {
      commit("clearAuth");
      localStorage.removeItem("api_token");
      localStorage.removeItem("code_role");
      localStorage.removeItem("user_id");
      Vue.prototype.$alertify.success("Vous êtes déconnecté");
      router.push("/");
    },
    autoLogin({ commit }) {
      const api_token = localStorage.getItem("api_token");
      const code_role = localStorage.getItem("code_role");
      const user_id = localStorage.getItem("user_id");
      if (!api_token || !code_role || !user_id) {
        return;
      }
      commit("authUser", {
        api_token: api_token,
        code_role: code_role,
        user_id: user_id,
      });
    },
  },
  getters: {
    ifCustomerAuthenticated(state) {
      return state.api_token !== null && state.code_role == CUSTOMER_CODE_ROLE;
    },
    ifAdministratorAuthenticated(state) {
      return state.api_token !== null && state.code_role == ADMIN_CODE_ROLE;
    },
    ifAuthenticated(state) {
      return state.api_token !== null;
    },
  },
});
