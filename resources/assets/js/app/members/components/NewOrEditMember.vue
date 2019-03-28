<template>
    <p v-if='isSuccess' style='color: green'>Uspješno!</p>
    <div v-else>
        <form autocomplete='off' @submit.prevent='submit'>
            <div class='field'>
                <label class='label' for='name'>* Ime</label>
                <div class='control'>
                    <input type='text' class='input' id='name' v-model='form.name' placeholder='Ime'>
                </div>
                <span class='help is-danger' v-if='form.errors.has("name")'>{{ form.errors.get('name') }}</span>
            </div>
            <div class='field'>
                <label class='label' for='address'>Adresa</label>
                <div class='control'>
                    <input type='text' class='input' id='address' v-model='form.address'>
                </div>
                <span class='help is-danger' v-if='form.errors.has("address")'>{{ form.errors.get('address') }}</span>
            </div>
            <div class='field'>
                <label class='label' for='phone'>Kontakt broj</label>
                <div class='control'>
                    <input type='text' class='input' id='phone' v-model='form.phone'>
                </div>
                <span class='help is-danger' v-if='form.errors.has("phone")'>{{ form.errors.get('phone') }}</span>
            </div>
            <div class='field'>
                <label class='label' for='address'>OIB</label>
                <div class='control'>
                    <input type='text' class='input' id='address' v-model='form.oib'>
                </div>
                <span class='help is-danger' v-if='form.errors.has("oib")'>{{ form.errors.get('oib') }}</span>
            </div>
            <div class='field'>
                <div class='control'>
                    <label for='sex-m' class='label'>
                        <input type='radio' id='sex-m' value='M' v-model='form.sex'>
                        Muško
                    </label>
                    <label for='sex-f' class='label'>
                        <input type='radio' id='sex-f' value='F' v-model='form.sex'>
                        Žensko
                    </label>
                </div>
                <span class='help is-danger' v-if='form.errors.has("sex")'>{{ form.errors.get('sex') }}</span>
            </div>
            <div class='field'>
                <label class='label' for='joined_at'>* Pridružio se</label>
                <div class='control'>
                    <input type='date' class='input' id='joined_at' v-model='form.joined_at'>
                </div>
                <span class='help is-danger' v-if='form.errors.has("joined_at")'>{{ form.errors.get('joined_at') }}</span>
            </div>
            <div class='field'>
                <label class='label' for='joined_at'>Datum rođenja</label>
                <div class='control'>
                    <input type='date' class='input' id='joined_at' v-model='form.birthday'>
                </div>
                <span class='help is-danger' v-if='form.errors.has("birthday")'>{{ form.errors.get('birthday') }}</span>
            </div>
            <div class='field'>
                <div class='control'>
                    <label class='checkbox' for='active'>
                        <input type='checkbox' class='checkbox' id='active' v-model='form.active'>
                        Član je aktivan
                    </label>
                </div>
                <span class='help is-danger' v-if='form.errors.has("active")'>{{ form.errors.get('active') }}</span>
            </div>
            <input type='submit' :disabled='loading' value='Uredi' class='button' v-if='member'>
            <input type='submit' :disabled='loading' value='Kreiraj' class='button' v-if='!member'>
        </form>
    </div>
</template>

<script>
    import Form from '../../../Form'
    import moment from 'moment'
    import { mapActions } from 'vuex'

    export default {
        props: {
            member: {
                default: null
            }
        },
        data() {
            return {
                loading: false,
                form: new Form({
                    name: this.member ? this.member.name : '',
                    address: this.member ? this.member.address : '',
                    phone: this.member ? this.member.phone : '',
                    sex: this.member ? this.member.sex : 'M',
                    joined_at: this.member ? moment(this.member.joined_at).format('YYYY-MM-DD') : '',
                    active: this.member ? this.member.active : 1,
                    birthday: (this.member && this.member.birthday !== null) ? moment(this.member.birthday).format('YYYY-MM-DD') : '',
                    oib: this.member ? this.member.oib : ''
                }),
                isSuccess: false
            }
        },
        methods: {
            ...mapActions({
                addNewMember: 'members/addNewMember',
                editMemberData: 'members/editMemberData'
            }),
            submit() {
                this.member ? this.editMember() : this.createMember()
            },
            createMember() {
                this.loading = true

                this.form.post('members/new').then((response) => {
                    this.isSuccess = true
                    this.addNewMember(response.data)
                    this.loading = false
                }).catch(e => this.loading = false)
            },
            editMember() {
                this.loading = true

                this.form.post('members/'+this.member.id+'/edit').then((response) => {
                    this.isSuccess = true
                    let data = {
                        member: response.data,
                        activeState: this.member.active
                    }
                    this.editMemberData(data)
                    this.loading = false
                }).catch(e => this.loading = false)
            }
        }
    }
</script>
