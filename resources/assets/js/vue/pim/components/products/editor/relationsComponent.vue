<template>
    <nav class="tabNav">
        <div class="tabNav__item" @click="this.$router.push(`/pim/products/edit/${this.$route.params.id}/specifications`)">
            {{ $t('productEditor.specifications.pageHeader') }}
        </div>
        <div class="tabNav__item" @click="this.$router.push(`/pim/products/edit/${this.$route.params.id}/assets`)">
            {{ $t('productEditor.assets.pageHeader') }}
        </div>
        <div class="tabNav__item" @click="this.$router.push(`/pim/products/edit/${this.$route.params.id}/relations`)">
            {{ $t('productEditor.articleGroups.pageHeader') }}
        </div>
    </nav>
    <div class="content-container">
        <div class="content">
            <notification-modal @toggle-modal="toggleNotificationModal" v-if="showNotificationModal" :title="$t('rights.notificationModal.success')">
                {{ $t('productEditor.assets.notificationModal.successContent') }}
            </notification-modal>
            <confirmation-modal :name="confirmationData.child_id" v-if="showConfirmationModal" @toggle-modal="toggleConfirmModal" @save-properties="deleteRelation"></confirmation-modal>
            <header class="content__header">
                <i class="fas fa-door-open fa-2x content__header-cancel" @click="$router.back()"></i>
                <div class="content__header-type">
                    <h2>{{ $t('productEditor.relations.pageHeader') }}</h2>
                </div>
            </header>
            <section class="dataSection">
                <div class="dataSection__manage">
                    <button class="dataSection__manage-new" @click="showModal = true">{{ $t('productEditor.relations.newButton') }}</button>
                    <regular-modal @toggle-modal="toggleModal" @save-properties="saveRelation(relation.id)" v-if="showModal" :title="$t('productEditor.relations.modal.title')" :errors="this.errors">
                        <div class="modal__content" role="form" @keyup.enter.prevent="saveRelation(relation.id)">
                            <label for="name">{{ $t('productEditor.relations.childProduct') }}</label>
                            <input class="modal__content-input" v-model="relation.child_id" type="text" name="name" required>
                            <label for="type">{{ $t('productEditor.relations.type') }}</label>
                            <select class="modal__content-input" v-model="relation.type" name="type" required>
                                <option class="modal__content-input__selector" v-for="type in types" :value="type.id">{{type.id}}</option>
                            </select>
                        </div>
                    </regular-modal>
                    <button class="dataSection__manage-danger" @click="deleteAllRelations">{{ $t('productEditor.assets.deleteAll') }}</button>
                </div>
                <table class="dataSection__table">
                    <tr class="dataSection__table-section">
                        <th class="dataSection__table-info">{{ $t('productEditor.relations.idRow') }}</th>
                        <th class="dataSection__table-info">{{ $t('productEditor.relations.typeRow') }}</th>
                        <th class="dataSection__table-info">{{ $t('productEditor.relations.actionRow') }}</th>
                    </tr>
                    <tr class="dataSection__table-section" v-if="loading">
                        <td class="dataSection__table-info">
                            <span class="skeleton-loader"></span>
                        </td>
                        <td class="dataSection__table-info">
                            <span class="skeleton-loader"></span>
                        </td>
                        <td class="dataSection__table-info">
                            <span class="skeleton-loader"></span>
                        </td>
                        <td class="dataSection__table-info">
                            <span class="skeleton-loader"></span>
                        </td>
                    </tr>
                    <tr class="dataSection__table-section" v-if="!loading && relations" v-for="relation in relations" :key="relation.id">
                        <td class="dataSection__table-info noImagePadding">{{ relation.child_id }}</td>
                        <td class="dataSection__table-info noImagePadding">{{ relation.type }}</td>
                        <td class="dataSection__table-info" v-if="actionRights['product.modify'] || actionRights['product.delete']">
                            <div class="dataSection__table-infoIcons">
                                <i class="fas fa-edit actions__pencil" v-if="actionRights['product.modify']" @click="fillData(relation)"></i>
                                <i class="fas fa-trash actions__trash" v-if="actionRights['product.delete']" @click="toggleConfirmModal(relation)"></i>
                            </div>
                        </td>
                    </tr>
                </table>
            </section>
        </div>
    </div>
