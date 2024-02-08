<template>
    <div class="content-container">
        <div class="content">
            <error-modal @toggle-modal="toggleErrorModal" v-if="showErrorModal" :title="$t('users.errorModal.title')">
                {{ this.errors }}
            </error-modal>
            <header class="content__header">
                <div class="content__header-type">
                    <h2>{{ $t('users.pageTitle') }}</h2>
                </div>
                <div class="content__header-search-container">
                    <input class="content__header-search" type="text" v-model="searchQuery" @keyup.enter.prevent="searcher" :placeholder="$t('products.searchInput')">
                    <div class="content__header-search-results" v-if="users !== allUsers">{{ $tChoice('navigation.searchResultAmount', users.length) }}</div>
                </div>
                <button class="content__header-new" v-if="actionRights['user.modify']" @click="$router.push('/manager/user/create')">{{ $t('users.newButton') }}</button>
            </header>
            <confirmation-modal :name="confirmationData.name" v-if="showConfirmationModal" @toggle-modal="toggleConfirmModal" @save-properties="deleteUser"></confirmation-modal>
            <section class="dataSection">
                <table class="dataSection__table">
                    <tr class="dataSection__table-section">
                        <th class="dataSection__table-info">{{ $t('users.nameRow') }}</th>
                        <th class="dataSection__table-info">{{ $t('users.mailRow') }}</th>
                        <th class="dataSection__table-info">{{ $t('users.profileRow') }}</th>
                        <th class="dataSection__table-info" v-if="actionRights['user.modify'] || actionRights['user.delete']">{{ $t('users.actionsRow') }}</th>
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
                    <tr class="dataSection__table-section" v-if="!loading" v-for="user in users" :key="user.id">
                        <td class="dataSection__table-info">{{user.name}} ({{user.username}})</td>
                        <td class="dataSection__table-info">{{user.email}}</td>
                        <td class="dataSection__table-info">{{user.profile.name}}</td>
                        <td class="dataSection__table-info" v-if="actionRights['user.modify'] || actionRights['user.delete']">
                            <div class="dataSection__table-infoIcons">
                                <i class="fas fa-edit actions__pencil" v-if="actionRights['user.modify']" @click="editUser(user)"></i>
                                <i class="fas fa-trash actions__trash" v-if="actionRights['user.delete']" @click="toggleConfirmModal(user)"></i>
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
import ErrorModal from "../../../pageParts/modal/errorModalComponent.vue"
import ConfirmationModal from "../../../pageParts/modal/confirmationModalComponent.vue"

export default {
    name: "userList",
    components: {ConfirmationModal, ErrorModal},
    data() {
        return {
            loading: false,
            users: [],
            showModal: false,
            newName: null,
            newMail: null,
            editedElementId: null,
            searchQuery: null,
            actionRights: [],
            showErrorModal: false,
            errors: null,
            showConfirmationModal: false,
            confirmationData: null,
            allUsers: null,
        }
    },

    async mounted() {
        this.loading = true
        this.getAllusers()
        this.actionRights = Object(await hasRight(['user.modify', 'user.delete']))
        this.loading = false
    },
    methods: {
        /**
         * Filters all users for the item that the user is looking for
         * @return {Promise<[]|*[]>}
         */
        async searcher() {
            if (!this.searchQuery) {
                return this.users = this.allUsers
            }

            return this.users = this.allUsers.filter((item) => {
                return (item.name.includes(this.searchQuery) || item.username.includes(this.searchQuery))
            })
        },
        /**
         * Toggles the modal containing the confirmation for a delete action
         * @param {Object} user
         * @return void
         */
        toggleConfirmModal(user) {
            this.showConfirmationModal = !this.showConfirmationModal
            this.confirmationData = user
        },
        /**
         * Retrieves all users and pushes them into the displayed array
         * @return {Promise<void>}
         */
        getAllusers() {
            axios.get('/api/users/all')
                .then(response => {
                    this.users = response.data
                    this.allUsers = response.data
                })
                .catch(error => {
                    console.error(error)
                })
        },
        /**
         * Pushes the user to the correct edit page
         * @param {Object} user
         * @return void
         */
        editUser(user) {
            this.$router.push(`/manager/user/edit/${user.id}`)
        },
        /**
         * Makes an API call to the back-end and deletes the user
         * @return {Promise<void>}
         */
        deleteUser() {
            const user = this.confirmationData
            this.toggleConfirmModal()
            axios.put(`/api/users/delete/${user.id}`, {user: user})
                .catch(exception => {
                    this.errors = exception.response.data.message
                    this.toggleErrorModal()
                })
            this.getAllusers()
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
@import "../../../../../styles/sass/modules/pageParts/contentContainer";
@import "../../../../../styles/sass/modules/manager/users/userDisplay";
@import "../../../../../styles/sass/modules/pageParts/tableStyles";
</style>
