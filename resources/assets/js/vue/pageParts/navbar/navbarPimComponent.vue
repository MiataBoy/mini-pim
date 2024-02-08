<template>
    <ul class="content__navigation content__navigation-center">
        <li class="navigation__item" v-if="pageRights['product.view']">
            <router-link class="navigation__link" to="/pim/products">
                {{ $t('navbar.productsRedirect') }}
            </router-link>
        </li>

        <li class="navigation__item" v-if="pageRights['group.view']">
            <router-link class="navigation__link" to="/pim/groups">
                {{ $t('navbar.groupsRedirect') }}
            </router-link>
        </li>

        <li class="navigation__item"  v-if="pageRights['spec.view']">
            <router-link class="navigation__link" to="/pim/specifications">
                {{ $t('navbar.specsRedirect') }}
            </router-link>
        </li>
    </ul>
</template>

<script>

import {hasRight} from "../../composables/rights"

export default {
    name: 'navbarPim',
    data() {
        return {
            pageRights: []
        }
    },
    async mounted() {
        this.pageRights = Object(await hasRight(['product.view', 'group.view', 'spec.view']))
    }
};
</script>

<style scoped lang="scss">
@import '@fortawesome/fontawesome-pro/css/all.css';
@import "../../../../styles/sass/modules/pageParts/navbar/navbarComponent";
</style>
