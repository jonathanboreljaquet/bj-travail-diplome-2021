/*
  main.js
  
  File allowing to initialize the main root component with all the configuration and the plugins necessary for the good functioning of the application.

  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
*/

import "@babel/polyfill";
import "mutationobserver-shim";

import "./plugins/bootstrap-vue";
import "./plugins/vue-alertify";
import "./plugins/vue-signature-pad";

import "./assets/css/main.css";

import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";

import Axios from "axios";
Vue.prototype.$http = Axios;
Vue.prototype.$API_URL = "https://api-rest-douceur-de-chien.boreljaquet.ch/v1/";

import jquery from "jquery";
Vue.prototype.$jquery = jquery;

new Vue({
  router,
  store,
  render: (h) => h(App),
}).$mount("#app");
