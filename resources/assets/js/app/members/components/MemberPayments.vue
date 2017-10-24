<template>
    <div>
        <div v-if='!toggleNewPayment' style='margin-bottom:10px'><a href='#' @click.prevent='toggleNewPayment = 1'>Novo plaćanje</a><hr></div>
        <div v-else style='margin-bottom:10px'>
            *Cijena <input type='text' style='width:auto' v-model='form.value' maxlength='3'><br><br>
            <span class='error-block' v-if='form.errors.has("value")'>{{ form.errors.get('value') }}</span>
            *Vrijedi od <input type='date' v-model='form.valid_from'><br><br>
            <span class='error-block' v-if='form.errors.has("valid_from")'>{{ form.errors.get('valid_from') }}</span>
            *Vrijedi do <input type='date' v-model='form.valid_until'><br><br>
            <span class='error-block' v-if='form.errors.has("valid_until")'>{{ form.errors.get('valid_until') }}</span>
            Napomena <input type='text' v-model='form.description' placeholder='Opcionalno...'><br><br>
            <span class='error-block' v-if='form.errors.has("description")'>{{ form.errors.get('description') }}</span>
            <a href='#' @click.prevent='toggleNewPayment = 0'>Odustani</a>&nbsp;
            <button class='form-btn' @click.prevent='addNewPayment()'>Dodaj</button>
            <hr>
        </div>
        <p>Ukupno: {{ getPaymentsTotal() | money }} kn</p>
        <div v-for='payment in member.payments' class='memberPayment'>
            Cijena: <strong>{{ payment.value }}</strong><br>
            Vrijedi od: <strong>{{ payment.valid_from | moment }}</strong><br>
            Vrijedi do: <strong>{{ payment.valid_until | moment }}</strong><br>
            <span v-if=payment.description>Napomena: <strong>{{ payment.description }}</strong></span>
        </div>
    </div>
</template>

<script>
    import Form from '../../../Form'
    import moment from 'moment'
    import { mapGetters } from 'vuex'
    import { getLatestValidUntil } from '../helpers'

    export default {
        props: ['member'],
        data() {
            return {
                toggleNewPayment: 0,
                form: new Form({
                    value: 100,
                    valid_from: null,
                    valid_until: null,
                    description: null
                })
            }
        },
        computed: {
            ...mapGetters({
                membership_monthly: 'members/membership_monthly',
                membership_daily: 'members/membership_daily'
            })
        },
        methods: {
            getLatestValidUntil() {
                return getLatestValidUntil(this.member)
            },
            getPaymentsTotal() {
                let total = 0
                this.member.payments.forEach((payment) => {
                    total += +payment.value
                })
                return total
            },
            setUpNewPaymentInputs() {
                this.form.description = null

                if(this.member.active) {
                    this.form.value = this.membership_monthly
                    this.form.valid_from = this.getLatestValidUntil().format('YYYY-MM-DD')
                    this.form.valid_until = this.getLatestValidUntil().add(1, 'M').format('YYYY-MM-DD')
                    return
                }

                this.form.value = this.membership_daily
                this.form.valid_from = moment().startOf('day').format('YYYY-MM-DD')
                this.form.valid_until = moment().startOf('day').format('YYYY-MM-DD')
            },
            addNewPayment() {
                this.form.post('/members/'+this.member.id+'/payments').then((response) => {
                    this.member.payments.unshift(response.data)
                    this.toggleNewPayment = 0
                    this.setUpNewPaymentInputs()
                })
            }
        },
        mounted() {
            this.setUpNewPaymentInputs()
        }
    }
</script>

<style scoped>
.memberPayment{
    padding: 10px 0;
}
.memberPayment:nth-child(odd) {
    background: #f5f5f5;
}
</style>
