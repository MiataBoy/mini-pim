<template>
    <div class="main-container">
        <div class="container">
            <IPLogin v-if="this.isIntermixIP" :users=users />
            <FormLogin v-else/>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import FormLogin from "./FormLoginComponent.vue"
import IPLogin from "./IPLoginComponent.vue"

export default {
    name: "login",
    components: {FormLogin, IPLogin},
    data() {
        return {
            isIntermixIP: null,
            users: [],
        }
    },
    async mounted() {
        await axios.get('api/ip-check')
            .then(response => {
                this.isIntermixIP = response.data;
            })
            .catch(error => {
                console.error(error)
            })
        if (this.isIntermixIP) {
            const users = await axios.get('api/users/all')
            this.users = users.data
        }
    },
}

</script>

<style scoped lang="scss">
@import "../../../../../styles/sass/modules/pim/login/loginContent";
</style>
