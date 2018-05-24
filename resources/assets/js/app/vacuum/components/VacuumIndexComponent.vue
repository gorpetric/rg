<template>
    <div class='container'>
        <h2>Vacuum termini</h2>
        <p><button class='btn' @click='membersModal = true'>Vacuum članovi</button></p>
        <p v-if='!appointments.length'>Nema termina</p>
        <template v-else>
            <p>
                <select v-model='filter'>
                    <option value=0>Nezavršeni</option>
                    <option value=1>Završeni</option>
                    <option value=2>Svi</option>
                </select>
                <select v-model='sort'>
                    <option value=0>Najstariji</option>
                    <option value=1>Najnoviji</option>
                </select>
            </p>
            <p>
                <input type='text' v-model='searchQuery' placeholder='Pretraži po imenu člana ili datumu termina'>
            </p>
            <div class='appointment' v-for='appointment in filteredAppointments' :key='appointment.id'>
                <p><strong>{{ appointment.appointment_at | momentt }}</strong></p>
                <p class='indent'>
                    <i class='fas' :class="{ 'fa-male': appointment.vacuum_appointment_group.vacuum_member.sex == 'M', 'fa-female': appointment.vacuum_appointment_group.vacuum_member.sex == 'F' }"></i> {{ appointment.vacuum_appointment_group.vacuum_member.name }}&nbsp;
                    <i class='fas fa-money-bill-alt'></i> {{ appointment.vacuum_appointment_group.price_per_appointment }}
                </p>
                <p class='indent' v-if='!appointment.finished'>
                    {{ getInfo(appointment) }}
                </p>
                <p class='indent'>
                    <button class='form-btn' @click='goToMemberVacuum(appointment.vacuum_appointment_group.vacuum_member.id, appointment.id)'>Detalji</button>
                </p>
            </div>
        </template>

        <modal v-if='membersModal' @close='membersModal = false'>
            <span slot='header'>Vacuum članovi</span>
            <div slot='body'>
                <vacuum-members :membersprop='members'></vacuum-members>
            </div>
        </modal>
    </div>
</template>

<script>
    import moment from 'moment'

    export default {
        props: ['appointments', 'membersprop'],
        data() {
            return {
                members: this.membersprop,
                filter: 0,
                sort: 0,
                searchQuery: '',
                membersModal: false
            }
        },
        computed: {
            sortedAppointments() {
                switch(Number(this.sort)) {
                    case 0:
                        return _.orderBy(this.appointments, ['appointment_at'], ['asc'])
                    case 1:
                        return _.orderBy(this.appointments, ['appointment_at'], ['desc'])
                    default:
                        return this.appointments
                }
            },
            searchedAppointments() {
                let searchRegex = new RegExp(this.searchQuery, 'i')
                if(this.searchQuery == '') {
                    return this.sortedAppointments
                }
                return this.sortedAppointments.filter(a => {
                    return searchRegex.test(moment(a.appointment_at).format('DD.MM.YYYY. H:mm:ss')) ||
                            searchRegex.test(a.vacuum_appointment_group.vacuum_member.name)
                })
            },
            filteredAppointments() {
                switch(Number(this.filter)) {
                    case 0:
                        return this.searchedAppointments.filter(a => a.finished == 0)
                    case 1:
                        return this.searchedAppointments.filter(a => a.finished == 1)
                    case 2:
                        return this.searchedAppointments
                    default:
                        return this.searchedAppointments
                }
            }
        },
        methods: {
            goToMemberVacuum(id, hash = null) {
                let x = hash ? '#' + hash : ''
                window.location.href = '/members/vacuum/'+id + x
            },
            getInfo(appointment) {
                let now =  moment().format('YYYY-MM-DD H:mm:ss')
                let a = moment(appointment.appointment_at)

                let diff = moment.duration(a.diff(now))

                let d = diff.days()
                let h = diff.hours()
                let m = diff.minutes()

                return d + ' d, ' + h + ' h, ' + m + ' m'
            }
        }
    }
</script>

<style scoped>
.appointment {
    margin: 30px 0;
    border-bottom: 1px solid rgba(0,0,0,.06);
}
.appointment:last-child {
    border: none;
}
.indent {
    text-indent: 10px;
}
</style>
