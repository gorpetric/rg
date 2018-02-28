<template>
    <transition name='modal-transition'>
    <div class='modal-mask' @click='close()'>
        <div class='modal-container' @click.stop>
            <div class='modal-header'>
                <slot name='header'></slot>
                <i class='fas fa-times' @click='close' title='Close'></i>
            </div>
            <div class='modal-inner'>
                <slot name='body'></slot>
            </div>
        </div>
    </div>
    </transition>
</template>

<script>
    export default {
        data() {
            return {

            }
        },
        methods: {
            close() {
                document.body.classList.remove('modal-open')
                this.$emit('close')
            }
        },
        mounted() {
            document.addEventListener('keydown', (e) => {
                if(e.keyCode == 27) this.close()
            })
            document.body.classList.add('modal-open')
        }
    }
</script>
