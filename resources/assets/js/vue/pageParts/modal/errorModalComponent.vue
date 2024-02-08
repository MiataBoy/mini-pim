<template>
    <div class="notificationModal-overlay">
        <div class="notificationModal">
            <div class="notificationModal__header">
                {{ title }}
            </div>
            <div class="modal__content" role="form">
                <div class="modal__content-formPart">
                    <slot>
                        Could not receive content from component.
                        Please try again later.
                    </slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "errorModal",
    props: ['title'],
    mounted() {
        setTimeout(() => {
            this.closeModal()
        }, 3000)
    },
    methods: {
        closeModal() {
            this.$emit('toggle-modal')
        },
    }
}

</script>

<style scoped lang="scss">
@import "../../../../styles/sass/modules/pageParts/modal/errorModal";
</style>
