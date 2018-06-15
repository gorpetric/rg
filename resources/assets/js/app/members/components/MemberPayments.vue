<template>
    <div>
        <div v-if='!toggleNewPayment && !deletingPaymentId' style='margin-bottom:10px'><a href='#' @click.prevent='toggleNewPayment = 1'>Novo plaćanje</a><hr></div>
        <div v-if='toggleNewPayment' style='margin-bottom:10px'>
            <div class='field is-horizontal'>
                <div class='field-label'>
                    <label class='label'>* Cijena</label>
                </div>
                <div class='field-body'>
                    <div class='field'>
                        <div class='control'>
                            <input type='text' class='input' v-model='form.value' maxlength='3'>
                        </div>
                        <span class='help is-danger' v-if='form.errors.has("value")'>{{ form.errors.get('value') }}</span>
                    </div>
                </div>
            </div>

            <div class='field is-horizontal'>
                <div class='field-label'>
                    <label class='label'>* Vrijedi od</label>
                </div>
                <div class='field-body'>
                    <div class='field'>
                        <div class='control'>
                            <input type='date' class='input' v-model='form.valid_from'>
                        </div>
                        <span class='help is-danger' v-if='form.errors.has("valid_from")'>{{ form.errors.get('valid_from') }}</span>
                    </div>
                </div>
            </div>

            <div class='field is-horizontal'>
                <div class='field-label'>
                    <label class='label'>* Vrijedi do</label>
                </div>
                <div class='field-body'>
                    <div class='field'>
                        <div class='control'>
                            <input type='date' class='input' v-model='form.valid_until'>
                        </div>
                        <span class='help is-danger' v-if='form.errors.has("valid_until")'>{{ form.errors.get('valid_until') }}</span>
                    </div>
                </div>
            </div>

            <div class='field is-horizontal'>
                <div class='field-label'>
                    <label class='label'>Napomena</label>
                </div>
                <div class='field-body'>
                    <div class='field'>
                        <div class='control'>
                            <input type='text' class='input' v-model='form.description' placeholder='Opcionalno...'>
                        </div>
                        <span class='help is-danger' v-if='form.errors.has("description")'>{{ form.errors.get('description') }}</span>
                    </div>
                </div>
            </div>

            <div class='field is-horizontal'>
                <div class='field-label'></div>
                <div class='field-body'>
                    <div class='field is-grouped'>
                        <p class='control'>
                            <button class='button' :disabled='loading' @click.prevent='addNewPayment()'>Dodaj</button>
                        </p>
                        <p class='control'>
                            <a href='#' @click.prevent='toggleNewPayment = 0'>Odustani</a>
                        </p>
                    </div>
                </div>
            </div>
            <hr>
        </div>
        <p>Ukupno: {{ getPaymentsTotal() | money }} kn</p>

        <div class='table-container'>
            <table class='table is-striped is-hoverable is-fullwidth'>
                <tbody>
                    <tr v-for='(payment, index) in member.payments'>
                        <td>
                            Cijena: <strong>{{ payment.value }}</strong><br>
                            Vrijedi od: <strong>{{ payment.valid_from | moment }}</strong><br>
                            Vrijedi do: <strong>{{ payment.valid_until | moment }}</strong><br>
                            <span v-if=payment.description>Napomena: <strong>{{ payment.description }}</strong></span>
                            <div v-if='index == 0 && !deletingPaymentId && !toggleNewPayment'><a href='#' @click.prevent='deletingPaymentId = payment.id'>Obriši</a></div>
                            <div v-if='index == 0 && deletingPaymentId == payment.id && !toggleNewPayment'>
                                <span>Sigurno?</span>
                                <a href='#' @click.prevent='deletingPaymentId = null'>Odustani</a>
                                <button class='button' @click.prevent='deletePayment(payment)'>Da, obriši</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
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
                loading: false,
                toggleNewPayment: 0,
                deletingPaymentId: null,
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
                this.loading = true

                this.form.post('/members/'+this.member.id+'/payments').then((response) => {
                    this.member.payments.unshift(response.data)
                    this.toggleNewPayment = 0
                    this.setUpNewPaymentInputs()
                    this.loading = false
                }).catch(e => this.loading = false)
            },
            deletePayment(payment) {
                this.deletingPaymentId = payment.id

                axios.delete('/members/'+this.member.id+'/payments/'+payment.id).then((response) => {
                    this.member.payments.shift()
                    this.deletingPaymentId = null
                    this.setUpNewPaymentInputs()
                }).catch((err) => {
                    alert('Something went wrong! Please try again.');
                })
            }
        },
        mounted() {
            this.setUpNewPaymentInputs()
        }
    }
</script>
