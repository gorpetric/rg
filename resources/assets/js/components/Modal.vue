<template>
    <transition name='modal-transition'>
    <div class='modal is-active'>
        <div class='modal-background' @click='close()'></div>
        <div class='modal-card'>
            <header class='modal-card-head'>
                <span class='modal-card-title'><slot name='header'></slot></span>
                <button class='delete' aria-label='close' @click='close()'></button>
            </header>
            <section class='modal-card-body'><slot name='body'></slot></section>
        </div>
    </div>
    </transition>
</template>

<script>
    export default {
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
