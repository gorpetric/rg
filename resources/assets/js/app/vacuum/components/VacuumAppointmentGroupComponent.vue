<template>
    <div class='group'>
        <div class='group-inner' :class="{ 'bb': group.vacuum_appointments.length }">
            <i class='fas fa-list-ol'></i>&nbsp;{{ finishedAppointments() }} / {{ group.num_of_appointments }}
            <i class='fas fa-money-bill-alt'></i>&nbsp;{{ group.price_per_appointment }}
            <hr v-show='canCreateAppointment()'>
            <a href='#' v-if='canCreateAppointment() && !creating_new' @click.prevent='creating_new = true'>Novi termin</a>
            <template v-if='creating_new'>
                <div class='form-group'>
                    <input type='date' v-model='new_values.date'>
                    <input type='time' v-model='new_values.time'>
                    <span class='error-block' v-if='new_values.errors.has("date")'>{{ new_values.errors.get('date') }}</span>
                    <span class='error-block' v-if='new_values.errors.has("time")'>{{ new_values.errors.get('time') }}</span>
                </div>
                <p>
                    <button class='form-btn' :disabled='loading' @click='createAppointment'>Kreiraj termin</button>
                    <a href='#' @click.prevent='creating_new = false'>Odustani</a>
                </p>
            </template>
        </div>
        <vacuum-appointment v-for='appointment in group.vacuum_appointments' :key='appointment.id' :member='member' :appointmentprop='appointment' :parts='parts'></vacuum-appointment>
    </div>
</template>

<script>
    import Form from '../../../Form'

    export default {
        props: ['member', 'groupprop', 'parts'],
        data() {
            return {
                group: this.groupprop,
                loading: false,
                creating_new: false,
                new_values: new Form({
                    date: null,
                    time: null
                })
            }
        },
        methods: {
            finishedAppointments() {
                return this.group.vacuum_appointments.filter(a => a.finished == 1).length
            },
            canCreateAppointment() {
                return this.group.vacuum_appointments.length < this.group.num_of_appointments
            },
            createAppointment() {
                this.loading = true

                this.new_values.post('/members/vacuum/'+this.member+'/'+this.group.id).then(response => {
                    this.group.vacuum_appointments.unshift(response.appointment)
                    this.creating_new = false
                    this.loading = false
                }).catch(e => this.loading = false)
            }
        }
    }
</script>

<style scoped>
.group {
    border: 1px solid black;
    border-radius: 5px;
    margin-top: 50px;
}
.group-inner {
    padding: 20px;
}
.group-inner.bb {
    border-bottom: 1px solid black;
}
</style>
