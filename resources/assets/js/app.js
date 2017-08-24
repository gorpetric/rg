import store from './vuex'

require('./bootstrap')

require('./stuff')

window.Vue = require('vue')

require('./components')

const app = new Vue({
    el: '#app',
    store: store
});
