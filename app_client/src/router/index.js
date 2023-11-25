import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import Dashboard from "../components/Dashboard.vue"
import Users from "../components/users/Users.vue"
import User from "../components/users/User.vue"
import Login from "../components/auth/Login.vue"
import ChangePassword from "../components/auth/ChangePassword.vue"
import Vcards from '../components/vcards/vcards.vue'
import Vcard from '../components/vcards/vcard.vue'
//import Transactions from "../components/transactions/Transactions.vue"

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/password',
      name: 'ChangePassword',
      component: ChangePassword 
    },
    {
      path: '/dashboard',
      name: 'Dashboard',
      component: Dashboard 
    },
    {
      path: '/users',
      name: 'Users',
      component: Users 
    },
    {
      path: '/users/:id',
      name: 'User',
      component: User,
      props: route => ({ id: parseInt(route.params.id) })
    },
    {
      path: '/vcards',
      name: 'Vcards',
      component: Vcards 
    },
    {
      path: '/vcards/new',
      name: 'NewVcard',
      component: Vcard 
    },
    {
      path: '/vcards/:phone_number',
      name: 'Vcard',
      component: Vcard,
      props: route => ({ phone_number: parseInt(route.params.phone_number) })
    },
    /*{
      path: '/transactions',
      name: 'Transactions',
      component: Transactions 
    },*/
  ]
})

export default router
