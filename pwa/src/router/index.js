import Vue from 'vue'
import Router from 'vue-router'
import Home from './../components/Home.vue'
import About from './../components/About.vue'
import Calendar from './../components/Calendar.vue'
import PrivacyPolicy from './../components/PrivacyPolicy.vue'


Vue.use(Router)

export default new Router({
    routes: [
        { path: '/', component: Home },
        { path: '/about', component: About },
        { path: '/calendar', component: Calendar },
        { path: '/privacy_policy', component: PrivacyPolicy }
    ]
})