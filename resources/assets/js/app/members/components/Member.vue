<template>
    <div class='member-block' :class='determineColor()'>
        <div class='main' @click.prevent='toggleInfo'>
            {{ member.name }}
            <small v-if='member.active'>( {{ getLatestValidUntil(member) | moment }} ) ( {{ getDaysDifference(member) }} )</small>
        </div>
        <div class='info' :class='showInfo()'>
            Adresa: <strong v-if='member.address'>{{ member.address }}</strong><i v-else><small>nije upisano</small></i><br>
            Kontakt broj: <strong v-if='member.phone'>{{ member.phone }}</strong><i v-else><small>nije upisano</small></i><br>
            Datum učlanjenja: <strong>{{ member.joined_at | moment }}</strong><br>
            <a :href='"/members/"+member.id+"/edit"'>Uredi</a>
            <br><br>
            <a href='#' @click.prevent='togglePayments'>Prikaži plaćanja</a>
        </div>
        <div class='payments' :class='showPayments()'>
            <div v-if='!toggleNewPayment' style='margin-bottom:10px'><a href='#' @click.prevent='toggleNewPayment = 1'>Novo</a></div>
            <div v-else style='margin-bottom:10px'>
                Cijena <input type='text' style='width:auto' v-model='newPaymentInputs.value' maxlength='3'><br><br>
                Vrijedi od <input type='date' v-model='newPaymentInputs.valid_from'><br><br>
                Vrijedi do <input type='date' v-model='newPaymentInputs.valid_until'><br><br>
                <a href='#' @click.prevent='toggleNewPayment = 0'>Odustani</a>&nbsp;
                <button class='form-btn' @click.prevent='addNewPayment()'>Dodaj</button>
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
</template>

<script>
    import moment from 'moment'
    import { mapGetters } from 'vuex'

    export default {
        data() {
            return {
                toggled: 0,
                paymentsShowing: 0,
                toggleNewPayment: 0,
                newPaymentInputs: {
                    value: 100,
                    valid_from: null,
                    valid_until: null
                }
            }
        },
        props: ['member'],
        computed: {
            ...mapGetters({
                membership_monthly: 'members/membership_monthly'
            })
        },
        methods: {
            determineColor() {
                if(!this.member.active) return 'inactive'

                let diff = this.getDaysDifference(this.member)

                if(diff > 5) return 'ok'
                else if(diff > 0 && diff <= 5) return 'warning'
                else return 'danger'
            },
            getDaysDifference() {
                let date = this.getLatestValidUntil()
                return date.diff(moment().startOf('day'), 'days')
            },
            getLatestValidUntil() {
                if(!this.member.payments.length) return moment(this.member.joined_at).startOf('day')
                return moment(this.member.payments[0].valid_until)
            },
            toggleInfo() {
                if(this.toggled) {
                    this.toggled = 0
                    this.paymentsShowing = 0
                    this.toggleNewPayment = 0
                    return
                }
                this.toggled = 1
            },
            togglePayments() {
                if(this.paymentsShowing) {
                    this.toggleNewPayment = 0
                    this.paymentsShowing = 0
                    return
                }
                this.paymentsShowing = 1
            },
            showInfo() {
                if(!this.toggled) return ''
                return 'info-showing'
            },
            showPayments() {
                if(!this.paymentsShowing) return ''
                return 'payments-showing'
            },
            setUpNewPaymentInputs() {
                this.newPaymentInputs.value = this.membership_monthly
                this.newPaymentInputs.valid_from = this.getLatestValidUntil().format('YYYY-MM-DD')
                this.newPaymentInputs.valid_until = this.getLatestValidUntil().add(1, 'M').format('YYYY-MM-DD')
            },
            addNewPayment() {
                axios.post('/members/'+this.member.id+'/payments', {
                    value: this.newPaymentInputs.value,
                    valid_from: this.newPaymentInputs.valid_from,
                    valid_until: this.newPaymentInputs.valid_until
                }).then((response) => {
                    this.member.payments.unshift(response.data.data)
                    this.toggleNewPayment = 0
                    this.setUpNewPaymentInputs()
                }).catch((err) => {
                    console.log(err)
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
            this.setUpNewPaymentInputs()
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
</style>
