<template>
    <div class="content-container">
        <div class="content">
            <notification-modal @toggle-modal="toggleNotificationModal" v-if="showNotificationModal" :title="$t('profiles.notificationModal.success')">
                {{ $t('profiles.notificationModal.successContent') }}
            </notification-modal>
            <error-modal @toggle-modal="toggleErrorModal" v-if="showErrorModal" :title="$t('profiles.errorModal.title')">
                {{ this.errors.message }}
            </error-modal>
            <header class="content__header">
                <div class="content__header-type">
                    <h2>{{ $t('profiles.pageTitle') }}</h2>
                </div>
                <div class="content__header-search-container">
                    <input class="content__header-search" type="text" v-model="searchQuery" @keyup.enter.prevent="searcher" :placeholder="$t('products.searchInput')">
                    <div class="content__header-search-results" v-if="profiles !== allProfiles">{{ $tChoice('navigation.searchResultAmount', profiles.length) }}</div>
                </div>
                <button class="content__header-new" v-if="actionRights['profile.modify']" @click="showModal = true">{{ $t('profiles.newButton') }}</button>
            </header>
            <regular-modal @toggle-modal="toggleModal" @save-properties="createProfile" v-if="showModal" :title="$t('profiles.newModalTitle')" :errors="this.errors">
                <div class="modal__content" role="form" @keyup.enter.prevent="createProfile">
                    <div class="modal__content-formPart">
                        <label for="name">{{ $t('profiles.newModalName') }}</label>
                        <input class="modal__content-input" v-model="name" type="text" name="name" required>
                    </div>
                </div>
            </regular-modal>
            <confirmation-modal :name="confirmationData.name" v-if="showConfirmationModal" @toggle-modal="toggleConfirmModal" @save-properties="deleteProfile"></confirmation-modal>
            <section class="dataSection">
                <table class="dataSection__table">
                    <tr class="dataSection__table-section">
                        <th class="dataSection__table-info">{{ $t('profiles.idRow') }}</th>
                        <th class="dataSection__table-info">{{ $t('profiles.nameRow') }}</th>
                        <th class="dataSection__table-info" v-if="actionRights['profile.modify'] || actionRights['profile.delete']">{{ $t('profiles.actionRow') }}</th>
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
                    <tr class="dataSection__table-section" v-else v-for="profile in profiles" :key="profile.id">
                        <td class="dataSection__table-info">{{profile.id}}</td>
                        <td class="dataSection__table-info" v-if="editedElementId === profile.id">
                            <input class="dataSection__table-edit" v-model="newName" @keyup.enter.prevent="saveProfile(profile)">
                        </td>
                        <td class="dataSection__table-info" v-else>{{profile.name}}</td>
                        <td class="dataSection__table-info" v-if="actionRights['profile.modify'] || actionRights['profile.delete']">
                            <div class="dataSection__table-infoIcons">
                                <i v-if="editedElementId !== profile.id && actionRights['profile.modify']" class="fas fa-edit actions__pencil" @click="toggleEditState(profile)"></i>
                                <i v-else v-if="actionRights['profile.modify']" class="fas fa-save actions__save" @click="saveProfile(profile)"></i>
                                <i class="fas fa-trash actions__trash" v-if="actionRights['profile.delete']" @click="toggleConfirmModal(profile)"></i>
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
            name: null,
            loading: false,
            profiles: [],
            showModal: false,
            newName: null,
            editedElementId: null,
            searchQuery: null,
            actionRights: [],
            errors: null,
            showNotificationModal: false,
            showErrorModal: false,
            showConfirmationModal: false,
            confirmationData: null,
            allProfiles: [],
        }
    },
    async mounted() {
        this.loading = true
        this.getAllProfiles()
        this.actionRights = Object(await hasRight(['profile.modify', 'profile.delete']))
        this.loading = false
    },
    methods: {
        /**
         * Filters all profiles for the item that the user is looking for
         * @return {Promise<[]|*[]>}
         */
        async searcher() {
            if (!this.searchQuery) {
                return this.profiles = this.allProfiles
            }

            return this.profiles = this.allProfiles.filter((item) => {
                return (item.name.includes(this.searchQuery))
            })
        },
        /**
         * Retrieves all saved profiles to display these on the list
         * @return {Promise<void>}
         */
        getAllProfiles() {
            axios.get('/api/profiles/all')
                .then(response => {
                    this.profiles = response.data
                    this.allProfiles = response.data
                })
                .catch(error => {
                    console.error(error)
                })
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
         * Toggles the modal containing the confirmation for a delete action
         * @param {Object} profile
         * @return void
         */
        toggleConfirmModal(profile) {
            this.showConfirmationModal = !this.showConfirmationModal
            this.confirmationData = profile
        },
        /**
         * Toggle whether a profile is being modified or created, determining the data sent to the back-end
         * @param profile
         * @return void
         */
        toggleEditState(profile) {
            if (this.editedElementId === profile.id) {
                this.saveProfile(profile)
            } else {
                this.editedElementId = profile.id
                this.newName = profile.name
            }
        },
        /**
         * When a privileged user updates a profile on the client, an API call is made to update it
         * @param {Object} profile
         * @return {Promise<void>}
         */
        saveProfile(profile) {
            axios.put(`/api/profiles/save/${profile.id}`, { name: this.newName })
                .then(response => {
                    profile.name = this.newName
                    this.editedElementId = null
                    this.toggleNotificationModal()
                })
                .catch(error => {
                    this.errors = error.response.data
                    this.toggleErrorModal()
                })
        },
        /**
         * When a privileged user deletes a profile on the client, an API call is made to delete it, and profiles are newly retrieved
         * @return {Promise<void>}
         */
        deleteProfile() {
            const profile = this.confirmationData
            this.confirmationData = null
            this.showConfirmationModal = false
            axios.put(`/api/profiles/delete/${profile.id}`)
                .then(() => {
                    this.getAllProfiles()
                })
                .catch(exception => {
                    this.errors = exception.response.data
                    this.toggleErrorModal()
                })
        },
        /**
         * When a user creates a new profile, an API call is sent to the back-end.
         * A successful response will close the form modal, retrieve all profiles again and display the success notification modal
         * @return void
         */
        createProfile() {
            axios.post('/api/profiles/save', {name: this.name})
                .then((response => {
                    this.toggleModal()
                    this.getAllProfiles()
                    this.toggleNotificationModal()
                }))
                .catch((error => {
                    console.error(error)
                    this.errors = error.response.data
                }))
        },

    }
}
</script>

<style scoped lang="scss">
@import "../../../../../styles/sass/modules/pageParts/contentContainer";
@import "../../../../../styles/sass/modules/pageParts/tableStyles";
@import "../../../../../styles/sass/modules/manager/profiles/profileDisplay";
</style>
