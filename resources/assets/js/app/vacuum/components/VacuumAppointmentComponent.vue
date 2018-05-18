<template>
    <div class='appointment'>
        <template v-if='!editing_appointment'>
            {{ appointment.appointment_at | momentt }}
            &nbsp;<i class='fas fa-check' v-show='appointment.finished'></i><br>
            <template v-if='!appointment.finished'>
                <a href='#' @click.prevent='toggledEditAppointment'>Promjena termina</a><br>
                <a href='#' @click.prevent='completeAppointment(true)'>Označi termin kao završen</a>
            </template>
            <a href='#' v-else @click.prevent='completeAppointment(false)'>Označi termin kao nezavršen</a>
        </template>
        <template v-else>
            <div class='form-group'>
                <input type='date' v-model='edit_values.date'>
                <input type='time' v-model='edit_values.time'>
                <span class='error-block' v-if='edit_values.errors.has("date")'>{{ edit_values.errors.get('date') }}</span>
                <span class='error-block' v-if='edit_values.errors.has("time")'>{{ edit_values.errors.get('time') }}</span>
            </div>
            <p>
                <button class='form-btn' :disabled='loading' @click='editAppointment'>Promijeni</button>
                <a href='#' @click.prevent='editing_appointment = false'>Odustani</a>
            </p>
        </template>
        <hr>
        <div class='measure'>
            <div class='table-responsive'>
                <table style='width:100%'>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Prije</th>
                            <th>Nakon</th>
                            <th v-if='!appointment.finished'></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for='part in uniqueParts'>
                            <td>{{ part.part }} ({{ part.unit }})</td>
                            <td>{{ getBeforeMeasure(part) }}</td>
                            <td>{{ getAfterMeasure(part) }}</td>
                            <td v-if='!appointment.finished'><a href='#' @click.prevent='deleteMeasurement(part)'>Obriši</a></td>
                        </tr>
                        <tr v-if='creating_new_measurement'>
                            <td>
                                <select v-model='new_measurement.part'>
                                    <option disabled value=0>Odaberi...</option>
                                    <option v-for='part in leftOutParts' :value='part.id'>{{ part.part }} ({{ part.unit }})</option>
                                </select>
                            </td>
                            <td>
                                <input type='number' step='0.01' :pattern='pattern' v-model='new_measurement.before'>
                            </td>
                            <td>
                                <input type='number' step='0.01' :pattern='pattern' v-model='new_measurement.after'>
                            </td>
                            <td>
                                <a href='#' @click.prevent='createMeasurement'>Spremi</a>&nbsp;|&nbsp;
                                <a href='#' @click.prevent='creating_new_measurement = false'>Odustani</a>
                            </td>
                        </tr>
                        <tr v-if='leftOutParts.length && !creating_new_measurement && !appointment.finished'>
                            <td colspan=4><a href='#' @click.prevent='creating_new_measurement = true'>Novo mjerenje</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import Form from '../../../Form'

    export default {
        props: ['member', 'appointmentprop', 'parts'],
        data() {
            return {
                appointment: this.appointmentprop,
                loading: false,
                pattern: '^\d*(\.\d{0,2})?$',
                creating_new_measurement: false,
                new_measurement: new Form({
                    part: 0,
                    before: 0,
                    after: 0
                }),
                editing_appointment: false,
                edit_values: new Form({
                    date: null,
                    time: null
                })
            }
        },
        computed: {
            uniqueParts() {
                let uids = [...new Set(this.appointment.vacuum_appointment_measures.map(m => m.vacuum_measure_body_part_id))]
                let parts = []
                this.parts.forEach(part => {
                    if(uids.includes(part.id) && !parts.includes(part.id)) parts.push(part)
                })
                return parts
            },
            leftOutParts() {
                let uniqueParts = this.uniqueParts.map(p => p.id)
                return this.parts.filter(p => !uniqueParts.includes(p.id))
            }
        },
        methods: {
            getBefore(part) {
                return this.appointment.vacuum_appointment_measures.find(m => m.vacuum_measure_body_part_id == part.id && m.before_or_after == 'B')
            },
            getAfter(part) {
                return this.appointment.vacuum_appointment_measures.find(m => m.vacuum_measure_body_part_id == part.id && m.before_or_after == 'A')
            },
            getBeforeMeasure(part) {
                let m = this.getBefore(part)
                return m ? m.measure : 0
            },
            getAfterMeasure(part) {
                let m = this.getAfter(part)
                return m ? m.measure : 0
            },
            createMeasurement() {
                let ids = this.leftOutParts.map(p => p.id)
                if(!ids.includes(this.new_measurement.part)) return

                let part = this.parts.find(p => p.id == this.new_measurement.part)

                this.loading = true

                this.new_measurement.post('/members/'+this.member+'/vacuum/'+this.appointment.vacuum_appointment_group_id+'/'+this.appointment.id).then(response => {
                    this.appointment.vacuum_appointment_measures.push(response.before)
                    this.appointment.vacuum_appointment_measures.push(response.after)
                    this.new_measurement.part = 0
                    this.creating_new_measurement = false
                    this.loading = false
                }).catch(e => {
                    this.loading = false
                    alert('Something went wrong')
                })
            },
            deleteMeasurement(part) {
                if(!confirm('Sigurno?')) return

                let b = this.getBefore(part)
                let a = this.getAfter(part)

                let ids = []

                if(b) ids.push(b.id)
                if(a) ids.push(a.id)

                axios.post('/members/'+this.member+'/vacuum/'+this.appointment.vacuum_appointment_group_id+'/'+this.appointment.id+'/delete-measure', {
                    ids: ids
                }).then(response => {
                    let bi = this.appointment.vacuum_appointment_measures.indexOf(b)
                    if(bi > -1) this.appointment.vacuum_appointment_measures.splice(bi, 1)
                    let ai = this.appointment.vacuum_appointment_measures.indexOf(a)
                    if(ai > -1) this.appointment.vacuum_appointment_measures.splice(ai, 1)
                }).catch(e => alert('Something went wrong'))
            },
            completeAppointment(fin = true) {
                if(!confirm('Sigurno?')) return

                axios.post('/members/'+this.member+'/vacuum/'+this.appointment.vacuum_appointment_group_id+'/'+this.appointment.id+'/complete-appointment', {
                    finished: fin
                }).then(response => {
                    this.appointment.finished = fin ? 1 : 0
                }).catch(e => alert('Something went wrong'))
            },
            toggledEditAppointment() {
                let x = this.appointment.appointment_at.split(' ')
                this.edit_values.date = x[0]
                this.edit_values.time = x[1]
                this.editing_appointment = true
            },
            editAppointment() {
                this.loading = true

                let date = this.edit_values.date
                let time = this.edit_values.time

                this.edit_values.post('/members/'+this.member+'/vacuum/'+this.appointment.vacuum_appointment_group_id+'/'+this.appointment.id+'/edit-appointment').then(response => {
                    this.appointment.appointment_at = date + ' ' + time
                    this.editing_appointment = false
                    this.loading = false
                }).catch(e => this.loading = false)
            }
        }
    }
</script>

<style scoped>
.appointment {
    padding: 20px;
    border-bottom: 1px solid black;
}
.appointment:last-child {
    border-bottom: none;
}
</style>
