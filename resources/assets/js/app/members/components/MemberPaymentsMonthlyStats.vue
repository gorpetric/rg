<template>
    <div>
        <form autocomplete='off' @submit.prevent='submit'>
            <div class='field has-addons'>
                <p class='control'>
                    <span class='select is-fullwidth'>
                        <select v-model='form.month'>
                            <option v-for='month in months'>{{ month }}</option>
                        </select>
                    </span>
                </p>
                <p class='control is-expanded'>
                    <input type='text' class='input' v-model='form.year' maxlength='4' size='4'>
                </p>
                <p class='control'>
                    <input type='submit' value='Pregled' class='button'>
                </p>
            </div>
            <span class='help is-danger' v-if='form.errors.has("month")'>{{ form.errors.get('month') }}</span>
            <span class='help is-danger' v-if='form.errors.has("year")'>{{ form.errors.get('year') }}</span>
        </form>
        <div class='loader' v-if='loading' style='margin: 10px 0'></div>
        <div v-else>
            <p v-if='!Object.keys(data).length'>Nema zapisa</p>
            <div v-else style='padding: 10px' class='table-container'>
                <p>Ukupno: {{ data.total | money }} kn</p>
                <table class='table is-bordered is-striped is-hoverable is-fullwidth'>
                    <thead>
                        <tr>
                            <th>Datum</th>
                            <th>Cijena</th>
                            <th>Član</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for='payment in data.payments'>
                            <td>{{ payment.valid_from | moment }}</td>
                            <td>{{ payment.value }}</td>
                            <td>{{ payment.member.name }}</td>
                        </tr>
                    </tbody>
                </table>
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
        mounted() {
            this.submit()
        }
    }
</script>
