import { createRouter, createWebHistory } from 'vue-router'
import {hasRight} from "../composables/rights"
import {useUserStore} from "../piniaStores/userStore";

const managerRoutes = [
    {
        path: 'profiles',
        name: 'Profiles',
        component: () => import("../manager/profiles/components/profileDisplayComponent.vue"),
        meta: { hasRight: 'profile.view', exclude: false },
    },
    {
        path: 'rights',
        name: 'Rights',
        component: () => import("../manager/rights/rightsDisplayComponent.vue"),
        meta: { hasRight: 'rights.view', exclude: false },
    },
    {
        path: 'users',
        name: 'Users',
        component: () => import("../manager/users/components/userDisplayComponent.vue"),
        meta: { hasRight: 'user.view', exclude: false },
    },
    {
        path: 'user/create',
        name: 'CreateUser',
        component: () => import("../manager/users/components/userCreateComponent.vue"),
        meta: { hasRight: 'user.modify', exclude: false }
    },
    {
        path: 'user/edit/:id',
        name: 'EditUser',
        component: () => import("../manager/users/components/userCreateComponent.vue"),
        meta: { hasRight: 'user.modify', exclude: true }
    },
    {
        path: 'manager_locales',
        name: 'manager_locales',
        component: () => import("../manager/settings/manager_localesComponent.vue"),
        meta: { hasRight: 'manager_locales.view', exclude: false }
    },
    {
        path: 'pim_locales',
        name: 'pim_locales',
        component: () => import("../manager/settings/pim_localesComponent.vue"),
        meta: { hasRight: 'pim_locales.view', exclude: false }
    }
]

const pimRoutes = [
    {
        path: 'products',
        name: 'productOverview',
        component: () => import("../pim/components/products/productOverviewComponent.vue"),
        meta: { hasRight: 'product.view', exclude: false }
    },
    {
        path: 'products/edit/:id/specifications',
        name: 'productEditor-specifications',
        component: () => import("../pim/components/products/editor/specificationsComponent.vue"),
        meta: { hasRight: 'product.modify', exclude: true}
    },
    {
        path: 'products/edit/:id/assets',
        name: 'productEditor-assets',
        component: () => import("../pim/components/products/editor/assetsComponent.vue"),
        meta: { hasRight: 'product.modify', exclude: true}
    },
    {
        path: 'products/edit/:id/relations',
        name: 'productEditor-relations',
        component: () => import("../pim/components/products/editor/relationsComponent.vue"),
        meta: { hasRight: 'product.modify', exclude: true }
    },
    {
        path: 'specifications',
        name: 'specificationOverview',
        component: () => import("../pim/components/specifications/specificationsOverviewComponent.vue"),
        meta: { hasRight: 'spec.view', exclude: false }
    }
]

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: () => import("../pim/components/login/loginComponent.vue"),
        meta: { exclude: true }
    },
    {
        path: '/manager',
        name: 'manager',
        children: managerRoutes, // Nested child routes under /manager
    },
    {
        path: '/pim',
        name: 'pim',
        children: pimRoutes,
    },
    {
        path: '/403',
        name: '403',
        component: () => import("../errorPage/components/errors/403.vue"),
        meta: { exclude: true }
    },
    {
        path: '/',
        name: '/',
        meta: { exclude: true }
    }
]

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
})

router.beforeEach(async (to, from, next) => {
    if (to.path !== '/login') {
         let user = await useUserStore().getUser
         console.log(`user: ${JSON.stringify(user)}`)
         if (user === null) {
             await useUserStore().fetchUser()
             user = await useUserStore().getUser
             if (user === null) {
                 return next('/login')
             }
        }
        if (to.path === '/') {
            return next({ name: user.defaultPage })
        }
        if (to.meta && to.meta.hasRight) {
            const hasRequiredRights = await hasRight([to.meta.hasRight])
            if (hasRequiredRights[to.meta.hasRight] || to.path === `/manager/user/edit/${to.params.id}` && parseInt(to.params.id) === user.id) {
                return next()
            } else {
                return next('/403')
            }
        }
        return next()
    } else {
        return next()
    }
})

export default router
