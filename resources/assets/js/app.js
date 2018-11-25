
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('profile-component', require('./components/ProfileComponent.vue'));
Vue.component('wallet-index-component', require('./components/Wallet/WalletIndexComponent'));
Vue.component('order-index-component', require('./components/Order/OrderIndexComponent'));
Vue.component('location-index-component', require('./components/Location/LocationIndexComponent'));
Vue.component('dashboard-component', require('./components/Dashboard/DashboardComponent'));

import Lightbox from 'vue-pure-lightbox'

window.Vue.use(Lightbox);
window.Vue.use(require('vue-moment'));

const app = new Vue({
    el: '#app',
    data() {
        return {
            apiUrl: 'api/v1',
            currency: 'RSD',
            userId: Laravel.user.id
        }
    },
    methods: {
        getUrlParam(key) {
            console.log(key);
            let url = new URL(window.location.href);
            return url.searchParams.get(key);
        }
    }
});

