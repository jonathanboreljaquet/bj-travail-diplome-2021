import "@babel/polyfill";
import "mutationobserver-shim";
import "./plugins/bootstrap-vue";
import "./assets/css/main.css";

import Vue from "vue";
import App from "./App.vue";
import router from "./router";

import VueAlertify from "vue-alertify";
Vue.use(VueAlertify);

import Axios from "axios";
Vue.prototype.$http = Axios;

import store from "./store";

import jquery from "jquery";
Vue.prototype.$jquery = jquery;

Vue.prototype.$API_URL = "https://api-rest-douceur-de-chien.boreljaquet.ch/";

Vue.config.productionTip = false;

new Vue({
  router,
  store,
  render: (h) => h(App),
}).$mount("#app");
