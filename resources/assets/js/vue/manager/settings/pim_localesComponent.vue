<template>
    <nav class="tabNav">
        <div class="tabNav__item" @click="this.$router.push(`/manager/manager_locales`)">
            {{ $t('navigation.headers.manager_locale') }}
        </div>
        <div class="tabNav__item" @click="this.$router.push(`/manager/pim_locales`)">
            {{ $t('navigation.headers.pim_locale') }}
        </div>
    </nav>
    <div class="content-container">
        <div class="content">
            <notification-modal @toggle-modal="toggleNotificationModal" v-if="showNotificationModal" :title="$t('locales.notificationModal.success')">
                {{ $t('locales.notificationModal.successContent') }}
            </notification-modal>
            <header class="content__header">
                <div class="content__header-type">
                    <h2>{{ $t('locales.pim.title') }}</h2>
                </div>
                <button class="content__header-new" @click="showModal = true">{{ $t('products.newButton') }}</button>
                <regular-modal @toggle-modal="toggleModal" @save-properties="saveLanguage(language.id)" v-if="showModal" :title="$t('locales.pim.modal.title')" :errors="this.errors">
                    <div class="modal__content" role="form" @keyup.enter.prevent="saveLanguage(language.id)">
                        <label for="code">{{ $t('locales.pim.modal.codeLabel') }}</label>
                        <input class="modal__content-input" v-model="language.code" type="text" name="code" required>
                        <label for="name">{{ $t('locales.pim.modal.nameLabel') }}</label>
                        <input class="modal__content-input" v-model="language.name" type="text" name="name" required>
                        <label for="displayClass">{{ $t('locales.pim.modal.displayClassLabel') }}</label>
                        <input class="modal__content-input" v-model="language.displayClass" type="text" name="displayClass" required>
                    </div>
                </regular-modal>
            </header>
            <div class="localeSwitcher">
                <div class="localeSwitcher__switcher">
                    <ul class="localeSwitcher__switcher-list" v-if="languages">
                        <li class="localeSwitcher__switcher-list__item" v-for="language in languages">
                            <div class="localeSwitcher__switcher-list__item-language">
                                {{ language.name }} - {{language.code }}
                            </div>
                            <div class="localeSwitcher__switcher-list__item-toggle">
                                <i class="fas fa-edit actions__pencil" @click="(showModal = true) && (this.language = language) "></i>
                                <i class="fas fa-trash actions__trash" v-if="language.code !== 'nl'" @click="deleteLanguage(language)"></i>
                                <input type="checkbox" v-model="language.enabled" @change="disableLanguage(language)">
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import NotificationModal from "../../pageParts/modal/notificationModalComponent.vue"
import RegularModal from "../../pageParts/modal/RegularModalComponent.vue"

export default {
    name: "pim_locales",
    components: {RegularModal, NotificationModal},
    data() {
        return {
            languages: [],
            showNotificationModal: false,
            showModal: false,
            errors: null,
            language: {
                code: null,
                name: null,
                displayClass: null,
            }
        }
    },
    async mounted() {
        await this.getLanguages()
    },
    methods: {
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
        },
        /**
         * Retrieves all available content locales and saves them in the displayed array
         * @return {Promise<void>}
         */
        async getLanguages() {
            await axios.get('/api/locales/pim/all')
                .then(response => {
                    this.languages = response.data
                })
        },
        /**
         * Updates whether the language is disabled or not, determining whether the user may translate specifications and their values in the language or not
         * @param {Object} language
         * @return {Promise<void>}
         */
        async disableLanguage(language) {
            await axios.post(`/api/locales/pim/${language.id}/disable`, {enabled: language.enabled})
                .then(response => {
                    this.getLanguages()
                    this.toggleNotificationModal()
                })
                .catch(error => {
                    console.error(error)
                })
        },
        /**
         * Makes an API call to the back-end to delete the language
         * @param {Object} language
         * @return {Promise<void>}
         */
        async deleteLanguage(language) {
            await axios.post(`/api/locales/pim/${language.id}/delete`)
                .then(response => {
                    this.getLanguages()
                    this.toggleNotificationModal()
                })
                .catch(error => {
                    console.error(error)
            })
        },
        /**
         * Makes an API call to the back-end to save the language's details.
         * Which API call is made depends on whether the ID of a language is provided.
         * This function is both for creating and for editing a language.
         * @param {Number} languageId
         * @return {Promise<void>}
         */
        async saveLanguage(languageId) {
            let apiUrl = null

            if (languageId) {
                apiUrl = `/api/locales/pim/${languageId}/update`
            } else {
                apiUrl = `/api/locales/pim/save`
            }

            await axios.post(apiUrl, {language: this.language})
                .then(response => {
                    this.toggleModal()
                    this.getLanguages()
                    this.toggleNotificationModal()
                    this.language = {code: null, name: null, displayClass: null, id: null}
                })
                .catch(exceptions => {
                    this.errors = exceptions.response.data
                })
        },
    }
}

</script>

<style scoped lang="scss">
@import "../../../../styles/sass/modules/pageParts/settingsContentContainer";
@import "../../../../styles/sass/modules/manager/settings/manager_locales";
@import "../../../../styles/sass/modules/pim/specifications";
</style>
