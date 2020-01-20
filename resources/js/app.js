/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import moment from 'moment'

window.Vue = require('vue');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');
const token = window.localStorage.getItem('token');
const base = window.axios.create({
    baseURL: "http://127.0.0.1:8000/api/v1",
    headers: {
        'Authorization': 'Bearer ' + token,
        "Access-Control-Allow-Origin": "*",
        'Content-Type': 'application/json',
    }
});
base.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Vue.prototype.$http = base

import Toasted from 'vue-toasted';
Vue.use(Toasted)

// Date time picker default settings
import VuePersianDatetimePicker from 'vue-persian-datetime-picker';
Vue.use(VuePersianDatetimePicker, {
    name: 'date-picker',
    props: {
        inputFormat: 'YYYY-MM-DD HH:mm',
        format: 'YYYY-MM-DD HH:mm',
        editable: true,
        inputClass: 'form-control',
        placeholder: 'Select date',
        altFormat: 'YYYY-MM-DD HH:mm',
        color: 'rgb(255, 205, 46)'
    }
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('account-planner', require('./components/account/planner.vue').default);
Vue.component('date-picker', require('vue-persian-datetime-picker'));

window.moment = moment

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
