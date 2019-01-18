import VueRouter from 'vue-router'

import Login from './components/login'

/*
    ADMIN COMPONENTS
*/
import AdminApp from './components/admin/layouts/app'
import Dashboard from './components/admin/pages/dashboard'
import Permission from './components/admin/pages/permissions/index'
import PermissionCreate from './components/admin/pages/permissions/create'
import PermissionUpdate from './components/admin/pages/permissions/update'
import PermissionView from './components/admin/pages/permissions/view'

import Role from './components/admin/pages/roles/index'
import RoleCreate from './components/admin/pages/roles/create'
import RoleUpdate from './components/admin/pages/roles/update'
import RoleView from './components/admin/pages/roles/view'

import User from './components/admin/pages/users/index'
import UserCreate from './components/admin/pages/users/create'
import UserUpdate from './components/admin/pages/users/update'
import UserView from './components/admin/pages/users/view'

import Category from './components/admin/pages/categories/index'
import CategoryCreate from './components/admin/pages/categories/create'

const routes = [
    {
        path: '/login',
        component: Login,
        beforeEnter: (to, from, next) => {
            return axios.get('/api/auth/current').then(response => {
                return next({path: '/'});
            }).catch(errors => {
                return next()
            })
        }
    },
    {
        path: '/admin',
        component: AdminApp,
        meta: {
            auth: true,
            admin: true
        },
        children: [
            {path: '/', component: Dashboard},
            
            {path: 'permission', component: Permission},
            {path: 'permission/create', component: PermissionCreate},
            {path: 'permission/:slug/update', component: PermissionUpdate, name: 'PermissionUpdate'},
            {path: 'permission/:slug/view', component: PermissionView, name: 'PermissionView'},

            {path: 'role', component: Role},
            {path: 'role/create', component: RoleCreate},
            {path: 'role/:slug/update', component: RoleUpdate, name: 'RoleUpdate'},
            {path: 'role/:slug/view', component: RoleView, name: 'RoleView'},

            {path: 'user', component: User},
            {path: 'user/create', component: UserCreate},
            {path: 'user/:slug/update', component: UserUpdate, name: 'UserUpdate'},
            {path: 'user/:slug/view', component: UserView, name: 'UserView'},

            {path: 'category', component: Category},
            {path: 'category/create', component: CategoryCreate}
        ]
    }
]

const router = new VueRouter({
    routes: routes,
    mode: 'history',
    linkActiveClass: 'active',
    linkExactActiveClass: 'active'
})

router.beforeEach((to, from, next) => {
    if(to.matched.some(m => m.meta.auth)) {
        return axios.get('/api/auth/current').then(authenticated => {
            if(to.matched.some(m => m.meta.admin)) {
                return axios.get('/api/auth/admin').then(response => {
                        return next();
                    }).catch(errors => {
                        return next({path: '/404'});
                    })
            }
            return next();
        }).catch(error => {
            return next({path: '/login'})
        }) 
    }
    return next()
})

export default router