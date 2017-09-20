<template>
    <div class='member-block' :class='determineColor()'>
        <div class='main' @click.prevent='toggleInfo'>
            {{ member.name }}
            <small v-if='member.active'>( {{ getLatestValidUntil() | moment }} ) ( {{ getDaysDifference() }} )</small>
        </div>
        <div class='info' :class='showInfo()'>
            Adresa: <strong v-if='member.address'>{{ member.address }}</strong><i v-else><small>nije upisano</small></i><br>
            Kontakt broj: <strong v-if='member.phone'>{{ member.phone }}</strong><i v-else><small>nije upisano</small></i><br>
            Datum učlanjenja: <strong>{{ member.joined_at | moment }}</strong><br>
            <a :href='"/members/"+member.id+"/edit"' @click.prevent='editMemberShowing = 1'>Uredi</a>
            <br><br>
            <a href='#' @click.prevent='paymentsShowing = 1'>Prikaži plaćanja</a>
        </div>
        <modal v-if='paymentsShowing' @close='paymentsShowing = 0'>
            <span slot='header'>{{ member.name }} - plaćanja ( {{ getDaysDifference() }} )</span>
            <div slot='body'>
                <div v-if='!toggleNewPayment' style='margin-bottom:10px'><a href='#' @click.prevent='toggleNewPayment = 1'>Novo plaćanje</a><hr></div>
                <div v-else style='margin-bottom:10px'>
                    *Cijena <input type='text' style='width:auto' v-model='newPaymentInputs.value' maxlength='3'><br><br>
                    *Vrijedi od <input type='date' v-model='newPaymentInputs.valid_from'><br><br>
                    *Vrijedi do <input type='date' v-model='newPaymentInputs.valid_until'><br><br>
                    Napomena <input type='text' v-model='newPaymentInputs.description' placeholder='Opcionalno...'><br><br>
                    <a href='#' @click.prevent='toggleNewPayment = 0'>Odustani</a>&nbsp;
                    <button class='form-btn' @click.prevent='addNewPayment()'>Dodaj</button>
                    <hr>
                </div>
                <p>Ukupno: {{ getPaymentsTotal() }} kn</p>
                <div v-for='payment in member.payments' class='memberPayment'>
                    Cijena: <strong>{{ payment.value }}</strong><br>
                    Vrijedi od: <strong>{{ payment.valid_from | moment }}</strong><br>
                    Vrijedi do: <strong>{{ payment.valid_until | moment }}</strong><br>
                    <span v-if=payment.description>Napomena: <strong>{{ payment.description }}</strong></span>
                </div>
            </div>
        </modal>
        <modal v-if='editMemberShowing' @close='editMemberShowing = 0'>
            <span slot='header'>Uredi član: {{ member.name }}</span>
            <div slot='body'>
                <new-or-edit-member :member='member'></new-or-edit-member>
            </div>
        </modal>
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
                editMemberShowing: 0,
                toggleNewPayment: 0,
                newPaymentInputs: {
                    value: 100,
                    valid_from: null,
                    valid_until: null,
                    description: null
                }
            }
        },
        props: ['member'],
        computed: {
            ...mapGetters({
                membership_monthly: 'members/membership_monthly',
                membership_daily: 'members/membership_daily'
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
                return moment(this.member.payments[0].valid_until).startOf('day')
            },
            getPaymentsTotal() {
                let total = 0
                this.member.payments.forEach((payment) => {
                    total += +payment.value
                })
                return total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
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
            showInfo() {
                if(!this.toggled) return ''
                return 'info-showing'
            },
            setUpNewPaymentInputs() {
                this.newPaymentInputs.value = this.member.active ? this.membership_monthly : this.membership_daily
                this.newPaymentInputs.valid_from = this.getLatestValidUntil().format('YYYY-MM-DD')
                this.newPaymentInputs.valid_until = this.getLatestValidUntil().add(1, 'M').format('YYYY-MM-DD')
                this.newPaymentInputs.description = null
            },
            addNewPayment() {
                axios.post('/members/'+this.member.id+'/payments', {
                    value: this.newPaymentInputs.value,
                    valid_from: this.newPaymentInputs.valid_from,
                    valid_until: this.newPaymentInputs.valid_until,
                    description: this.newPaymentInputs.description
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
    border: 2px solid black;
    margin: 10px 0;
}
.main {
    padding: 10px;
    cursor: pointer;
}
.info {
    font-size: 16px;
    padding: 10px;
    border-top: 1px solid lightgray;
    display: none;
}
.info-showing {
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
.memberPayment{
    padding: 10px 0;
}
.memberPayment:nth-child(odd) {
    background: #f5f5f5;
}
</style>
