<template>
    <i v-if='loading' class='fa fa-spinner'></i>
    <section id='members' v-else>
        <p>Ukupno članova: {{ members.active.length + members.inactive.length }} (aktivni: {{ members.active.length }}, neaktivni: {{ members.inactive.length }})</p>
        <p><a href='/members/new'>Novi član</a></p>
        <input type='text' v-model='searchQuery' placeholder='Petraži po imenu' style='width:auto'>
        <div class='active'>
            <h2>Aktivni</h2>
            <member v-for='member in searchedActive' :key='member.id' :member='member'></member>
        </div>
        <div class='inactive'>
            <h2>Neaktivni</h2>
            <member v-for='member in searchedInactive' :key='member.id' :member='member'></member>
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
            }),
            sortedActive() {
                return this.members.active.sort((a, b) => {
                    let dda = this.getDaysDifference(a)
                    let ddb = this.getDaysDifference(b)
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
            }
        },
        methods: {
            ...mapActions({
                getData: 'members/getData'
            }),
            getDaysDifference(member) {
                let date = this.getLatestValidUntil(member)
                return date.diff(moment().startOf('day'), 'days')
            },
            getLatestValidUntil(member) {
                if(!member.payments.length) return moment(member.joined_at).startOf('day')
                return moment(member.payments[0].valid_until)
            }
        },
        mounted() {
            this.getData()
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
