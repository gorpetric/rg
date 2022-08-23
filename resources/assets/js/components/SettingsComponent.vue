<template>
    <div class='section container content'>
        <h1 class='title is-3'>Settings</h1>

        <form autocomplete='off' @submit.prevent='createSett'>
            <div class='field has-addons'>
                <p class='control is-expanded'>
                    <input type='text' class='input' v-model='newSett.key' placeholder='key'>
                </p>
                <p class='control is-expanded'>
                    <input type='text' class='input' v-model='newSett.value' placeholder='value'>
                </p>
                <p class='control'>
                    <input type='submit' value='Create' class='button' :disabled='loading'>
                </p>
            </div>
            <span class='help is-danger' v-if='newSett.errors.has("key")'>{{ newSett.errors.get('key') }}</span>
            <span class='help is-danger' v-if='newSett.errors.has("value")'>{{ newSett.errors.get('value') }}</span>
        </form>

        <hr />

        <p><input type='text' class='input' v-model='searchQuery' placeholder='Quick search'></p>

        <div class='table-container'>
            <table class='table is-bordered is-striped is-hoverable is-fullwidth'>
                <thead>
                    <tr>
                        <th scope="col" v-for='column in columns' @click='sortBy(column)' style='cursor: pointer'>
                            {{ column }}
                            <i v-if='sort.key == column' class='fa' :class="{ 'fa-arrow-up': sort.order == 'asc', 'fa-arrow-down': sort.order == 'desc' }"></i>
                        </th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for='s in searchedSettings' :key='s.id'>
                        <td>{{ s.id }}</td>
                        <td>{{ s.key }}</td>
                        <td><input type='text' class='input' v-model='s.value' /></td>
                        <td>{{ s.updated_at }}</td>
                        <td>
                            <button class='button' :disabled='buttonDisabled(s)' @click='updateSetting(s)'>Update</button>
                            <button class='button is-danger' :disabled='loading' @click='deleteSetting(s)'>Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import Form from '../Form'

    export default {
        props: ['settingsprop'],
        data() {
            return {
                loading: false,
                searchQuery: '',
                sort: {
                    key: 'id',
                    order: 'asc'
                },
                settingsOrg: [],
                settingsClone: [],
                updSett: new Form({
                    key: '',
                    value: ''
                }),
                newSett: new Form({
                    key: '',
                    value: ''
                })
            }
        },
        computed: {
            columns() {
                return [
                    'id', 'key', 'value', 'updated_at'
                ]
            },
            sortedSettings() {
                if(!this.sort.key) return this.settingsClone
                return _.orderBy(this.settingsClone, (i) => {
                    let value = i[this.sort.key]

                    if (!isNaN(parseFloat(value))) {
                        return parseFloat(value)
                    }

                    return String(i[this.sort.key]).toLowerCase()
                }, this.sort.order)
            },
            searchedSettings() {
                if(this.searchQuery == '') {
                    return this.sortedSettings
                }

                let searchRegex = new RegExp(this.searchQuery, 'i')

                return this.sortedSettings.filter((s) => {
                    return searchRegex.test(s.id) ||
                            searchRegex.test(s.key) ||
                            searchRegex.test(s.value)
                })
            }
        },
        methods: {
            cloneSettingsOrg() {
                this.settingsOrg = []
                this.settingsOrg = _.cloneDeep(this.settingsprop)
            },
            cloneSettings() {
                this.settingsClone = []
                this.settingsClone = _.cloneDeep(this.settingsOrg)
            },
            sortBy(column) {
                this.sort.key = column
                this.sort.order = this.sort.order === 'asc' ? 'desc' : 'asc'
            },
            buttonDisabled(s) {
                return this.loading || this.newValueIsTheSameAsOld(s)
            },
            newValueIsTheSameAsOld(s) {
                let orgSett = this.settingsOrg.find(sett => sett.id == s.id)
                return orgSett.value == s.value
            },
            updateSetting(s) {
                if(this.newValueIsTheSameAsOld(s)) return

                this.loading = true

                this.updSett.reset()
                this.updSett.key = s.key
                this.updSett.value = s.value

                this.updSett.post('settings/'+s.id).then((response) => {
                    this.updateSettingsList(response.setting)

                    this.loading = false
                }).catch(e => this.loading = false)
            },
            updateSettingsList(newsett) {
                this.settingsOrg = _.map(this.settingsOrg, s => s.id == newsett.id ? newsett : s)
                this.cloneSettings()
            },
            createSett() {
                this.loading = true

                this.newSett.post('settings').then((response) => {
                    this.newSett.reset()

                    this.appedNewSett(response.setting)

                    this.loading = false
                }).catch(e => this.loading = false)
            },
            appedNewSett(newsett) {
                this.settingsOrg.unshift(newsett)
                this.settingsClone.unshift(newsett)
            },
            deleteSetting(s) {
                if(confirm('Are you sure?')) {
                    this.loading = true

                    axios.delete('settings/'+s.id+'/delete').then((response) => {
                        this.removeSettFromList(s)

                        this.loading = false
                    }).catch(e => this.loading = false)
                } else return
            },
            removeSettFromList(s) {
                this.settingsOrg = _.filter(this.settingsOrg, sO => sO.id !== s.id)
                this.settingsClone = _.filter(this.settingsClone, sC => sC.id !== s.id)
            }
        },
        mounted() {
            this.cloneSettingsOrg()
            this.cloneSettings()
        }
    }
</script>
