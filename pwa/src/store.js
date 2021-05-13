import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";
import router from "./router";
Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    api_token: null,
  },
  mutations: {
    authUser(state, userData) {
      state.api_token = userData.api_token;
    },
    clearAuth(state) {
      state.api_token = null;
    },
  },
  actions: {
    signup({ commit }, authData) {
      axios
        .post(
          "https://www.googleapis.com/identitytoolkit/v3/relyingparty/signupNewUser?key=AIzaSyAv71t6_6YOyOdpbkmsvqtE2i68uhL3U1g",
          {
            email: authData.email,
            password: authData.password,
            returnSecureToken: true,
          }
        )
        .then((res) => {
          console.log(res);
          localStorage.setItem("token", res.data.idToken);
          localStorage.setItem("userId", res.data.localId);
          commit("authUser", {
            token: res.data.idToken,
            userId: res.data.localId,
          });
          router.push("/dashboard");
        })
        .catch((error) => console.log(error));
    },
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
          console.log("Bien");
          console.log(res);
          localStorage.setItem("api_token", res.data.api_token);
          commit("authUser", {
            api_token: res.data.api_token,
          });
          router.push("/customer_information");
        })
        .catch((error) => {
          console.log(error);
        });
    },
    logout({ commit }) {
      commit("clearAuth");
      localStorage.removeItem("api_token");
      router.replace("/");
    },
    AutoLogin({ commit }) {
      const api_token = localStorage.getItem("api_token");
      if (!api_token) {
        return;
      }
      commit("authUser", {
        api_token: api_token,
      });
    },
  },
  getters: {
    ifAuthenticated(state) {
      return state.api_token !== null;
    },
  },
});
