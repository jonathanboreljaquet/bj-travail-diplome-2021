import Vue from "vue";
import Router from "vue-router";
import store from "./store";
import Home from "./components/Home.vue";
import About from "./components/About.vue";
import Calendar from "./components/Calendar.vue";
import PrivacyPolicy from "./components/PrivacyPolicy.vue";
import Connection from "./components/Connection.vue";
import Inscription from "./components/Inscription.vue";
import CustomerInformation from "./components/CustomerInformation.vue";
import CustomerAppointment from "./components/CustomerAppointment.vue";
import Administration from "./components/Administration.vue";
import Planning from "./components/Planning.vue";
import EducatorCalendar from "./components/EducatorCalendar.vue";
import { CUSTOMER_CODE_ROLE, ADMIN_CODE_ROLE } from "./constants.js";

Vue.use(Router);

export default new Router({
  routes: [
    { path: "/", name: "home", component: Home },
    { path: "/about", name: "about", component: About },
    { path: "/calendar", name: "calendar", component: Calendar },
    {
      path: "/privacy_policy",
      name: "privacyPolicy",
      component: PrivacyPolicy,
    },
    {
      path: "/connection",
      name: "connection",
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
      name: "inscription",
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
      path: "/customer_appointment/:userId?",
      name: "customerAppointment",
      component: CustomerAppointment,
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
    {
      path: "/educator_calendar",
      name: "educatorCalendar",
      component: EducatorCalendar,
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
