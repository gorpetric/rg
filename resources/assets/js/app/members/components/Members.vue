<template>
    <div class='section container content'>
        <h1 class='title is-3'>Članovi</h1>
        <input type='text' class='input' v-model='searchQuery' placeholder='Petraži po imenu'>
        <div v-if='loading' class='loader'></div>
        <div class='columns' style='margin-top:20px' v-else>
            <div class='column members-controls'>
                <p>
                    Ukupno članova: {{ members.active.length + members.inactive.length }}<br>
                    <label for='filter-active'>Aktivni: {{ members.active.length }}</label>
                    <input type='checkbox' v-model='filter.active' id='filter-active'><br>
                    <label for='filter-inactive'>Neaktivni: {{ members.inactive.length }}</label>
                    <input type='checkbox' v-model='filter.inactive' id='filter-inactive'><br>
                    <template v-if='searchQuery.length'>Pretraživanje: {{ searchedActive.length + searchedInactive.length }}</template>
                </p>
                <button class='button' @click='newMemberShowing = 1'><i class='fas fa-user-plus'></i>&nbsp;Novi član</button>
            </div>
            <div class='column is-three-quarters members-list'>
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
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import { getDaysDifference } from '../helpers'

    export default {
        data() {
            return {
                searchQuery: '',
                newMemberShowing: 0,
                filter: {
                    active: true,
                    inactive: true
                }
            }
        },
        computed: {
            ...mapGetters({
                loading: 'members/loading',
                members: 'members/members'
            }),
            sortedActive() {
                return this.members.active.sort((a, b) => {
                    let dda = getDaysDifference(a)
                    let ddb = getDaysDifference(b)
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
                    return searchRegex.test(member.name) ||
                            searchRegex.test(member.id)
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
.members-controls
    .button
        width: 100%
        margin: 10px 0
</style>
