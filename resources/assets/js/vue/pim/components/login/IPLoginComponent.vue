<template>
    <div class="form" role="form" @submit.prevent="login()">
        <img class="form__logo" alt="Minipim logo" src="/images/minipim.jpg">
        <label for="user">Gebruiker</label>
        <select class="form__select" v-model="user" id="username">
            <option class="form__input-option" v-for="user in users" :value="user" :key="user.id">{{user.username}}</option>
        </select>
        <p class="form__input-error" v-if="missingUser">Selecteer een gebruiker.</p>

        <button class="form__submit" @click="login()" type="submit">Inloggen</button>
    </div>
</template>

<script>
import axios from 'axios'
import {useUserStore} from "../../../piniaStores/userStore"

export default {
    name: 'IPLogin',
    props: ['users'],
    data() {
        return {
            user: null,
            missingUser: false,
        }
    },
    methods: {
        /**
         * Logs the user in through the user ID, bypassing authentication
         * @return {Promise<void>}
         */
        async login() {
            await axios.get('/sanctum/csrf-cookie', {
                withCredentials: true
            })

            if (this.user !== null) {
                await axios.post('/api/login', {
                    userId: this.user.id,
                })
                    .then(async (response) => {
                        await useUserStore().updateUser(response.data.user)
                        console.log(`userstore: ${useUserStore().user}`)
                        localStorage.setItem('navType', 'pim')
                        return this.$router.push('/')
                    })
            } else {
                this.missingUser = true;
            }
        },
    },
}
</script>

<style scoped lang="scss">
@import "../../../../../styles/sass/modules/pim/login/loginComponent";
</style>
