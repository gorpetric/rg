require('./bootstrap');

window.Vue = require('vue');

Vue.component('members', require('./components/Members.vue'));

const app = new Vue({
    el: '#app'
});
