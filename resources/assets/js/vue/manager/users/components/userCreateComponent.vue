<template>
    <div class="data-container">
        <div class="content-container">
            <section class="content">
                <notification-modal @toggle-modal="toggleNotificationModal" v-if="showNotificationModal" :title="$t('userEditor.notificationModal.success')">
                    {{ $t('userEditor.notificationModal.successContent') }}
                </notification-modal>
                <error-modal @toggle-modal="toggleErrorModal" v-if="showErrorModal" :title="$t('userEditor.errorModal.title')">
                    {{ this.errors.message }}
                </error-modal>
                <div class="content__header">
                    <i class="fas fa-door-open fa-2x content__header-cancel" @click="$router.back()"></i>
                    <h2 class="content__header-title">{{ $t('userEditor.generalPageHeader') }}</h2>
                    <i class="fas fa-save fa-2x content__header-save" @click="saveUser"></i>
                </div>
                <div class="content__form" role="form" @keyup.enter.prevent="saveUser">
                    <div class="content__form-left">
                        <label for="name">{{ $t('userEditor.nameRow') }}</label>
                        <input class="content__form-input skeleton-loader" v-if="loading">
                        <input class="content__form-input" type="text" name="name" v-model="this.user.name" id="name" v-else>
                        <label for="company">{{ $t('userEditor.companyRow') }}</label>
                        <input class="content__form-input skeleton-loader" v-if="loading">
                        <input class="content__form-input" type="text" name="company" v-model="this.user.company" id="company" v-else>
                        <label for="profile">{{ $t('userEditor.profileRow') }}</label>
                        <input class="content__form-input skeleton-loader" v-if="loading">
                        <select class="content__form-input" v-else v-if="profiles" v-model="this.user.profile_id" name="profile" id="profile">
                            <option class="content__form-selector" v-for="profile in profiles" :value="profile.id">{{ profile.name }}</option>
                        </select>
                        <label for="lang">{{ $t('userEditor.localeRow') }}</label>
                        <input class="content__form-input skeleton-loader" v-if="loading">
                        <select class="content__form-input" v-else v-if="languages" v-model="this.user.locale" name="lang" id="lang">
                            <option class="content__form-selector" v-for="lang in languages" :value="lang.locale">{{ lang.name }}</option>
                        </select>
                        <label for="defaultPage">{{ $t('userEditor.pageRow') }}</label>
                        <input class="content__form-input skeleton-loader" v-if="loading">
                        <select class="content__form-input" v-else v-if="pages" v-model="this.user.defaultPage" name="defaultPage" id="defaultPage">
                            <option class="content__form-selector" v-for="page in pages">{{ page.name }}</option>
                        </select>
                    </div>
                    <div class="content__form-right">
                        <h2>{{ $t('userEditor.credentials') }}</h2> <br>
                        <label for="username">{{ $t('userEditor.usernameRow') }}</label>
                        <input class="content__form-input skeleton-loader" v-if="loading">
                        <input class="content__form-input" type="text" name="username" v-model="this.user.username" id="username" v-else>
                        <label for="email">{{ $t('userEditor.mailRow') }}</label>
                        <input class="content__form-input skeleton-loader" v-if="loading">
                        <input class="content__form-input" type="text" name="email" v-model="this.user.email" id="email" v-else>
                        <label for="password">{{ $t('userEditor.passwordRow') }}</label>
                        <input class="content__form-input skeleton-loader" v-if="loading">
                        <input class="content__form-input" type="password" name="password" v-model="this.user.password" id="password" v-else>
                        <label for="confirmPassword">{{ $t('userEditor.confirmPasswordRow') }}</label>
                        <input class="content__form-input skeleton-loader" v-if="loading">
                        <input class="content__form-input" type="password" name="confirmPassword" v-model="this.user.password_confirmation" id="confirmPassword" v-else>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
