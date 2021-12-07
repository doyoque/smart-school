require('./bootstrap')

window.Vue = require('vue').default
import Vue from 'vue'

import App from '@/app.vue'
import appRoute from '@routes/route.js'

axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token')

const app = new Vue({
  el: '#app',
  render: h => h(App),
  router: appRoute
})