</template>

<script>
import axios from '../../../../../../../js/axios.config'
import NotificationModal from "../../../../pageParts/modal/notificationModalComponent.vue"
import ConfirmationModal from "../../../../pageParts/modal/confirmationModalComponent.vue"
import RegularModal from "../../../../pageParts/modal/RegularModalComponent.vue"
import {hasRight} from "../../../../composables/rights"

export default {
    name: "productEditor-relations",
    components: {RegularModal, ConfirmationModal, NotificationModal},
    data() {
        return {
            showModal: false,
            showNotificationModal: false,
            showConfirmationModal: false,
            confirmationData: null,
            relation: {
                child_id: null,
                type: null,
            },
            relations: {},
            products: {},
            types: {},
            loading: false,
            actionRights: null,
            errors: null,
        }
    },
    async mounted() {
        this.loading = true
        this.actionRights = Object(await hasRight(['product.modify', 'product.delete']))
        await this.getRelations()
        await this.getTypes()
        this.loading = false
    },
    methods: {
        /**
         * Toggles the modal containing the creation form
         * @return void
         */
        toggleModal() {
            this.showModal = !this.showModal
        },
        /**
         * Toggles the modal containing the success notification
         * @return void
         */
        toggleNotificationModal() {
            this.showNotificationModal = !this.showNotificationModal
        },
        /**
         * Toggles the modal containing the confirmation for a delete action
         * @param {Object} relation
         * @return void
         */
        toggleConfirmModal(relation) {
            this.showConfirmationModal = !this.showConfirmationModal
            this.confirmationData = relation
        },
        /**
         * Retrieves all available types that a relation between products can have
         * @return {Promise<void>}
         */
        async getTypes() {
            await axios.get('/api/products/get/types/relations')
                .then(response => {
                    this.types = response.data
                })
        },
        /**
         * Retrieves all relations that the product has
         * @return {Promise<void>}
         */
        async getRelations() {
            await axios.get(`/api/products/get/${this.$route.params.id}/relations`)
                .then(response => {
                    this.relations = response.data
                })
        },
        /**
         * Saves a new relation or updates a relation
         * @param {Number} relationId
         * @return {Promise<void>}
         */
        async saveRelation(relationId) {
            let apiUrl = null

            if (relationId) {
                apiUrl = `/api/products/update/${this.$route.params.id}/relations`
            } else {
                apiUrl = `/api/products/save/${this.$route.params.id}/relations`
            }

            await axios.post(apiUrl, this.relation )
                .then(response => {
                    this.toggleModal()
                    this.toggleNotificationModal()
                    this.getRelations()
                })
                .catch((error => {
                    console.error(error)
                    this.errors = error.response.data
                }))
        },
        /**
         * Fills the editing modal data
         * @param {Object} relation
         * @return void
         */
        fillData(relation) {
            this.relation.id = relation.id
            this.relation.child_id = relation.child_id
            this.relation.type = relation.type

            this.toggleModal()
        },
        /**
         * Deletes the relation from the parent product
         * @return {Promise<void>}
         */
        async deleteRelation() {
            const relation = this.confirmationData
            await axios.post(`/api/products/delete/${this.$route.params.id}/relations`, {relation})
                .then(response => {
                    this.toggleConfirmModal()
                    this.toggleNotificationModal()
                    this.getRelations()
                })
        },
        /**
         * Deletes all relations from the product
         * @return {Promise<void>}
         */
        async deleteAllRelations() {
            await axios.post(`/api/products/deleteAll/${this.$route.params.id}/relations`)
                .then(response => {
                    this.toggleNotificationModal()
                    this.getRelations()
                })
        }
    }
}

</script>

<style scoped lang="scss">
@import "../../../../../../styles/sass/modules/pageParts/settingsContentContainer";
@import "../../../../../../styles/sass/modules/pageParts/tableStyles";
@import "../../../../../../styles/sass/modules/pim/products/settings/relations";
</style>
