require('./bootstrap')

window.Vue = require('vue').default
import Vue from 'vue'

import App from '@/app.vue'
import appRoute from '@routes/route.js'

axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token')

// destructuring response API
axios.interceptors.response.use(response => {
  return response.data
})

const app = new Vue({
  el: '#app',
  render: h => h(App),
  router: appRoute
})

Echo.private('chat')
  .listen('MessageEvent', (e) => {
    console.log(e, 'skdfjklsdaj');
});
