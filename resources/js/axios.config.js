import axios from 'axios';
import { useUserStore } from '../assets/js/vue/piniaStores/userStore';

const userStore = useUserStore();

const instance = axios.create();

/**
 * When making a request with Axios to an API url that may return an error or similarly a response that has multiple translations,
 * You should always import axios from this file (axios.config.js) so that the user locale is passed with the request.
 * This way, the user always sees errors/responses  from the back-end in the language they have selected.
 */

instance.interceptors.request.use((config) => {
    config.headers['Accept-Language'] = userStore.user.locale;
    return config;
});

export default instance;
