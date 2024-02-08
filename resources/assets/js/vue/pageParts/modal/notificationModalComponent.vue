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

export default {
    name: "notificationModal",
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
@import "../../../../styles/sass/modules/pageParts/modal/notificationModal";
</style>
