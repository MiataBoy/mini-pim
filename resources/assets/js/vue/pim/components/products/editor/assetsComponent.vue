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
            <confirmation-modal :name="confirmationData.file_name" v-if="showConfirmationModal" @toggle-modal="toggleConfirmModal" @save-properties="deleteAsset"></confirmation-modal>
            <header class="content__header">
                <i class="fas fa-door-open fa-2x content__header-cancel" @click="$router.back()"></i>
                <div class="content__header-type">
                    <h2>{{ $t('productEditor.assets.pageHeader') }}</h2>
                </div>
            </header>
            <section class="dataSection">
                <div class="dataSection__manage">
                    <label for="fileExplorer" class="dataSection__manage-new">
                        <input type="file" id="fileExplorer" accept="image/*" style="display: none" @change="handleFileChanges">
                        {{ $t('productEditor.assets.newButton') }}
                    </label>
                    <button class="dataSection__manage-danger" @click="deleteAllAssets">{{ $t('productEditor.assets.deleteAll') }}</button>
                </div>
            </section>
            <section class="dataSection__assets">
                <div class="dataSection__assets-asset" v-for="asset in assets">
                    <img class="dataSection__assets-image" :src="'/storage/'+asset.file_path" :alt="asset.file_name">
                    <i class="fas fa-trash dataSection__assets__icon" @click="toggleConfirmModal(asset)"></i>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import {hasRight} from "../../../../composables/rights"
import RegularModal from "../../../../pageParts/modal/RegularModalComponent.vue"
import NotificationModal from "../../../../pageParts/modal/notificationModalComponent.vue"
import ConfirmationModal from "../../../../pageParts/modal/confirmationModalComponent.vue"

export default {
    name: "productSettings-assets",
    components: {ConfirmationModal, NotificationModal, RegularModal},
    data() {
        return {
            selectedFile: [],
            loading: false,
            showModal: false,
            errors: null,
            assets: [],
            showNotificationModal: false,
            confirmationData: null,
            showConfirmationModal: false,
        }
    },
    async mounted() {
        this.loading = true

        this.actionRights = Object(await hasRight(['product.modify', 'product.delete']))
        this.getProduct()

        this.loading = false
    },
    methods: {
        /**
         * Toggles the modal containing the confirmation for a delete action
         * @param {Object} asset
         * @return void
         */
        toggleConfirmModal(asset) {
            this.showConfirmationModal = !this.showConfirmationModal
            this.confirmationData = asset
        },
        /**
         * Toggles the modal containing the success notification
         * @return void
         */
        toggleNotificationModal() {
            this.showNotificationModal = !this.showNotificationModal
            this.errors = null
        },
        /**
         * Retrieves the currently accessed product's assets
         * @return {Promise<void>}
         */
        getProduct() {
            axios.get(`/api/products/${this.$route.params.id}`)
                .then(response => {
                    this.assets = response.data.assets
                })
                .catch(error => {
                    console.log(error)
                })
        },
        /**
         * Toggles the modal containing the creation form
         * @return void
         */
        toggleModal() {
            this.showModal = !this.showModal
            this.errors = null
        },
        /**
         * Attempts to upload the assets that the user has provided through the file uploader
         * @param {File} event
         * @return {Promise<void>}
         */
        handleFileChanges(event) {
            this.selectedFile = event.target.files[0]
            if (!this.selectedFile) {
                console.error('A file upload was attempted but a file could not be found.')
            }
            const formData = new FormData()
            formData.append('file', this.selectedFile)

            axios.post(`/api/assets/upload?id=${this.$route.params.id}`, formData)
                .then(response => {
                    this.getProduct()
                    this.toggleNotificationModal()
                })
                .catch(error => {
                    console.error(`Error generated during file upload: ${error}`)
                })
        },
        /**
         * Deletes all assets belonging to the product
         * @return {Promise<void>}
         */
        deleteAllAssets() {
            axios.post(`/api/assets/delete/all`, {'id': this.$route.params.id})
                .then(response => {
                    this.getProduct()
                })
                .catch(error => {
                    console.error(error)
                })
        },
        /**
         * Deletes the given asset from the product's data
         * @return {Promise<void>}
         */
        deleteAsset() {
            const asset = this.confirmationData
            axios.post(`/api/assets/delete/{id}`, {asset: asset})
                .then(response => {
                    this.toggleConfirmModal()
                    this.getProduct()
                })
                .catch(error => {
                    console.error(error)
                })
        }
    }
}

</script>

<style scoped lang="scss">
@import "../../../../../../styles/sass/modules/pageParts/settingsContentContainer";
@import "../../../../../../styles/sass/modules/pim/products/productContent";
@import "../../../../../../styles/sass/modules/pim/products/settings/assets";
</style>
