import { createWebHistory, createRouter } from 'vue-router'
import store from '@/store'
/* Guest Component */
const Login = () => import('@/components/Login.vue')
const Register = () => import('@/components/Register.vue')
const UpdateProfileUser = () => import('@/components/UpdateProfileUser.vue')
const FavouritesQuotes = () => import('@/components/FavouritesQuotes.vue')
const Users = () => import('@/components/Users.vue')
/* Guest Component */
/* Layouts */
const DahboardLayout = () => import('@/components/layouts/Default.vue')
/* Layouts */
/* Authenticated Component */
const Dashboard = () => import('@/components/Dashboard.vue')
/* Authenticated Component */
const routes = [
    {
        name: "login",
        path: "/login",
        component: Login,
        meta: {
            middleware: "guest",
            title: `Login`
        }
    },
    {
        name: "register",
        path: "/register",
        component: Register,
        meta: {
            middleware: "guest",
            title: `Register`
        }
    },
    {
        path: "/",
        component: DahboardLayout,
        meta: {
            middleware: "auth"
        },
        children: [
            {
                name: "dashboard",
                path: '/',
                component: Dashboard,
                meta: {
                    title: `Dashboard`
                }
            },
            {
                name: "favouritesQuotes",
                path: '/favourites-quotes',
                component: FavouritesQuotes,
                meta: {
                    title: `Favourites Quotes`
                }
            },
            {
                name: "users",
                path: '/users',
                component: Users,
                meta: {
                    title: `Users`
                }
            },
            {
                name: "updateProfileUser",
                path: '/update-profile-:id',
                component: UpdateProfileUser,
                meta: {
                    title: `Update Profile`
                }
            }
        ]
    }
]
const router = createRouter({
    history: createWebHistory(),
    routes, // short for `routes: routes`
})
router.beforeEach((to, from, next) => {
    document.title = to.meta.title
    if (to.meta.middleware == "guest") {
        if (store.state.auth.authenticated) {
            next({ name: "dashboard" })
        }
        next()
    } else {
        if (store.state.auth.authenticated) {
            next()
        } else {
            next({ name: "login" })
        }
    }
})
export default router