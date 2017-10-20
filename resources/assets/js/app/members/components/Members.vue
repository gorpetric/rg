<template>
    <div v-if='loading' class='loader'></div>
    <section id='members' v-else>
        <p>Ukupno članova: {{ members.active.length + members.inactive.length }} (aktivni: {{ members.active.length }}, neaktivni: {{ members.inactive.length }})</p>
        <p><a href='/members/new' @click.prevent='newMemberShowing = 1'>Novi član</a></p>
        <p><a href='#' @click.prevent='statsShowing = 1'>Statistika</a></p>
        <input type='text' v-model='searchQuery' placeholder='Petraži po imenu' style='width:auto'>
        <div class='active'>
            <h2>Aktivni</h2>
            <member v-for='member in searchedActive' :key='member.id' :member='member'></member>
        </div>
        <div class='inactive'>
            <h2>Neaktivni</h2>
            <member v-for='member in searchedInactive' :key='member.id' :member='member'></member>
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
                statsShowing: 0
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
