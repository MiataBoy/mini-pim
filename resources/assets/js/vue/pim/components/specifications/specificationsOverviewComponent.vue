<template>
    <div class="content-container">
        <div class="content">
            <notification-modal @toggle-modal="toggleNotificationModal" v-if="showNotificationModal" :title="$t('specifications.notificationModal.success')">
                {{ $t('specifications.notificationModal.successContent') }}
            </notification-modal>
            <confirmation-modal :name="confirmationData.translations[0].name" v-if="showConfirmationModal" @toggle-modal="toggleConfirmModal" @save-properties="deleteSpecification"></confirmation-modal>
            <error-modal @toggle-modal="toggleErrorModal" v-if="showErrorModal" :title="$t('specifications.errorModal.title')">
                {{ this.errors.message }}
            </error-modal>
            <header class="content__header">
                <div class="content__header-type">
                    <h2>{{ $t('specifications.pageTitle') }}</h2>
                </div>
                <div class="content__header-search-container">
                    <input class="content__header-search" type="text" v-model="searchQuery" @keyup.enter.prevent="searcher" :placeholder="$t('products.searchInput')">
                    <div class="content__header-search-results" v-if="specifications !== allSpecifications">{{ $tChoice('navigation.searchResultAmount', specifications.length) }}</div>
                </div>
                <button class="content__header-new" v-if="actionRights['spec.modify']" @click="showModal = true">{{ $t('specifications.newButton') }}</button>
            </header>
            <regular-modal @toggle-modal="toggleModal" @save-properties="saveSpecification(specification.id)" v-if="showModal" :title="$t('specifications.newModal.title')" :errors="this.errors">
                <div class="langSelector" v-if="specification.id">
                    <select v-model="selectedLanguage" @change="setLanguage(selectedLanguage); fillData(specification)">
                        <option v-for="language in pimLanguages" :value="language.code">{{ language.code }}</option>
                    </select>
                </div>
                <div class="modal__content" role="form" @keyup.enter.prevent="saveSpecification(specification.id)">
                    <label for="name">{{ $t('specifications.newModal.nameInput') }}</label>
                    <input class="modal__content-input" v-model="specification.name" type="text" name="name" required>
                    <label for="type">{{ $t('specifications.newModal.typeInput') }}</label>
                    <select class="modal__content-input" v-model="specification.inputType" name="type" required>
                        <option class="modal__content-input__selector" v-for="type in inputTypes" :value="type.id">{{type.id}}</option>
                    </select>
                    <label for="description">{{ $t('specifications.newModal.descInput') }}</label>
                    <textarea class="modal__content-textarea" v-model="specification.description" name="description"></textarea>
                </div>
            </regular-modal>
            <section class="dataSection">
                <table class="dataSection__table">
                    <tr class="dataSection__table-section">
                        <th class="dataSection__table-info">{{ $t('specifications.nameRow') }}</th>
                        <th class="dataSection__table-info">{{ $t('specifications.typeRow') }}</th>
                        <th class="dataSection__table-info" v-if="actionRights['spec.modify'] || actionRights['spec.delete']">{{ $t('specifications.actionsRow') }}</th>
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
                    <tr class="dataSection__table-section" v-else v-for="specification in specifications" :key="specification.id">
                        <td class="dataSection__table-info">{{specification.translations[0].name}}</td>
                        <td class="dataSection__table-info">{{specification.input_type.id}}</td>
                        <td class="dataSection__table-info" v-if="actionRights['spec.modify'] || actionRights['spec.delete']">
                            <div class="dataSection__table-infoIcons">
                                <i  class="fas fa-edit actions__pencil" v-if="actionRights['spec.modify']" @click="fillData(specification)"></i>
                                <i class="fas fa-trash actions__trash" v-if="actionRights['spec.delete']" @click="toggleConfirmModal(specification)"></i>
                            </div>
                        </td>
                    </tr>
                </table>
            </section>
        </div>
    </div>
</template>

<script>
import axios from '../../../../../../js/axios.config'
import {hasRight} from "../../../composables/rights"
import RegularModal from "../../../pageParts/modal/RegularModalComponent.vue"
import NotificationModal from "../../../pageParts/modal/notificationModalComponent.vue"
import ErrorModal from "../../../pageParts/modal/errorModalComponent.vue"
import ConfirmationModal from "../../../pageParts/modal/confirmationModalComponent.vue"

