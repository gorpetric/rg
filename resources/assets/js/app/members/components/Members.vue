<template>
    <div v-if='loading' class='loader'></div>
    <section id='members' v-else>
        <input type='text' v-model='searchQuery' placeholder='Petraži po imenu'>
        <div class='members-controls'>
            <p>
                Ukupno članova: {{ members.active.length + members.inactive.length }}<br>
                <label for='filter-active'>Aktivni: {{ members.active.length }}</label>
                <input type='checkbox' v-model='filter.active' id='filter-active'><br>
                <ul style='margin:0'>
                    <li>
                        <label for='filter-red'>Crveni</label>
                        <input type='checkbox' v-model='filter.red' :disabled='!filter.active' id='filter-red'>
                    </li>
                    <li>
                        <label for='filter-orange'>Narančasti</label>
                        <input type='checkbox' v-model='filter.orange' :disabled='!filter.active' id='filter-orange'>
                    </li>
                    <li>
                        <label for='filter-green'>Zeleni</label>
                        <input type='checkbox' v-model='filter.green' :disabled='!filter.active' id='filter-green'>
                    </li>
                </ul>
                <label for='filter-inactive'>Neaktivni: {{ members.inactive.length }}</label>
                <input type='checkbox' v-model='filter.inactive' id='filter-inactive'><br>
                <template v-if='searchQuery.length'>Pretraživanje: {{ searchedActive.length + searchedInactive.length }}</template>
            </p>
            <button class='btn' @click='newMemberShowing = 1' title='Novi član'><i class='fas fa-user-plus'></i></button>
            <button class='btn' @click='statsShowing = 1' title='Statistika'><i class='fas fa-list-ol'></i></button>
        </div>
        <div class='members-list'>
            <div class='active' v-show='filter.active && searchedActive.length'>
                <h2>Aktivni</h2>
                <member v-for='member in searchedActive' :key='member.id' :member='member'></member>
            </div>
            <div class='inactive' v-show='filter.inactive && searchedInactive.length'>
                <h2>Neaktivni</h2>
                <member v-for='member in searchedInactive' :key='member.id' :member='member'></member>
            </div>
        </div>
        <modal v-if='newMemberShowing' @close='newMemberShowing = 0'>
            <span slot='header'>Novi član</span>
            <div slot='body'>
                <new-or-edit-member></new-or-edit-member>
            </div>
        </modal>
        <modal v-if='statsShowing' @close='statsShowing = 0'>
            <span slot='header'>Statistika</span>
            <div slot='body'>
                <member-payments-monthly-stats></member-payments-monthly-stats>
            </div>
        </modal>
    </section>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import { getDaysDifference } from '../helpers'

    export default {
        data() {
            return {
                searchQuery: '',
                newMemberShowing: 0,
                statsShowing: 0,
                filter: {
                    active: true,
                    inactive: true,
                    green: true,
                    orange: true,
                    red: true
                }
            }
        },
        computed: {
            ...mapGetters({
                loading: 'members/loading',
                members: 'members/members'
            }),
            sortedActive() {
                return this.filteredActive.sort((a, b) => {
                    let dda = getDaysDifference(a)
                    let ddb = getDaysDifference(b)
                    return dda - ddb
                })
            },
            sortedInactive() {
                return this.filteredInactive.sort((a, b) => {
                    if(a.name < b.name) return -1
                    if(a.name > b.name) return 1
                    return 0
                })
            },
            filteredActive() {
                return this.members.active.filter(member => {
                    let dd = getDaysDifference(member)
                    if(dd <= 0 && this.filter.red) return member
                    else if(dd > 0 && dd <= 5 && this.filter.orange) return member
                    else if(dd > 5 && this.filter.green) return member
                })
            },
            filteredInactive() {
                return this.members.inactive
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
            })
        },
        mounted() {
            this.getData()
        }
    }
</script>

<style scoped lang=sass>
#members
    display: flex
    flex-wrap: wrap

    .members-controls
        margin-right: 20px

        .btn
            width: 100%
            margin: 10px 0

    .members-list
        flex-grow: 2

    @media(max-width: 650px)
        display: block

        .members-controls
            margin: 0

            .btn
                width: auto
                margin: 10px
</style>
