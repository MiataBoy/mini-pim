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
                    <h2>{{ $t('locales.manager.title') }}</h2>
                </div>
            </header>
            <div class="localeSwitcher">
                <div class="localeSwitcher__switcher">
                    <ul class="localeSwitcher__switcher-list" v-if="languages">
                        <li class="localeSwitcher__switcher-list__item" v-for="language in languages">
                            <div class="localeSwitcher__switcher-list__item-language">
                                {{ language.name }} - {{language.locale }}
                            </div>
                            <div class="localeSwitcher__switcher-list__item-toggle">
                                <input type="checkbox" v-model="language.enabled" @change="updateLanguage(language)">
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

export default {
    name: "manager_locales",
    components: {NotificationModal},
    data() {
        return {
            languages: [],
            showNotificationModal: false,
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
         * Retrieves all available manager locales and saves them in the displayed array
         * @return {Promise<void>}
         */
        async getLanguages() {
            await axios.get('/api/locales/manager/all')
                .then(response => {
                    this.languages = response.data
                })
        },
        /**
         * Updates whether the language is disabled or not, determining whether the user may see the application in the language or not
         * @param {Object} language
         * @return {Promise<void>}
         */
        async updateLanguage(language) {
            await axios.post(`/api/locales/manager/${language.id}/disable`, {enabled: language.enabled})
                .then(response => {
                    this.toggleNotificationModal()
                })
                .catch(error => {
                    console.error(error)
                })
        }
    }
}

</script>

<style scoped lang="scss">
@import "../../../../styles/sass/modules/pageParts/settingsContentContainer";
@import "../../../../styles/sass/modules/manager/settings/manager_locales";
</style>
