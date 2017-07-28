import Vue from 'vue'
import Vuex from 'vuex'
import members from '../app/members/vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        members: members
    }
})
