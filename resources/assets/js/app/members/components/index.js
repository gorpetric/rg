import Vue from 'vue'

//'export const Members = Vue.component('members', require('./Members.vue'))
Vue.component('members', require('./Members.vue'))
Vue.component('member', require('./Member.vue'))
Vue.component('member-payments', require('./MemberPayments.vue'))
Vue.component('new-or-edit-member', require('./NewOrEditMember.vue'))
Vue.component('member-payments-monthly-stats', require('./MemberPaymentsMonthlyStats.vue'))
