<template>
    <nav class="navbar">
        <div class="navbar__hamburger" @click="toggleNav">
            <img class="navbar__hamburger-img" src="/images/logo-white-nobg.svg" alt="{{ $t('navbar.logoAlt') }}">
            <i class="fa-solid fa-bars fa-3x"></i>
        </div>
        <div :class="{ 'content': isOpen || !isMobile, 'contentHidden': !isOpen }">
            <section class="content-left skeleton-loader" v-if="loading"></section>

            <section class="content-left" v-else v-if="!isMobile">
                <router-link class="content__image" to="/">
                    <img class="content__image" src="/images/minipim.jpg" alt="{{ $t('navbar.logoAlt') }}">
                    <div class="content__image__text">
                        <p>Minipim</p>
                        <p>{{ $t("navbar.logoText") }} Intermix</p>
                    </div>
                </router-link>
            </section>

            <section class="content-center skeleton-loader" v-if="loading"></section>

            <section class="content-center" v-else>
                <navbar-settings v-if="navbarType === 'management'"/>
                <navbar-pim v-else/>
            </section>

            <section class="content-right skeleton-loader" v-if="loading"></section>

            <section class="content-right" v-else>
                <ul class="content__navigation">
                    <li class="content__navigation__item" v-if="user.locale && languages.length > 1">
                        <div class="content__navigation-dropdown">
                            {{ $t('navbar.localeDropdown') }}

                            <div class="content__navigation-dropdown-content" v-if="user && !loading">
                                <h2>{{ $t('navbar.localeDropdown') }}</h2>
                                <hr>
                                <div class="dropdown__content-item" v-for="lang in languages">
                                    <p @click="loadLanguage(lang.locale)" v-if="lang.locale !== user.locale">{{ lang.name }}</p>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="content__navigation__item" v-if="hasManagementRights">
                        <div class="content__navigation-dropdown">
                            {{ $t('navbar.moduleDropdown') }}
                            <div class="content__navigation-dropdown-content">
                                <h2>{{ $t('navbar.moduleDropdown') }}</h2>
                                <hr>
                                <p class="dropdown__content-item" @click="switchNav('pim')">{{ $t('navbar.pimModule') }}</p>
                                <p class="dropdown__content-item" @click="switchNav('management')">{{ $t('navbar.managementModule') }}</p>
                            </div>
                        </div>
                    </li>


                    <li class="content__navigation__item">
                        <div class="content__navigation-dropdown">
                            <i class="fas fa-user content__navigation-icon"></i> {{ $t('navbar.accountDropdown') }}
                            <div class="content__navigation-dropdown-content" v-if="user && !loading">
                                <h2>{{user.username}}</h2>
                                <hr>
                                <p class="dropdown__content-item" @click="logout">{{ $t('navbar.logout') }}</p>
                                <router-link class="dropdown__content-item" :class="`navigation__link`" :to="`/manager/user/edit/${user.id}`">
                                    {{ $t('navbar.credentials') }}</router-link>
                            </div>
                        </div>
                    </li>

                </ul>
            </section>
        </div>
    </nav>
</template>

<script>

import navbarPim from "./navbarPimComponent.vue"
import navbarSettings from "./navbarSettingsComponent.vue"
import axios from "axios"
import {hasRight} from "../../composables/rights"
import { useUserStore } from "../../piniaStores/userStore"
import {loadLanguageAsync} from "laravel-vue-i18n"

export default {
    name: 'navbarMain',
    components: {navbarSettings, navbarPim},
    data() {
        return {
            languages: [],
            loading: false,
            isMobile: window.innerWidth <= 767,
            isOpen: false,
            navbarType: localStorage.getItem('navType'),
            user: [],
            hasManagementRights: false,
        }
    },
    async mounted() {
        this.loading = true
        const userStore = useUserStore()
        await userStore.fetchUser()
        this.user = userStore.user

        this.hasManagementRights = await hasRight(['user.view', 'profile.view', 'rights.view'])
        this.hasManagementRights = !Object.values(this.hasManagementRights).every(right => right === false);

        this.languages = (await axios.get('/api/locales/manager/all?enabled=true')).data

        this.loading = false
    },
    methods: {
        // A built-in function to the laravel-vue-i18n package to load in a different language
        loadLanguageAsync,

        /**
         * Loads the language that the user switches to in the locale
         * @param {string} locale
         * @return {Promise<void>}
         */
        async loadLanguage(locale) {
            await loadLanguageAsync(locale)

            const userStore = useUserStore()

            await userStore.updateUserProperty('locale', locale)
            await axios.put(`/api/locales/manager/set`, {locale: locale})

        },
        /**
         * Logs user out in the Pinia store and the back-end
         * @return {Promise<void>}
         */
        logout() {
            useUserStore().logout()
            localStorage.clear()
            axios.post('/api/logout')
                .then(() => {
                    this.$router.push('/login')
                })
                .catch((error => {
                    console.error(error)
                }))
        },
        /**
         * Function solely for mobile usage, allows opening and closing of navigation
         * @return void
         */
        toggleNav() {
            this.isOpen = !this.isOpen
        },
        /**
         * Switches the navigation type between modules
         * @param {String} navType
         */
        switchNav(navType) {
            localStorage.setItem('navType', navType)

            this.navbarType = navType
        },
    }
}
</script>

<style scoped lang="scss">
@import '@fortawesome/fontawesome-pro/css/all.css';
@import "../../../../styles/sass/modules/pageParts/navbar/navbarComponent";
</style>
