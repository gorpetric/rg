<template>
    <div>
        <form autocomplete='off' @submit.prevent='submit' style='border-bottom: 1px solid lightgray; padding: 5px'>
            <label for='month'>Mjesec</label>
            <select id='month' v-model='form.month' style='padding: 10px'>
                <option v-for='month in months'>{{ month }}</option>
            </select>
            <label for='year'>Godina</label>
            <input type='text' v-model='form.year' id='year' style='width: auto' maxlength='4' size='4'>
            <input type='submit' value='Pregled' class='form-btn'>
            <span class='error-block' v-if='form.errors.has("month")'>{{ form.errors.get('month') }}</span>
            <span class='error-block' v-if='form.errors.has("year")'>{{ form.errors.get('year') }}</span>
        </form>
        <i class='fa fa-spinner' v-if='loading'></i>
        <div v-else>
            <p v-if='!Object.keys(data).length'>Nema zapisa</p>
            <div v-else style='padding: 10px'>
                <p>Ukupno: {{ data.total | dotPrice }} kn</p>
                <hr>
                <p v-for='payment in data.payments'>{{ payment.valid_from | moment }} - {{ payment.value }} ({{ payment.member.name }})</p>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment'
    import Form from '../../../Form'

    export default {
        data() {
            return {
                months: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'],
                form: new Form({
                    month: moment().format('MM'),
                    year: moment().format('YYYY')
                }),
                loading: 0,
                data: []
            }
        },
        methods: {
            submit() {
                this.loading = 1
                axios.get('members/stats/monthly?year='+this.form.year+'&month='+this.form.month).then((response) => {
                    this.form.errors.clear()
                    this.loading = 0
                    this.data = response.data.data
                }).catch((errors) => {
                    this.loading = 0
                    this.form.errors.record(errors.response.data.errors)
                })
            }
        },
        filters: {
            moment(val) {
                return moment(val).format('DD.MM.YYYY.')
            },
            dotPrice(val) {
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
            }
        },
        mounted() {
            this.submit()
        }
    }
</script>

<style scoped>
@keyframes spin {
    from { transform: rotate(0deg) }
    to { transform: rotate(360deg) }
}
.fa-spinner {
    animation: spin 1s infinite;
}
</style>
