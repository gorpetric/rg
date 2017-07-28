import store from './vuex'

require('./bootstrap');

window.Vue = require('vue');

require('./components')
//Vue.component('members', require('./components/Members.vue'));

const app = new Vue({
    el: '#app',
    store: store
});
