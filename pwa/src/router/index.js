import Vue from "vue";
import Router from "vue-router";
import store from "../store";
import Home from "./../components/Home.vue";
import About from "./../components/About.vue";
import Calendar from "./../components/Calendar.vue";
import PrivacyPolicy from "./../components/PrivacyPolicy.vue";
import Connection from "./../components/Connection.vue";
import Inscription from "./../components/Inscription.vue";
import CustomerInformation from "./../components/CustomerInformation.vue";
import CustomerAppoitment from "./../components/CustomerAppoitment.vue";
import Administration from "./../components/Administration.vue";
import Planning from "./../components/Planning.vue";
import { CUSTOMER_CODE_ROLE, ADMIN_CODE_ROLE } from "./../variable.js";

Vue.use(Router);

export default new Router({
  routes: [
    { path: "/", component: Home },
    { path: "/about", component: About },
    { path: "/calendar", component: Calendar },
    { path: "/privacy_policy", component: PrivacyPolicy },
    {
      path: "/connection",
      component: Connection,
      beforeEnter(to, from, next) {
        if (
          store.state.api_token &&
          store.state.code_role == CUSTOMER_CODE_ROLE
        ) {
          next("/customer_information");
        } else {
          next();
        }
        if (store.state.api_token && store.state.code_role == ADMIN_CODE_ROLE) {
          next("/administration");
        } else {
          next();
        }
      },
    },
    {
      path: "/inscription",
      component: Inscription,
      beforeEnter(to, from, next) {
        if (
          store.state.api_token &&
          store.state.code_role == CUSTOMER_CODE_ROLE
        ) {
          next("/customer_information");
        } else {
          next();
        }
        if (store.state.api_token && store.state.code_role == ADMIN_CODE_ROLE) {
          next("/administration");
        } else {
          next();
        }
      },
    },
    {
      path: "/customer_information/:userId?",
      name: "customerInformation",
      component: CustomerInformation,
      beforeEnter(to, from, next) {
        if (store.state.api_token) {
          next();
        } else {
          next("/");
        }
      },
    },
    {
      path: "/customer_appoitment/:userId?",
      name: "customerAppoitment",
      component: CustomerAppoitment,
      beforeEnter(to, from, next) {
        if (store.state.api_token) {
          next();
        } else {
          next("/");
        }
      },
    },
    {
      path: "/administration",
      name: "administration",
      component: Administration,
      beforeEnter(to, from, next) {
        if (store.state.api_token && store.state.code_role == ADMIN_CODE_ROLE) {
          next();
        } else {
          next("/");
        }
      },
    },
    {
      path: "/planning",
      name: "planning",
      component: Planning,
      beforeEnter(to, from, next) {
        if (store.state.api_token && store.state.code_role == ADMIN_CODE_ROLE) {
          next();
        } else {
          next("/");
        }
      },
    },
  ],
});
