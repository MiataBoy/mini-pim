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
            <notification-modal @toggle-modal="toggleNotificationModal" v-if="showNotificationModal" :title="$t('productEditor.specs.notificationModal.success')">
                {{ $t('productEditor.specs.notificationModal.successContent') }}
            </notification-modal>
            <confirmation-modal :name="confirmationData.specifications.translations[0].name" v-if="showConfirmationModal" @toggle-modal="toggleConfirmModal" @save-properties="removeSpecification"></confirmation-modal>
            <header class="content__header">
                <i class="fas fa-door-open fa-2x content__header-cancel" @click="$router.back()"></i>
                <div class="content__header-type">
                    <h2>{{ $t('productEditor.specifications.pageHeader') }}</h2>
                </div>
            </header>
            <section class="dataSection">
                <div class="dataSection__manage">
                    <button class="dataSection__manage-new" @click="showModal = true">{{ $t('productEditor.specifications.newButton') }}</button>
                    <regular-modal @toggle-modal="toggleModal" v-if="showModal" :title="$t('products.newModalTitle')" :errors="this.errors">
                        <div class="modal__content" role="form">
                            <div class="modal__content-formPart">
                                <table class="dataSection__table">
                                    <tr class="dataSection__table-section">
                                        <th class="dataSection__table-info">{{ $t('specifications.idRow') }}</th>
                                        <th class="dataSection__table-info">{{ $t('specifications.nameRow') }}</th>
                                        <th class="dataSection__table-info">{{ $t('specifications.typeRow') }}</th>
                                        <th class="dataSection__table-info">{{ $t('specifications.actionsRow') }}</th>
                                    </tr>
                                    <tr class="dataSection__table-section" v-for="specification in specifications" :key="specification.id">
                                        <td class="dataSection__table-info">{{specification.id}}</td>
                                        <td class="dataSection__table-info">{{specification.translations[0].name}}</td>
                                        <td class="dataSection__table-info">{{specification.input_type.id}}</td>
                                        <td><i class="fas fa-plus-square" @click="addSpecification(specification)"></i></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </regular-modal>
                    <div class="langSelector">
                        <select v-model="selectedLanguage" @change="setLanguage(selectedLanguage); getProductSpecifications">
                            <option v-for="language in pimLanguages" :value="language.code">{{ language.code }}</option>
                        </select>
                    </div>
                    <button class="dataSection__manage-danger" @click="removeAllSpecifications">Alles verwijderen</button>
                </div>

                <div class="dataSection__specs" v-if="loading">
                    <div class="dataSection__specs-item" v-for="n in 10">
                        <div class="dataSection__specs-item__label">
                            <span class="skeleton-loader"></span>
                        </div>
                        <div class="dataSection__specs-item__manager">
                            <span class="skeleton-loader"></span>
                        </div>
                    </div>
                </div>

                <div v-else class="dataSection__specs" v-if="productSpecifications">
                    <div class="dataSection__specs-item" v-for="specification in productSpecifications">
                        <div class="dataSection__specs-item__label" :title="specification.specifications.translations[0].name">
                            <label for="spec">{{ specification.specifications.translations[0].name }}</label>
                        </div>
                        <div class="dataSection__specs-item__manager">
                            <input class="dataSection__specs-item__input" :type="specification.specifications.inputType_id" name="spec" v-model="specification.translations[0].value" @change="updateSpecification(specification)">
                            <i class="fas fa-x 2x" @click="toggleConfirmModal(specification)"></i>
                        </div>
                    </div>
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
    name: "productSettings-specifications",
    components: {ConfirmationModal, NotificationModal, RegularModal},
    data() {
        return {
            loading: false,
            showModal: false,
            productSpecifications: {},
            specifications: [],
            errors: null,
            value: null,
            showNotificationModal: false,
            selectedLanguage: 'nl',
            pimLanguages: [],
            confirmationData: null,
            showConfirmationModal: false,
        }
    },
    async mounted() {
        this.loading = true
        await this.getLanguages()
        this.selectedLanguage = localStorage.getItem('pimLang') ? localStorage.getItem('pimLang') : 'nl'
        await this.getProductSpecifications()
        await this.getSpecifications()
        this.actionRights = Object(await hasRight(['product.modify', 'product.delete']))

        this.loading = false
    },
    methods: {
        /**
         * Toggles the modal containing the confirmation for a delete action
         * @param {Object} specification
         * @return void
         */
        toggleConfirmModal(specification) {
            this.showConfirmationModal = !this.showConfirmationModal
            this.confirmationData = specification
        },
        /**
         * Gets the available languages for translations of specification values in the product
         * @return {Promise<void>}
         */
        async getLanguages() {
            await axios.get(`/api/locales/pim/all`)
                .then(response => {
                    this.pimLanguages = response.data
                })
        },
        /**
         * Allows for changing the language in which the given value should be saved
         * @param {Object} language
         * @return void
         */
        setLanguage(language) {
            localStorage.setItem('pimLang', language)
            this.selectedLanguage = language
            this.getProductSpecifications()
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
         * Toggles the modal containing the creation form
         * @return void
         */
        toggleModal() {
            this.showModal = !this.showModal
            this.errors = null
        },
        /**
         * Retrieves all specifications so that the user may select from the list of specifications
         * @return {Promise<void>}
         */
        async getSpecifications() {
            await axios.get('/api/specifications/all')
                .then(response => {
                    this.specifications = []
                    response.data.forEach((item) => {
                        const isUnique = this.productSpecifications.every((entry) => {
                            return item.id !== parseInt(entry.specification_id);
                        });
                        if (isUnique) {
                            console.log(`push ${JSON.stringify(item)}`);
                            this.specifications.push(item);
                        }
                    });
                })
                .catch(error => {
                    console.error(error)
                })

        },
        /**
         * Adds the selected specification to the product without a value
         * @param {Object} specification
         * @return {Promise<void>}
         */
        async addSpecification(specification) {
            await axios.post(`/api/products/save/${this.$route.params.id}/specifications`, {specification: specification})
                .then(async response => {
                    this.toggleModal()
                    this.toggleNotificationModal()
                    await this.getProductSpecifications()
                    await this.getSpecifications()
                })
                .catch(error => {
                    console.error(error)
                })
        },
        /**
         * Removes the specification from the product
         * @return {Promise<void>}
         */
        async removeSpecification() {
            const specification = this.confirmationData
            await axios.post(`/api/products/delete/${specification.id}/specifications`)
                .then(async response => {
                    this.toggleConfirmModal()
                    this.toggleNotificationModal()
                    await this.getProductSpecifications()
                    await this.getSpecifications()
                })
        },
        /**
         * Removes all specifications from the product
         * @return {Promise<void>}
         */
        async removeAllSpecifications() {
            await axios.post(`/api/products/deleteAll/${this.$route.params.id}/specifications`)
                .then(response => {
                    this.toggleNotificationModal()
                    this.getProductSpecifications()
                })
        },
        /**
         * Retrieves all of the specifications and their values and pushes them to the display array
         * @return {Promise<void>}
         */
        async getProductSpecifications() {
            const locale = this.selectedLanguage
            await axios.post(`/api/products/get/${this.$route.params.id}/specifications`, {locale: locale})
                .then(response => {
                    this.productSpecifications = response.data
                    console.log(this.productSpecifications)
                    for (let key in this.productSpecifications) {
                        let specification = this.productSpecifications[key]

                        if (!specification.translations.length) {
                            specification.translations[0] = {
                                "value_id": specification.id,
                                "value": null,
                                "locale": this.selectedLanguage
                            }
                        }
                    }

                })
                .catch(error => {
                    console.error(error)
                })
        },
        /**
         * Updates a specification's value in the language that was selected at the time of updating
         * @param specification
         * @return {Promise<void>}
         */
        async updateSpecification(specification) {
            await axios.post(`/api/products/update/${specification.id}/specifications`, {specification: specification, locale: this.selectedLanguage})
                .then(response => {
                    this.toggleNotificationModal()
                    this.getProductSpecifications()
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
@import "../../../../../../styles/sass/modules/pim/products/settings/specifications";
@import "../../../../../../styles/sass/modules/pageParts/tableStyles";

</style>
