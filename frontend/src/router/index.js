// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router'
import store from '@/store'

// Dynamic imports for code splitting
const Login = () => import(/* webpackChunkName: "auth" */ '@/views/Login.vue')
const Register = () => import(/* webpackChunkName: "auth" */ '@/views/Register.vue')
const Search = () => import(/* webpackChunkName: "main" */ '@/views/Search.vue')
const Favorites = () => import(/* webpackChunkName: "main" */ '@/views/Favorites.vue')

const routes = [
    {
        path: '/',
        redirect: to => {
            if (store.getters['auth/isAuthenticated']) {
                return { path: '/search' };
            } else {
                return { path: '/login' };
            }
        }
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: {
            guest: true,
            title: 'Login - MyDictionary',
            layout: 'AuthLayout'
        }
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
        meta: {
            guest: true,
            title: 'Register - MyDictionary',
            layout: 'AuthLayout'
        }
    },
    {
        path: '/search',
        name: 'Search',
        component: Search,
        meta: {
            requiresAuth: true,
            title: 'Search - MyDictionary',
            layout: 'DefaultLayout'
        }
    },
    {
        path: '/favorites',
        name: 'Favorites',
        component: Favorites,
        meta: {
            requiresAuth: true,
            title: 'My Favorites - MyDictionary',
            layout: 'DefaultLayout'
        }
    },
    {
        // Vue Router 4 uses :pathMatch(.*) instead of *
        path: '/:pathMatch(.*)*',
        redirect: to => {
            if (store.getters['auth/isAuthenticated']) {
                return { path: '/search' };
            } else {
                return { path: '/login' };
            }
        }
    }
]

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        }
        if (to.hash) {
            return {
                el: to.hash, // Vue Router 4 uses 'el' instead of 'selector'
                behavior: 'smooth'
            }
        }
        return { top: 0, left: 0 } // Vue Router 4 uses 'top' and 'left' instead of 'x' and 'y'
    }
})

router.beforeEach(async (to, from, next) => {
    // Start loading indicator
    store.commit('SET_LOADING', true)

    try {
        await store.dispatch('auth/user')
        const isAuthenticated = store.getters['auth/isAuthenticated']

        if (to.matched.some(record => record.meta.requiresAuth)) {
            if (!isAuthenticated) {
                next({
                    name: 'Login',
                    query: { redirect: to.fullPath }
                })
                return
            }
        }

        if (to.matched.some(record => record.meta.guest) && isAuthenticated) {
            next({ name: 'Search' })
            return
        }

        next()
    } catch (error) {
        console.error('Router navigation error:', error)
        next(false) // Abort current navigation
    } finally {
        store.commit('SET_LOADING', false)
    }
})

router.afterEach((to) => {
    document.title = to.meta.title || 'MyDictionary'

    // Track page view for analytics
    if (window.gtag) {
        // Google Analytics 4 (gtag)
        window.gtag('event', 'page_view', {
            page_title: document.title,
            page_location: window.location.href,
            page_path: to.path
        })
    } else if (window.ga) {
        // Legacy Google Analytics
        window.ga('send', 'pageview', to.path)
    }
})

export default router