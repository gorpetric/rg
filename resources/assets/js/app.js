import moment from 'moment'
import store from './vuex'

require('./bootstrap')

require('./stuff')

window.Vue = require('vue')

require('./components')

Vue.filter('moment', (value) => moment(value).format('DD.MM.YYYY.'))
Vue.filter('money', (value) => value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'))

const app = new Vue({
    el: '#app',
    store: store
});
