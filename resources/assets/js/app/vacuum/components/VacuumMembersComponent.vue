<template>
    <div v-if='!creating_new'>
        <p><button class='button' @click='creating_new = true'>Novi vacuum član</button></p>
        <input type='text' class='input' v-model='searchQuery' placeholder='Pretraži po imenu'>
        <div class='member' v-for='member in searchedMembers' :key='member.id'>
            <i class='fas' :class="{ 'fa-male': member.sex == 'M', 'fa-female': member.sex == 'F' }"></i>&nbsp;{{ member.name }}&nbsp;
            <span v-if='member.address'><i class='fas fa-address-card'></i>&nbsp;{{ member.address }}&nbsp;</span>
            <span v-if='member.phone'><i class='fas fa-phone'></i>&nbsp;{{ member.phone }}&nbsp;</span><br>
            <button class='button' @click='goToMemberVacuum(member.id)'>Pregled svih termina</button>
        </div>
    </div>
    <div v-else>
        <form autocomplete='off' @submit.prevent='createMember'>
            <div class='field'>
                <label class='label' for='name'>* Ime</label>
                <div class='control'>
                    <input type='text' class='input' id='name' v-model='new_values.name' placeholder='Ime'>
                </div>
                <span class='help is-danger' v-if='new_values.errors.has("name")'>{{ new_values.errors.get('name') }}</span>
            </div>
            <div class='field'>
                <label class='label' for='address'>Adresa</label>
                <div class='control'>
                    <input type='text' class='input' id='address' v-model='new_values.address'>
                </div>
                <span class='help is-danger' v-if='new_values.errors.has("address")'>{{ new_values.errors.get('address') }}</span>
            </div>
            <div class='field'>
                <label class='label' for='phone'>Kontakt broj</label>
                <div class='control'>
                    <input type='text' class='input' id='phone' v-model='new_values.phone'>
                </div>
                <span class='help is-danger' v-if='new_values.errors.has("phone")'>{{ new_values.errors.get('phone') }}</span>
            </div>
            <div class='field'>
                <div class='control'>
                    <label for='sex-m' class='label'>
                        <input type='radio' id='sex-m' value='M' v-model='new_values.sex'>
                        Muško
                    </label>
                    <label for='sex-f' class='label'>
                        <input type='radio' id='sex-f' value='F' v-model='new_values.sex'>
                        Žensko
                    </label>
                </div>
                <span class='help is-danger' v-if='new_values.errors.has("sex")'>{{ new_values.errors.get('sex') }}</span>
            </div>
            <input type='submit' :disabled='loading' value='Kreiraj' class='button'>
            <a href='#' @click.prevent='creating_new = false'>Odustani</a>
        </form>
    </div>
</template>

<script>
    import Form from '../../../Form'

    export default {
        props: ['membersprop'],
        data() {
            return {
                members: this.membersprop,
                loading: false,
                searchQuery: '',
                creating_new: false,
                new_values: new Form({
                    name: null,
                    address: null,
                    phone: null,
                    sex: 'M'
                })
            }
        },
        computed: {
            sortedMembers() {
                return _.orderBy(this.members, [u => u.name.toLowerCase()], ['asc'])
            },
            searchedMembers() {
                let searchRegex = new RegExp(this.searchQuery, 'i')
                if(this.searchQuery == '') {
                    return this.sortedMembers
                }
                return this.sortedMembers.filter(m => {
                    return searchRegex.test(m.name) ||
                            searchRegex.test(m.id)
                })
            }
        },
        methods: {
            goToMemberVacuum(id, hash = null) {
                let x = hash ? '#' + hash : ''
                window.location.href = '/members/vacuum/'+id + x
            },
            createMember() {
                this.loading = true

                this.new_values.post('/members/vacuum').then(response => {
                    this.members.unshift(response.member)
                    this.new_values.sex = 'M'
                    this.loading = false
                    this.creating_new = false
                }).catch(e => this.loading = false)
            }
        }
    }
</script>

<style scoped>
.member {
    margin: 10px 0;
    padding: 10px;
    border-bottom: 1px solid rgba(0,0,0,.06);
}
.member:last-child {
    border: none;
}
</style>
