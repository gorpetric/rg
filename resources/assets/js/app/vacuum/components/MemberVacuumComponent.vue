<template>
    <div class='section container content'>
        <h3 class='title is-4'>{{ member.name }} <small>- Vacuum</small></h3>
        <p><button :disabled='!canCreateNewGroup()' @click='creating_new_group = true' class='button'>Nova grupa termina</button></p>

        <div class='new_group' v-if='creating_new_group'>
            <div class='field has-addons'>
                <p class='control has-icons-left'>
                    <input type='number' class='input' v-model='new_values.num_of_appointments' placeholder='Broj termina'>
                    <span class='icon is-left'><i class='fas fa-sort-numeric-up'></i></span>
                </p>
                <p class='control has-icons-left'>
                    <input type='number' class='input' v-model='new_values.price_per_appointment' placeholder='Cijena po terminu'>
                    <span class='icon is-left'><i class='fas fa-dollar-sign'></i></span>
                </p>
                <p class='control'>
                    <button class='button' :disabled='loading' @click='createNewGroup'>Kreiraj</button>
                </p>
            </div>
            <a href='#' @click.prevent='creating_new_group = false'>Odustani</a>

            <span class='help is-danger' v-if='new_values.errors.has("num_of_appointments")'>{{ new_values.errors.get('num_of_appointments') }}</span>
            <span class='help is-danger' v-if='new_values.errors.has("price_per_appointment")'>{{ new_values.errors.get('price_per_appointment') }}</span>
        </div>

        <vacuum-appointment-group v-for='group in member.vacuum_appointment_groups' :key='group.id' :member='member.id' :parts='parts' :groupprop='group'></vacuum-appointment-group>
    </div>
</template>

<script>
    import Form from '../../../Form'

    export default {
        props: ['memberprop', 'parts'],
        data() {
            return {
                member: this.memberprop,
                loading: false,
                new_values: new Form({
                    num_of_appointments: null,
                    price_per_appointment: null
                }),
                creating_new_group: false
            }
        },
        methods: {
            finishedAppointments(group) {
                return group.vacuum_appointments.filter(a => a.finished == 1).length
            },
            canCreateNewGroup() {
                if(!this.member.vacuum_appointment_groups.length) return true
                return this.finishedAppointments(this.member.vacuum_appointment_groups[0]) == this.member.vacuum_appointment_groups[0].num_of_appointments && !this.creating_new
            },
            createNewGroup() {
                this.loading = true

                this.new_values.post('/members/vacuum/'+this.member.id).then(response => {
                    this.member.vacuum_appointment_groups.unshift(response.group)
                    this.creating_new_group = false
                    this.loading = false
                }).catch(e => this.loading = false)
            }
        }
    }
</script>
