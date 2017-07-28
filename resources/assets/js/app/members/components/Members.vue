<template>
    <i v-if='loading' class='fa fa-spinner'></i>
    <section id='members' v-else>
        <p><a href='/clanovi/novi'>Novi član</a></p>
        <input type='text' v-model='searchQuery' placeholder='Pretraži po imenu'>
        <div class='active'>
            <h2>Aktivni</h2>
            <member v-for='member in members.active' :key='member.id' :member='member'></member>
        </div>
        <div class='inactive'>
            <h2>Neaktivni</h2>
            <member v-for='member in members.inactive' :key='member.id' :member='member'></member>
        </div>
    </section>
</template>

<script>
    import moment from 'moment'
    import { mapGetters, mapActions } from 'vuex'

    export default {
        data() {
            return {
                searchQuery: ''
            }
        },
        computed: {
            ...mapGetters({
                loading: 'members/loading',
                members: 'members/members'
            }),/*
            sortedActive() {
                return this.members.active.sort((a, b) => {
                    let dda = a.payments[0].valid_until
                    let ddb = b.payments[0].valid_until
                    return dda - ddb
                })
            },
            sortedInactive() {
                return this.members.inactive.sort((a, b) => {
                    if(a.name < b.name) return -1
                    if(a.name > b.name) return 1
                    return 0
                })
            },
            searchedActive() {
                let searchRegex = new RegExp(this.searchQuery, 'i')
                if(this.searchQuery == '') {
                    return this.sortedActive
                }
                return this.sortedActive.filter((member) => {
                    return searchRegex.test(member.name)
                })
            },
            searchedInactive() {
                let searchRegex = new RegExp(this.searchQuery, 'i')
                if(this.searchQuery == '') {
                    return this.sortedInactive
                }
                return this.sortedInactive.filter((member) => {
                    return searchRegex.test(member.name)
                })
            }*/
        },
        methods: {
            ...mapActions({
                getData: 'members/getData'
            })
        },
        mounted() {
            this.getData()
        }
    }
</script>

<style scoped>
input[type='text'] {
    max-width: 100%;
    padding: 5px;
}
@keyframes spin {
    from { transform: rotate(0deg) }
    to { transform: rotate(360deg) }
}
.fa-spinner {
    animation: spin 1s infinite;
}
</style>
