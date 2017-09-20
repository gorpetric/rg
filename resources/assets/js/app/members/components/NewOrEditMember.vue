<template>
    <p v-if='isSuccess' style='color: green'>Uspješno!</p>
    <div v-else>
        <form autocomplete='off' @submit.prevent='submit'>
            <div class='form-group'>
                <label for='name'>* Ime</label>
                <input type='text' id='name' v-model='form.name'>
                <span class='error-block' v-if='form.errors.has("name")'>{{ form.errors.get('name') }}</span>
            </div>
            <div class='form-group'>
                <label for='address'>Adresa</label>
                <input type='text' id='address' v-model='form.address'>
                <span class='error-block' v-if='form.errors.has("address")'>{{ form.errors.get('address') }}</span>
            </div>
            <div class='form-group'>
                <label for='phone'>Kontakt broj</label>
                <input type='text' id='phone' v-model='form.phone'>
                <span class='error-block' v-if='form.errors.has("phone")'>{{ form.errors.get('phone') }}</span>
            </div>
            <div class='form-group'>
                <span>Spol</span>&nbsp;
                <input type='radio' id='sex-m' value='M' v-model='form.sex'><label for='sex-m'>Muško</label>&nbsp;
                <input type='radio' id='sex-f' value='F' v-model='form.sex'><label for='sex-f'>Žensko</label>
                <span class='error-block' v-if='form.errors.has("sex")'>{{ form.errors.get('sex') }}</span>
            </div>
            <div class='form-group'>
                <label for='joined_at'>* Pridružio se</label>
                <input type='date' id='joined_at' v-model='form.joined_at'>
                <span class='error-block' v-if='form.errors.has("joined_at")'>{{ form.errors.get('joined_at') }}</span>
            </div>
            <div class='form-group'>
                <label for='active'>Član je aktivan</label>&nbsp;
                <input type='checkbox' id='active' v-model='form.active'>
                <span class='error-block' v-if='form.errors.has("active")'>{{ form.errors.get('active') }}</span>
            </div>
            <input type='submit' value='Uredi' class='form-btn' v-if='member'>
            <input type='submit' value='Kreiraj' class='form-btn' v-if='!member'>
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
                form: new Form({
                    name: this.member ? this.member.name : '',
                    address: this.member ? this.member.address : '',
                    phone: this.member ? this.member.phone : '',
                    sex: this.member ? this.member.sex : 'M',
                    joined_at: this.member ? moment(this.member.joined_at).format('YYYY-MM-DD') : '',
                    active: this.member ? this.member.active : 1,
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
                this.form.post('members/new').then((response) => {
                    this.isSuccess = true
                    this.addNewMember(response.data)
                })
            },
            editMember() {
                this.form.post('members/'+this.member.id+'/edit').then((response) => {
                    this.isSuccess = true
                    let data = {
                        member: response.data,
                        activeState: this.member.active
                    }
                    this.editMemberData(data)
                })
            }
        }
    }
</script>
