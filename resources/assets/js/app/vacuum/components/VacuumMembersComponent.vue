<template>
    <div v-if='!creating_new'>
        <p><button class='btn' @click='creating_new = true'>Novi član</button></p>
        <input type='text' v-model='searchQuery' placeholder='Pretraži po imenu'>
        <div class='member' v-for='member in searchedMembers' :key='member.id'>
            <i class='fas' :class="{ 'fa-male': member.sex == 'M', 'fa-female': member.sex == 'F' }"></i>&nbsp;{{ member.name }}&nbsp;
            <span v-if='member.address'><i class='fas fa-address-card'></i>&nbsp;{{ member.address }}&nbsp;</span>
            <span v-if='member.phone'><i class='fas fa-phone'></i>&nbsp;{{ member.phone }}&nbsp;</span>
            <p>
                <button class='form-btn' @click='goToMemberVacuum(member.id)'>Pregled svih termina</button>
            </p>
        </div>
    </div>
    <div v-else>
        <form autocomplete='off' @submit.prevent='createMember'>
            <div class='form-group'>
                <label for='name'>* Ime</label>
                <input type='text' id='name' v-model='new_values.name'>
                <span class='error-block' v-if='new_values.errors.has("name")'>{{ new_values.errors.get('name') }}</span>
            </div>
            <div class='form-group'>
                <label for='address'>Adresa</label>
                <input type='text' id='address' v-model='new_values.address'>
                <span class='error-block' v-if='new_values.errors.has("address")'>{{ new_values.errors.get('address') }}</span>
            </div>
            <div class='form-group'>
                <label for='phone'>Kontakt broj</label>
                <input type='text' id='phone' v-model='new_values.phone'>
                <span class='error-block' v-if='new_values.errors.has("phone")'>{{ new_values.errors.get('phone') }}</span>
            </div>
            <div class='form-group'>
                <span>Spol</span>&nbsp;
                <input type='radio' id='sex-m' value='M' v-model='new_values.sex'><label for='sex-m'>Muško</label>&nbsp;
                <input type='radio' id='sex-f' value='F' v-model='new_values.sex'><label for='sex-f'>Žensko</label>
                <span class='error-block' v-if='new_values.errors.has("sex")'>{{ new_values.errors.get('sex') }}</span>
            </div>
            <input type='submit' :disabled='loading' value='Kreiraj' class='form-btn'>
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
                return this.sortedMembers.filter(m => searchRegex.test(m.name))
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