import axios from '../../../../../../js/axios.config'
import {loadLanguageAsync} from "laravel-vue-i18n"
import {useUserStore} from "../../../piniaStores/userStore"
import NotificationModal from "../../../pageParts/modal/notificationModalComponent.vue"
import ErrorModal from "../../../pageParts/modal/errorModalComponent.vue"

export default {
    name: "Example",
    components: {ErrorModal, NotificationModal},
    data() {
        return {
            loading: false,
            isEditing: false,
            profiles: [],
            languages: [],
            pages: [],
            user: {
                name: null,
                username: null,
                company: null,
                profile_id: null,
                locale: null,
                defaultPage: null,
                email: null,
                password: null,
                password_confirmation: null,
            },
            errors: null,
            showErrorModal: false,
            showNotificationModal: false
        }
    },
    async mounted() {
        await this.getAllProfiles()
        await this.getAllLocales()
        await this.getAvailablePages()
        if (this.$route.params.id) {
            this.loading = true
            this.isEditing = true
            await this.fetchUserData(this.$route.params.id)
            this.loading = false
        }
    },
    methods: {
        /**
         * Retrieves all available profiles to allow switching a user's profile
         * @return {Promise<void>}
         */
        getAllProfiles() {
            axios.get('/api/profiles/all')
                .then((response) => {
                    this.profiles = response.data
                })
                .catch((error) => {
                    console.error(error)
                })
        },
        /**
         * Retrieves all locale's so that a base locale can be set for the user
         * @return {Promise<void>}
         */
        getAllLocales() {
            axios.get('/api/locales/manager/all')
                .then(response => {
                    this.languages = response.data
                })
                .catch(error => {
                    console.error(error)
                })
        },
        /**
         * Retrieves all pages that don't include an 'exclude' meta property
         * @return {Promise<void>}
         */
        getAvailablePages() {
            this.pages = [].concat(
                ...this.$router.options.routes
                    .filter(route => !route.meta || !route.meta.exclude)
                    .map(route => {
                        if (route.meta && route.meta.exclude) {
                            return [];
                        }
                        let filteredChildren = [];
                        if ((route.path === '/manager' || route.path === '/pim') && (!route.component || route.children)) {
                            filteredChildren = (route.children && route.children.filter(childRoute => !childRoute.meta || !childRoute.meta.exclude)) || [];
                        }
                        return [
                            {
                                ...route,
                                children: filteredChildren
                            },
                            ...(filteredChildren || [])
                        ];
                    })
            );

        },

        /**
         * Depending on the isEditing variable, this checks whether all needed fields have entries, and then makes the relevant API call
         * @return {Promise<void>}
         */
        async saveUser() {
            if (!this.isEditing) {
                axios.put('/api/users/save', this.user)
                    .then(response => {
                        this.toggleNotificationModal()
                    })
                    .catch(exceptions => {
                        this.errors = exceptions.response.data
                        this.toggleErrorModal()
                    })
            } else {
                const userStore = useUserStore()
                if (userStore.user.id === this.$route.params.id) {
                    await loadLanguageAsync(this.user.locale)

                    await userStore.updateUser(this.user)
                        .then(response => {
                            this.toggleNotificationModal()
                        })
                        .catch(exceptions => {
                            this.errors = exceptions.response.data
                            this.toggleErrorModal()
                        })
                } else {
                    axios.put(`/api/users/save/${this.$route.params.id}`, this.user)
                        .then(response => {
                            this.toggleNotificationModal()
                        })
                        .catch(exceptions => {
                            this.errors = exceptions.response.data
                            this.toggleErrorModal()
                        })
                }
            }
        },
        /**
         * This fetches the given user's data and displays it in the input fields for optimized updating experience
         * @param {Number} user_id
         * @return {Promise<void>}
         */
        fetchUserData(user_id) {
            axios.get(`/api/user/${user_id}`)
                .then((response => {
                    this.user = response.data
                }))
                .catch((error => {
                    console.error(error)
                }))
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
        }
    }
}

</script>

<style scoped lang="scss">
@import "../../../../../styles/sass/modules/manager/users/userCreate";
</style>
