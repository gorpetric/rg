<template>
    <div class='container content'>
        <h1 class='title is-3'>Logs</h1>
        <p><input type='text' class='input' v-model='searchQuery' placeholder='Quick search'></p>
        <div class='table-container'>
            <table class='table is-bordered is-striped is-hoverable is-fullwidth'>
                <thead>
                    <tr>
                        <th scope="col" v-for='column in columns' @click='sortBy(column)' style='cursor: pointer'>
                            {{ column }}
                            <i v-if='sort.key == column' class='fa' :class="{ 'fa-arrow-up': sort.order == 'asc', 'fa-arrow-down': sort.order == 'desc' }"></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for='log in searchedLogs' :key='log.id'>
                        <td>{{ log.id }}</td>
                        <td>{{ log.env }}</td>
                        <td>{{ log.message }}</td>
                        <td>{{ log.level }}</td>
                        <td><a href='#' v-if='log.context' @click.prevent='openLog(log, "context")'>View</a></td>
                        <td><a href='#' v-if='log.extra' @click.prevent='openLog(log, "extra")'>View</a></td>
                        <td>{{ log.created_at }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <modal v-if='showLog' @close='showLog = 0'>
            <span slot='header'>{{ log.id }} - {{ log.message }}</span>
            <div slot='body'>
                <pre>{{ log[this.showWhat] }}</pre>
            </div>
        </modal>
    </div>
</template>

<script>
    export default {
        props: ['logs'],
        data() {
            return {
                loading: false,
                searchQuery: '',
                sort: {
                    key: 'id',
                    order: 'desc'
                },
                showLog: 0,
                showWhat: ''
            }
        },
        computed: {
            columns() {
                return [
                    'id', 'env', 'message', 'level', 'context', 'extra', 'created_at'
                ]
            },
            sortedLogs() {
                if(!this.sort.key) return this.logs
                return _.orderBy(this.logs, (i) => {
                    let value = i[this.sort.key]

                    if (!isNaN(parseFloat(value))) {
                        return parseFloat(value)
                    }

                    return String(i[this.sort.key]).toLowerCase()
                }, this.sort.order)
            },
            searchedLogs() {
                if(this.searchQuery == '') {
                    return this.sortedLogs
                }

                let searchRegex = new RegExp(this.searchQuery, 'i')

                return this.sortedLogs.filter((log) => {
                    return searchRegex.test(log.id) ||
                            searchRegex.test(log.env) ||
                            searchRegex.test(log.message) ||
                            searchRegex.test(log.level) ||
                            searchRegex.test(log.created_at)
                })
            },
            log() {
                return this.logs.find(l => l.id == this.showLog)
            }
        },
        methods: {
            sortBy(column) {
                this.sort.key = column
                this.sort.order = this.sort.order === 'asc' ? 'desc' : 'asc'
            },
            openLog(log, what) {
                this.showLog = log.id,
                this.showWhat = what
            }
        }
    }
</script>
