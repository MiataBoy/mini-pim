<template>
    <div class="body" v-if="shouldDisplayNavbar">
        <div class="body-container">
            <navbar></navbar>
            <router-view></router-view>
        </div>
    </div>
    <div v-else>
        <router-view></router-view>
    </div>
</template>

<script>
import navbar from "../assets/js/vue/pageParts/navbar/navbarComponent.vue"
import {useUserStore} from "../assets/js/vue/piniaStores/userStore";
import {loadLanguageAsync} from "laravel-vue-i18n";

export default {
    name: 'app',
    components: {
        navbar,
    },
    data() {
        return {
            shouldDisplayNavbar: false,
        };
    },
    watch: {
        async $route(to, from) {
            this.shouldDisplayNavbar = to.name !== 'Login';
            if (this.shouldDisplayNavbar && useUserStore().user) {
                await useUserStore().fetchUser()
                await loadLanguageAsync(useUserStore().user.locale)
            }
        },
    },
};
</script>
