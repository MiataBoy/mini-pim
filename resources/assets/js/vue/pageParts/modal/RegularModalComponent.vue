<template>
    <div class="modal-overlay">
        <div class="modal">
            <div class="modal__header">
                <i class="fas fa-door-open" @click="closeModal"></i>
                <h2>{{ title }}</h2>
                <i class="fas fa-save" @click="saveProperty"></i>
            </div>
            <slot>
                Could not receive content from component.
                Please try again later.
            </slot>
            <div class="modal__footer" v-if="errors">
                {{ errors.message }}
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "regularModal",
    props: ['title', 'errors'],
    methods: {
        closeModal() {
            this.$emit('toggle-modal')
        },
        saveProperty() {
            this.$emit('save-properties')
        }
    }
}

</script>

<style scoped lang="scss">
@import "../../../../styles/sass/modules/manager/profiles/modals/createProfileModal";
</style>
