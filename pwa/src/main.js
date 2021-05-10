import "@babel/polyfill";
import "mutationobserver-shim";
import "./plugins/bootstrap-vue";

import Vue from 'vue'
import App from './App.vue'
import router from './router'

Vue.config.productionTip = false

new Vue({
  router, // we tell our vue instance to use this vue router
  render: h => h(App),
}).$mount('#app')
