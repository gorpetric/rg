<template>
    <div class='member-block' :class='determineColor()'>
        <div class='main' @click.prevent='toggleInfo'>
            {{ member.name }}
            <small v-if='member.active'>( {{ getLatestValidUntil() | moment }} ) ( {{ getDaysDifference() }} )</small>
        </div>
        <div class='info' :class='showInfo()'>
            Adresa: <strong v-if='member.address'>{{ member.address }}</strong><i v-else><small>nije upisano</small></i><br>
            Kontakt broj: <strong v-if='member.phone'>{{ member.phone }}</strong><i v-else><small>nije upisano</small></i><br>
            Datum učlanjenja: <strong>{{ member.joined_at | moment }}</strong><br>
            <p class='text-center'>
                <button class='btn' @click='editMemberShowing = 1' title='Uredi'><i class='fas fa-edit'></i></button>
                <button class='btn' @click='paymentsShowing = 1' title='Plaćanja'><i class='fas fa-euro-sign'></i></button>
            </p>
        </div>
        <modal v-if='paymentsShowing' @close='paymentsShowing = 0'>
            <span slot='header'>{{ member.name }} - plaćanja<span v-if='member.active'> ( {{ getDaysDifference() }} )</span></span>
            <div slot='body'>
                <member-payments :member='member'></member-payments>
            </div>
        </modal>
        <modal v-if='editMemberShowing' @close='editMemberShowing = 0'>
            <span slot='header'>Uredi član: {{ member.name }}</span>
            <div slot='body'>
                <new-or-edit-member :member='member'></new-or-edit-member>
            </div>
        </modal>
    </div>
</template>

<script>
    import { getLatestValidUntil, getDaysDifference } from '../helpers'

    export default {
        data() {
            return {
                toggled: 0,
                paymentsShowing: 0,
                editMemberShowing: 0,
            }
        },
        props: ['member'],
        methods: {
            determineColor() {
                if(!this.member.active) return 'inactive'

                let diff = this.getDaysDifference()

                if(diff > 5) return 'ok'
                else if(diff > 0 && diff <= 5) return 'warning'
                else return 'danger'
            },
            getLatestValidUntil() {
                return getLatestValidUntil(this.member)
            },
            getDaysDifference() {
                return getDaysDifference(this.member)
            },
            toggleInfo() {
                if(this.toggled) {
                    this.toggled = 0
                    return
                }
                this.toggled = 1
            },
            showInfo() {
                if(!this.toggled) return ''
                return 'info-showing'
            }
        }
    }
</script>

<style scoped>
.member-block {
    border: 2px solid black;
    margin: 10px 0;
}
.main {
    padding: 10px;
    cursor: pointer;
}
.info {
    font-size: 16px;
    padding: 10px;
    border-top: 1px solid lightgray;
    display: none;
}
.info-showing {
    display: block
}
.danger {
    border-color: red;
}
.warning {
    border-color: orange;
}
.ok {
    border-color: green;
}
.inactive {
    border-color: gray;
}
</style>
