<template>
    <section id='members'>
        <p><a href='/clanovi/novi'>Novi član</a></p>
        <input type='text' v-model='searchQuery' placeholder='Pretraži po imenu'>
        <div class='active'>
            <h2>Aktivni</h2>
            <div v-for='member in searchedActive' class='member-block' :class='determineColor(member)'>
                <div class='main' @click='toggleInfo(member.id)'>
                    {{ member.name }}
                    <small>( {{ getLatestValidUntil(member) | moment }} ) ( {{ getDaysDifference(member) }} )</small>
                </div>
                <div class='info' :class='showInfo(member.id)'>
                    Adresa: <strong v-if='member.address'>{{ member.address }}</strong><i v-else><small>nije upisano</small></i><br>
                    Kontakt broj: <strong v-if='member.phone'>{{ member.phone }}</strong><i v-else><small>nije upisano</small></i><br>
                    Datum učlanjenja: <strong>{{ member.joined_at | moment }}</strong><br>
                    <a :href='"/clanovi/"+member.id+"/uredi"'>Uredi</a>
                    <br><br>
                    <a href='#' @click.prevent='togglePayments(member.id)'>Prikaži plaćanja</a>
                </div>
                <div class='payments' :class='showPayments(member.id)'>
                    <div v-if='newPaymentIDs.indexOf(member.id) < 0'><a href='#' @click.prevent='newPaymentIDs.push(member.id)'>Novo</a></div>
                    <div v-else>
                        Cijena <input type='text' v-model='newPaymentInputs[member.id].value' maxlength='3'><br>
                        Vrijedi od <input type='date' v-model='newPaymentInputs[member.id].valid_from'><br>
                        Vrijedi do <input type='date' v-model='newPaymentInputs[member.id].valid_until'><br>
                        <a href='#' @click.prevent='newPaymentIDs.splice(newPaymentIDs.indexOf(member.id), 1)'>Odustani</a>
                        <button @click.prevent='addNewPayment(member)'>Dodaj</button>
                    </div>
                    <table>
                        <tr>
                            <th>Cijena</th>
                            <th>Vrijedi od</th>
                            <th>Vrijedi do</th>
                        </tr>
                        <tr v-for='payment in member.payments'>
                            <td>{{ payment.value }}</td>
                            <td>{{ payment.valid_from | moment }}</td>
                            <td>{{ payment.valid_until | moment }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class='inactive'>
            <h2>Neaktivni</h2>
            <div v-for='member in searchedInactive' class='member-block inactive'>
                <div class='main' @click='toggleInfo(member.id)'>
                    {{ member.name }}
                </div>
                <div class='info' :class='showInfo(member.id)'>
                    Adresa: <strong v-if='member.address'>{{ member.address }}</strong><i v-else><small>nije upisano</small></i><br>
                    Kontakt broj: <strong v-if='member.phone'>{{ member.phone }}</strong><i v-else><small>nije upisano</small></i><br>
                    Datum učlanjenja: <strong>{{ member.joined_at | moment }}</strong><br>
                    <a :href='"/clanovi/"+member.id+"/uredi"'>Uredi</a>
                    <br><br>
                    <a href='#' @click.prevent='togglePayments(member.id)'>Prikaži plaćanja</a>
                </div>
                <div class='payments' :class='showPayments(member.id)'>
                    <table>
                        <tr>
                            <th>Cijena</th>
                            <th>Vrijedi od</th>
                            <th>Vrijedi do</th>
                        </tr>
                        <tr v-for='payment in member.payments'>
                            <td>{{ payment.value }}</td>
                            <td>{{ payment.valid_from | moment }}</td>
                            <td>{{ payment.valid_until | moment }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import moment from 'moment'

    export default {
        data() {
            return {
                membership_monthly: this.data.meta.membership_monthly,
                membership_daily: this.data.meta.membership_daily,
                members: {
                    active: this.data.members.active,
                    inactive: this.data.members.inactive
                },
                searchQuery: '',
                toggleIDs: [],
                paymentsIDs: [],
                newPaymentIDs: [],
                newPaymentInputs: []
            }
        },
        props: ['data'],
        computed: {
            sortedActive() {
                return this.members.active.sort((a, b) => {
                    let dda = this.getDaysDifference(a)
                    let ddb = this.getDaysDifference(b)
                    return dda - ddb
                })
            },
            sortedInactive() {
                return this.members.inactive.sort((a, b) => {
                    if(a.name < b.name) return -1
                    if(a.name > b.name) return 1
                    return 0
                })
            },
            searchedActive() {
                let searchRegex = new RegExp(this.searchQuery, 'i')
                if(this.searchQuery == '') {
                    return this.sortedActive
                }
                return this.sortedActive.filter((member) => {
                    return searchRegex.test(member.name)
                })
            },
            searchedInactive() {
                let searchRegex = new RegExp(this.searchQuery, 'i')
                if(this.searchQuery == '') {
                    return this.sortedInactive
                }
                return this.sortedInactive.filter((member) => {
                    return searchRegex.test(member.name)
                })
            }
        },
        methods: {
            determineColor(member) {
                let diff = this.getDaysDifference(member)

                if(diff > 5) return 'ok'
                else if(diff > 0 && diff <= 5) return 'warning'
                else return 'danger'
            },
            getDaysDifference(member) {
                let date = this.getLatestValidUntil(member)
                return date.diff(moment().startOf('day'), 'days')
            },
            getLatestValidUntil(member) {
                if(!member.payments.length) return moment(member.joined_at).startOf('day')
                return moment(member.payments[0].valid_until)
            },
            toggleInfo(id) {
                if(this.toggleIDs.indexOf(id) > -1) {
                    this.toggleIDs.splice(this.toggleIDs.indexOf(id), 1)
                    this.paymentsIDs.splice(this.paymentsIDs.indexOf(id), 1)
                    this.newPaymentIDs.splice(this.newPaymentIDs.indexOf(id), 1)
                    return
                }
                this.toggleIDs.push(id)
            },
            showInfo(id) {
                if(this.toggleIDs.indexOf(id) < 0) return ''
                else return 'info-showing'
            },
            togglePayments(id) {
                if(this.paymentsIDs.indexOf(id) > -1) {
                    this.paymentsIDs.splice(this.paymentsIDs.indexOf(id), 1)
                    this.newPaymentIDs.splice(this.newPaymentIDs.indexOf(id), 1)
                    return
                }
                this.paymentsIDs.push(id)
            },
            showPayments(id) {
                if(this.paymentsIDs.indexOf(id) < 0) return ''
                else return 'payments-showing'
            },
            setUpInputModelsForEachMember(members) {
                for(let i = 0; i < (members.length - 1); i++) {
                    this.newPaymentInputs[members[i].id] = {
                        value: this.membership_monthly,
                        valid_from: this.getLatestValidUntil(members[i]).format('YYYY-MM-DD'),
                        valid_until: this.getLatestValidUntil(members[i]).add(1, 'M').format('YYYY-MM-DD')
                    }
                }
            },
            addNewPayment(member) {
                axios.post('/clanovi/'+member.id+'/placanja', {
                    value: this.newPaymentInputs[member.id].value,
                    valid_from: this.newPaymentInputs[member.id].valid_from,
                    valid_until: this.newPaymentInputs[member.id].valid_until
                }).then((response) => {
                    member.payments.unshift(response.data.data)
                    this.newPaymentIDs.splice(this.newPaymentIDs.indexOf(member.id), 1)
                    this.newPaymentInputs[member.id].valid_from = this.getLatestValidUntil(member).format('YYYY-MM-DD')
                    this.newPaymentInputs[member.id].valid_until = this.getLatestValidUntil(member).add(1, 'M').format('YYYY-MM-DD')
                }).catch((err) => {
                    alert('Something went wrong! Try again.')
                })
            }
        },
        filters: {
            moment(val) {
                return moment(val).format('DD.MM.YYYY.')
            }
        },
        mounted() {
            this.setUpInputModelsForEachMember(this.members.active)
            this.setUpInputModelsForEachMember(this.members.inactive)
        }
    }
</script>

<style scoped>
.member-block {
    border: 1px solid black;
    margin: 10px 0;
}
.main {
    padding: 10px;
    cursor: pointer;
}
.info, .payments {
    font-size: 16px;
    padding: 10px;
    border-top: 1px solid lightgray;
    display: none;
}
.info-showing, .payments-showing {
    display: block
}
.danger {
    border-color: red;
}
.warning {
    border-color: orange;
}
.ok {
    border-color: green;
}
.inactive {
    border-color: gray;
}
input[type='text'] {
    max-width: 100%;
    padding: 5px;
}
</style>
