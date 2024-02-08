<template>
    <div class="form" role="form">
        <error-modal @toggle-modal="toggleErrorModal" v-if="showErrorModal" :title="$t('userEditor.errorModal.title')">
            {{ this.errors.message === 'unauthenticated' ? $t('passwords.wrongDetails') : this.errors.message }}
        </error-modal>
        <img class="form__logo" alt="Minipim logo" src="/images/minipim.jpg">
        <label for="username">Gebruikersnaam</label>
        <input class="form__input" v-model="username" type="text" placeholder="Gebruikersnaam" id="username" required>
        <label for="password">Wachtwoord</label>
        <input class="form__input" v-model="password" type="password" placeholder="Wachtwoord" id="password" required>
        <div class="form__input-error" v-if="errors">
            <p>Vul alstublieft de volgende velden in:</p>
            <p>{{Object.keys(errors).join(", ")}}</p>
        </div>
        <button class="form__submit" @click="login()" type="submit">Inloggen</button>

        <router-link to="/" exact class="form__link">
            Wachtwoord vergeten
        </router-link>
    </div>
</template>

<script>
import axios from 'axios'
import {useUserStore} from "../../../piniaStores/userStore"
import ErrorModal from "../../../pageParts/modal/errorModalComponent.vue";

export default {
    name: 'FormLogin',
    components: {ErrorModal},
    data() {
        return {
            username: null,
            password: null,
            errors: null,
            showErrorModal: false,
        };
    },

    methods: {
        /**
         * Toggles the modal containing errors that may present themselves during API calls
         * @return void
         */
        toggleErrorModal() {
            this.showErrorModal = !this.showErrorModal
        },
        /**
         * Attempts to authenticate the user using the credentials they provided
         * @return {Promise<void>}
         */
        async login() {

            await axios.get('/sanctum/csrf-cookie', {
                withCredentials: true
            })

            axios.post('/api/login', {
                username: this.username,
                password: this.password,
            })
                .then(() => {
                    useUserStore().fetchUser()
                    localStorage.setItem('navType', 'pim')
                    this.$router.push('/')
                })
                .catch(exceptions => {
                    this.errors = exceptions.response.data.errors
                })
        },
    },
}
</script>

<style scoped lang="scss">
@import "../../../../../styles/sass/modules/pim/login/loginComponent";
</style>
