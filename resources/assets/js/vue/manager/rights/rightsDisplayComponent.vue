<template>
    <div class="content-container">
        <div class="content">
            <notification-modal @toggle-modal="toggleNotificationModal" v-if="showNotificationModal" :title="$t('rights.notificationModal.success')">
                {{ $t('rights.notificationModal.successContent') }}
            </notification-modal>
            <header class="content__header">
                <div class="content__header-type">
                    <h2>{{ $t('rights.pageTitle') }}</h2>
                </div>
                <div class="content__header-search-container">
                    <input class="content__header-search" type="text" v-model="searchQuery" @keyup.enter.prevent="searcher" :placeholder="$t('products.searchInput')">
                    <div class="content__header-search-results" v-if="rights !== allRights">{{ $tChoice('navigation.searchResultAmount', rights.length) }}</div>
                </div>
            </header>
            <section class="userData">
                <div class="userData__container">
                    <div class="userData__container__rights">
                        <h3>{{ $t('rights.rightTitle') }}</h3>
                        <p class="userData__container__rights-item skeleton-loader" v-if="loading"></p>
                        <p class="userData__container__rights-item" v-else v-for="right in rights">{{right.name}}</p>
                    </div>

                    <div class="userData__container__profiles">
                        <div class="userData__container__profiles-profile" v-if="loading" v-for="item in 3">
                            <h2 class="skeleton-loader"></h2>
                            <div class="profile__right">
                                <input class="skeleton-loader">
                            </div>
                        </div>

                        <div class="userData__container__profiles-profile" v-else v-for="profile in profiles">
                            <h2>{{profile.name}}</h2>
                            <div class="profile__right" role="form" v-for="right in profile.rights" :key="profile.rights.id">
                                <input class="profile__right-input" type="checkbox" name="rightCheck" v-model="right.right_assigned" @change="updateRight(profile, right)" :disabled="!actionRights['rights.modify'] || this.user.profile_id == profile.id">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import {hasRight} from "../../composables/rights"
import NotificationModal from "../../pageParts/modal/notificationModalComponent.vue"
import {useUserStore} from "../../piniaStores/userStore"
import {useRightsStore} from "../../piniaStores/rightsStore";


export default {
    name: "rightsDisplay",
    components: {NotificationModal},
    data() {
        return {
            loading: false,
            profiles: [],
            allProfiles: [],
            rights: [],
            allRights: [],
            searchQuery: null,
            actionRights: [],
            showNotificationModal: false,
            user: null,
        }
    },
    async mounted() {
        this.loading = true
        const userStore = useUserStore()
        await userStore.fetchUser()
        this.user = userStore.user
        await this.profileWithRights()
        await this.getAllRights()
        this.actionRights = Object(await hasRight(['rights.modify']))
        this.loading = false
    },
    methods: {
        /**
         * This function filters through the rights for the right that the user wants and pushes the results into the displayed array
         * @return {Promise<void>}
         */
        async searcher() {
            if (!this.searchQuery) {
                this.rights = this.allRights
                this.profiles = [...this.allProfiles]
                return
            }

            this.rights = this.allRights.filter((item) => {
                return (item.name.includes(this.searchQuery) || item.category.includes(this.searchQuery))
            })

            this.profiles = this.allProfiles.map(profile => {
                return {
                    ...profile,
                    rights: profile.rights.filter(right => right.name.includes(this.searchQuery))
                }
            })
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
         * Retrieves all profiles and their respective rights and saves them in the displayed array and back-up array
         * @return {Promise<void>}
         */
        async profileWithRights() {
            if (await useRightsStore().getRights === null) {
                await useRightsStore().fetchRights()
            }
            const response = await useRightsStore().getRights
            this.profiles = response
            this.allProfiles = response
        },
        /**
         * Fetches all available rights that the application contains and saves them in the displayed array and back-up array
         * @return {Promise<void>}
         */
        getAllRights() {
            axios.get('/api/rights/all')
                .then(response => {
                    this.rights = response.data
                    this.allRights = response.data
                })
        },
        /**
         * Submits an API call to the back-end to save the new value of the right for the given profile.
         * @param {Object} profile
         * @param {Object} right
         * @return {Promise<void>}
         */
        async updateRight(profile, right) {
            await useRightsStore().updateRight(profile.id, right.id, right.right_assigned)
            axios.post('/api/profileRights/save', {profile: profile.id, right_id: right.id, right_value: right.right_assigned})
                .then(response => {
                    this.toggleNotificationModal()
                })
                .catch(error => {
                    console.error(error)
                })
        }
    }
};
</script>

<style scoped lang="scss">
@import "../../../../styles/sass/modules/pageParts/contentContainer";
@import "../../../../styles/sass/modules/manager/rights/rightsDisplay";
</style>
