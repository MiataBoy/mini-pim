<template>
    <div class="modal-overlay">
        <div class="modal">
            <div class="modal__header">
                <h2>{{ $t('modals.confirmation.header') }}</h2>
            </div>
            <div class="modal__content">
                <div class="modal__content-formPart">
                    <p v-html="$t('modals.confirmation.content', {'name': name})"></p>
                </div>
            </div>
            <div class="modal__footer">
                <button class="btn btn-danger" @click="saveProperty">{{ $t('modals.confirmation.buttons.confirm') }}</button>
                <button class="btn btn-primary" @click="closeModal">{{ $t('modals.confirmation.buttons.deny') }}</button>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "confirmationModal",
    props: ['title', 'errors', 'name'],
    methods: {
        closeModal() {
            this.$emit('toggle-modal')
        },
        saveProperty() {
            this.$emit('save-properties')
        }
    },
}

</script>

<style scoped lang="scss">
@import "../../../../styles/sass/modules/pageParts/modal/confirmationModal";
</style>
