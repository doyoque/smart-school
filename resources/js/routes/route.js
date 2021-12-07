import Vue from 'vue'
import VueRouter from 'vue-router'
import IndexPage from '@pages/index.vue'
import DashboardPage from '@pages/dashboard.vue'
import SignupPage from '@pages/signup.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'index',
    component: IndexPage,
    meta: {
      guest: true
    }
  },
  {
    path: '/signup',
    name: 'signup',
    component: SignupPage,
    meta: {
      guest: true
    }
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: DashboardPage,
    meta: {
      school_admin: true
    }
  }
]

const router = new VueRouter({
  hashbang: false,
  mode: 'history',
  routes: routes
})

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.school_admin)) {
    if (localStorage.token) {
      next()
    } else {
      next('/')
    }
  } else {
    next()
  }
})

export default router