export default {
    name: "profileDisplay",
    components: {ConfirmationModal, ErrorModal, NotificationModal, RegularModal},
    data() {
        return {
            specification: {
                name: null,
                inputType: null,
                description: null,
            },
            loading: false,
            specifications: [],
            inputTypes: [],
            showModal: false,
            searchQuery: '',
            actionRights: [],
            errors: null,
            showNotificationModal: false,
            showErrorModal: false,
            pimLanguages: null,
            selectedLanguage: 'nl',
            confirmationData: null,
            showConfirmationModal: false,
            allSpecifications: [],
        }
    },
    async mounted() {
        this.loading = true
        this.actionRights = Object(await hasRight(['spec.modify', 'spec.delete']))
        await this.getInputTypes()
        this.selectedLanguage = 'nl'
        await this.getLanguages()
        await this.getSpecifications()
        this.loading = false
    },
    methods: {
        /**
         * Filters through the back-up array for the item that the user is searching for and pushes the results to the display array
         * @return {Promise<[]|*[]>}
         */
        async searcher() {
            if (!this.searchQuery) {
                return this.specifications = this.allSpecifications
            }

            return this.specifications = this.allSpecifications.filter((item) => {
                return (item.translations[0].name.includes(this.searchQuery))
            })
        },
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
         * Fills the editing modal data
         * @param {Object} specification
         * @return void
         */
        async fillData(specification) {
            await axios.post(`/api/specifications/get/${specification.id}`, {locale: this.selectedLanguage})
                .then(response => {
                    specification = response.data
                })

            this.specification = {
                id: specification.id,
                name: specification.translations[0] === undefined ? null : specification.translations[0].name ,
                inputType: specification.inputType_id,
                description: specification.translations[0] === undefined ? null : specification.translations[0].description
            }


            this.showModal = true
        },
        /**
         * Toggles the modal containing the creation form
         * @return void
         */
        toggleModal() {
            this.showModal = !this.showModal
            this.errors = null
            this.name = null
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
         * Toggles the modal containing errors that may present themselves during API calls
         * @return void
         */
        toggleErrorModal() {
            this.showErrorModal = !this.showErrorModal
        },
        /**
         * Retrieves all specifications and pushes them to the display array
         * @return {Promise<void>}
         */
        async getSpecifications() {
            await axios.get(`/api/specifications/all?lang=${this.selectedLanguage}`)
                .then(response => {
                    this.specifications = response.data
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

                    this.allSpecifications = this.specifications
                })
                .catch(error => {
                    console.error(error)
                })
        },
        /**
         * Gets all available input types for the specification values
         * @return {Promise<void>}
         */
        async getInputTypes() {
            await axios.get('/api/specifications/inputs/all')
                .then(response => {
                    this.inputTypes = response.data
                })
                .catch(error => {
                    console.error(error)
                })
        },
        /**
         * Deletes a specification from the database
         * @return {Promise<void>}
         */
        async deleteSpecification() {
            const specification = this.confirmationData
            await axios.post(`/api/specifications/delete/${specification.id}`)
                .then(() => {
                    this.toggleConfirmModal()
                    this.getSpecifications()
                    this.toggleNotificationModal()
                })
                .catch(error => {
                    console.error(error)
                })
        },
        /**
         * Saves or updates a specification in the database
         * @param {Number} specificationId
         * @return {Promise<void>}
         */
        async saveSpecification(specificationId) {
            let apiUrl = null

            if (specificationId) {
                apiUrl = `/api/specifications/${specificationId}/update`
            } else {
                apiUrl = `/api/specifications/save`
            }

            await axios.post(apiUrl, {specification: this.specification, locale: specificationId ? this.selectedLanguage : 'nl'})
                .then(() => {
                    this.getSpecifications()
                    this.toggleModal()
                    this.toggleNotificationModal()
                    this.specification = {name: null, inputType: null, description: null}
                    this.setLanguage('nl')
                })
                .catch(error => {
                    this.errors = error.response.data
                })
        },
        /**
         * Gets all available languages for specification translations
         * @return {Promise<void>}
         */
        async getLanguages() {
            await axios.get(`/api/locales/pim/all`)
                .then(response => {
                    this.pimLanguages = response.data
                })
        },
        /**
         * Sets the language in which the specification translation has to be saved
         * @param {Object} language
         */
        setLanguage(language) {
            localStorage.setItem('pimLang', language)
            this.selectedLanguage = language
        }
    }
}
</script>

<style scoped lang="scss">
@import "../../../../../styles/sass/modules/pageParts/contentContainer";
@import "../../../../../styles/sass/modules/pageParts/tableStyles";
@import "../../../../../styles/sass/modules/pim/specifications";
</style>
