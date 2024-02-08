<template>
    <div class="content-container">
        <div class="content">
            <header class="content__header">
                <div class="content__header-type">
                    <h2>{{ $t('products.pageTitle') }}</h2>
                </div>
                <div class="content__header-search-container">
                    <input class="content__header-search" type="text" v-model="searchQuery" @keyup.enter.prevent="searcher" :placeholder="$t('products.searchInput')">
                    <div class="content__header-search-results" v-if="products !== allProducts">{{ $tChoice('navigation.searchResultAmount', products.length) }}</div>
                </div>

<!--                <input class="content__header-search" type="text" v-model="searchQuery" @keyup.enter.prevent="searcher" :placeholder="$t('products.searchInput')">-->
                <button class="content__header-new" @click="showModal = true">{{ $t('products.newButton') }}</button>
                <regular-modal @toggle-modal="toggleModal" @save-properties="saveProduct" v-if="showModal" :title="$t('products.newModalTitle')" :errors="this.errors">
                    <div class="modal__content" role="form" @keyup.enter.prevent="saveProduct">
                        <div class="modal__content-formPart">
                            <label for="id">{{ $t('products.idRow') }}</label>
                            <input class="modal__content-input" v-model="product.id" type="text" name="id" required>
                        </div>
                    </div>
                </regular-modal>
                <confirmation-modal :name="confirmationData.id" v-if="showConfirmationModal" @toggle-modal="toggleConfirmModal" @save-properties="deleteProduct"></confirmation-modal>
            </header>
            <section class="dataSection">
                <table class="dataSection__table">
                    <tr class="dataSection__table-section">
                        <th class="dataSection__table-info">{{ $t('products.idRow') }}</th>
                        <th class="dataSection__table-info">{{ $t('products.thumbRow') }}</th>
                        <th class="dataSection__table-info">{{ $t('products.imageCountRow') }}</th>
                        <th class="dataSection__table-info" v-if="actionRights['product.modify'] || actionRights['product.delete']">{{ $t('products.actionsRow') }}</th>
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
                    <tr class="dataSection__table-section" v-if="!loading && products" v-for="product in products" :key="product.id">
                        <td class="dataSection__table-info noImagePadding">{{ product.id }}</td>
                        <td class="dataSection__table-info noImagePadding"><img v-if="product.assets.length" class="dataSection__table-info__image" :src="'/storage/'+product.assets[0].file_path" alt="ye"></td>
                        <td class="dataSection__table-info noImagePadding">{{product.assets.length }}</td>
                        <td class="dataSection__table-info noImagePadding" v-if="actionRights['product.modify'] || actionRights['product.delete']">
                            <div class="dataSection__table-infoIcons noImagePadding">
                                <i class="fas fa-edit actions__pencil" v-if="actionRights['product.modify']" @click="editProduct(product)"></i>
                                <i class="fas fa-trash actions__trash" v-if="actionRights['product.delete']" @click="toggleConfirmModal(product)"></i>
                            </div>
                        </td>
                    </tr>
                </table>
            </section>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import {hasRight} from "../../../composables/rights"
import RegularModal from "../../../pageParts/modal/RegularModalComponent.vue"
import ConfirmationModal from "../../../pageParts/modal/confirmationModalComponent.vue"

export default {
    name: "productOverview",
    components: {ConfirmationModal, RegularModal},
    data() {
        return {
            loading: false,
            products: [],
            product: {id: null, article_group: false},
            actionRights: [],
            showModal: false,
            errors: null,
            showConfirmationModal: false,
            confirmationData: null,
            allProducts: [],
            searchQuery: null,
        }
    },
    async mounted() {
        this.loading = true

        this.getProducts()
        this.actionRights = Object(await hasRight(['product.modify', 'product.delete']))

        this.loading = false
    },
    methods: {
        /**
         * Filters through the back-up array for the item that the user is searching for and pushes the results to the display array
         * @return {Promise<[]|*[]>}
         */
        async searcher() {
            if (!this.searchQuery) {
                return this.products = this.allProducts
            }

            return this.products = this.allProducts.filter((item) => {
                return (item.id.includes(this.searchQuery))
            })
        },
        /**
         * Toggles the modal containing the confirmation for a delete action
         * @param {Object} product
         * @return void
         */
        toggleConfirmModal(product) {
            this.showConfirmationModal = !this.showConfirmationModal
            this.confirmationData = product
        },
        /**
         * Retrieves all the products and pushes them to the display array
         * @return {Promise<void>}
         */
        getProducts() {
            axios.get('/api/products/all')
                .then(response => {
                    this.products = response.data
                    this.allProducts = response.data
                })
        },
        /**
         * Pushes the user to the editing page for the selected product
         * @param {Object} product
         * @return void
         */
        editProduct(product) {
            this.$router.push(`/pim/products/edit/${product.id}/specifications`)
        },
        /**
         * Deletes the product from the database
         * @return {Promise<void>}
         */
        deleteProduct() {
            const product = this.confirmationData
            axios.put(`/api/products/delete/${product.id}`)
            this.toggleConfirmModal()
            this.getProducts()
        },
        /**
         * Toggles the modal containing the creation form
         * @return void
         */
        toggleModal() {
            this.showModal = !this.showModal
            this.errors = null
            this.product.id = null
        },
        /**
         * Creates the product and saves it in the database
         * @return {Promise<void>}
         */
        async saveProduct() {
            axios.put('/api/products/save', this.product)
                .then(response => {
                    this.toggleModal()
                    this.getProducts()
                })
                .catch(exceptions => {
                    this.errors = exceptions.response.data
                })
        }
    }
}

</script>

<style scoped lang="scss">
@import "../../../../../styles/sass/modules/pageParts/contentContainer";
@import "../../../../../styles/sass/modules/pageParts/tableStyles";
@import "../../../../../styles/sass/modules/pim/products/productContent";
</style>
