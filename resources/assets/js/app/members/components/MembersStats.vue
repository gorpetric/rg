<template>
    <div class='section container content'>
        <h1 class='title is-3'>Članovi - statistika</h1>

        <div class='control'>
            <label class='radio' v-for='ast in availableStatsTypes'>
                <input type='radio' :value='ast.id' v-model='statsType' @change='getStats()' />
                {{ ast.name }}
            </label>
        </div>

        <hr />

        <form v-if='statsType == "by-month"' autocomplete='off' @submit.prevent='getStats'>
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
            <div v-if='statsType == "by-month"'>
                <p v-if='!Object.keys(data).length'>Nema zapisa</p>
                <div v-else style='padding: 10px' class='table-container'>
                    <p>
                        Ukupno
                        <strong v-for='(pt, ind) in byMonthTotals()'>
                            {{ pt.sum | money }} {{ pt.symbol }}
                            <span v-if='ind !== Object.keys(byMonthTotals()).length - 1'>&nbsp;&&nbsp;</span>
                        </strong>
                    </p>
                    <table class='table is-bordered is-striped is-hoverable is-fullwidth'>
                        <thead>
                            <tr>
                                <th>Datum</th>
                                <th>Cijena</th>
                                <th>Član</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for='payment in data'>
                                <td>{{ payment.valid_from | moment }}</td>
                                <td>{{ payment.value }} {{ payment.currency.symbol }}</td>
                                <td>{{ payment.member.name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-else>
                <table class='table is-bordered is-striped is-hoverable is-fullwidth'>
                    <thead>
                        <tr>
                            <th>Godina</th>
                            <th v-if='statsType == "monthly"'>Mjesec</th>
                            <th>Valuta</th>
                            <th>Ukupno</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for='d in data'>
                            <td>{{ d.onlyyear }}</td>
                            <td v-if='statsType == "monthly"'>{{ d.onlymonth }}</td>
                            <td>{{ d.currency.code }}</td>
                            <td>{{ d.valuesum | money }} {{ d.currency.symbol }}</td>
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
                data: [],
                statsType: 'by-month',
                availableStatsTypes: [
                    {
                        id: 'by-month',
                        name: 'Za određeni mjesec'
                    },
                    {
                        id: 'monthly',
                        name: 'Po mjesecima'
                    },
                    {
                        id: 'yearly',
                        name: 'Po godinama'
                    }
                ]
            }
        },
        methods: {
            byMonthTotals() {
                let totals = []

                this.data.forEach(payment => {
                    let tot = _.find(totals, t => t.id == payment.currency_id)
                    if(!tot) {
                        totals.push({
                            'id': payment.currency_id,
                            'symbol': payment.currency.symbol,
                            'sum': 0
                        })
                    }

                    tot = _.find(totals, t => t.id == payment.currency_id)
                    tot.sum += +payment.value
                })

                return _.sortBy(totals, 'id')
            },
            getStats() {
                this.loading = 1
                this.data = []

                axios.get('stats/get-stats?type='+this.statsType+'&year='+this.form.year+'&month='+this.form.month).then((response) => {
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
            this.getStats()
        }
    }
</script>
