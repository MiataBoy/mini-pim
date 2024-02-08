<template>
    <ul class="content__navigation content__navigation-center">
        <li class="navigation__item" v-if="pageRights['user.view']">
            <router-link class="navigation__link" to="/manager/users">
                {{ $t('navbar.usersRedirect') }}
            </router-link>
        </li>

        <li class="navigation__item" v-if="pageRights['profile.view']">
            <router-link class="navigation__link" to="/manager/profiles">
                {{ $t('navbar.profilesRedirect') }}
            </router-link>
        </li>

        <li class="navigation__item" v-if="pageRights['rights.view']">
            <router-link class="navigation__link" to="/manager/rights">
                {{ $t('navbar.rightsRedirect') }}
            </router-link>
        </li>

        <li class="navigation__item" v-if="settingsPage">
            <router-link class="navigation__link" :to="settingsPage">
                Settings
            </router-link>
        </li>
    </ul>
</template>

<script>
import { hasRight } from "../../composables/rights"


export default {
    name: 'navbarSettings',
    data() {
        return {
            pageRights: [],
            settingsPage: null,
        }
    },
    async mounted() {
        this.pageRights = Object(await hasRight(['user.view', 'profile.view', 'rights.view', 'manager_locales.view', 'pim_locales.view']))

        const settingsPages = ['manager_locales', 'pim_locales']

        let availablePages = []

        for (const right in settingsPages) {
            if (this.pageRights[settingsPages[right] + ".view"]) {
                availablePages.push(settingsPages[right])
            }
        }

        if (availablePages.length) {
                this.settingsPage = `/manager/${availablePages[0]}`
            } else {
                this.settingsPage = false
            }
        }
}
</script>

<style scoped lang="scss">
@import '@fortawesome/fontawesome-pro/css/all.css';
@import "../../../../styles/sass/modules/pageParts/navbar/navbarComponent";
</style>
