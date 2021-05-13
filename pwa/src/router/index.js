import Vue from "vue";
import Router from "vue-router";
import store from "../store";
import Home from "./../components/Home.vue";
import About from "./../components/About.vue";
import Calendar from "./../components/Calendar.vue";
import PrivacyPolicy from "./../components/PrivacyPolicy.vue";
import Connection from "./../components/Connection.vue";
import CustomerInformation from "./../components/CustomerInformation.vue";
Vue.use(Router);
export default new Router({
  routes: [
    { path: "/", component: Home },
    { path: "/about", component: About },
    { path: "/calendar", component: Calendar },
    { path: "/privacy_policy", component: PrivacyPolicy },
    { path: "/connection", component: Connection },
    {
      path: "/customer_information",
      component: CustomerInformation,
      beforeEnter(to, from, next) {
        if (store.state.api_token) {
          next();
        } else {
          next("/connection");
        }
      },
    },
  ],
});
