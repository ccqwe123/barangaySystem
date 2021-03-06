
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router'
Vue.use(VueRouter)
import { Form, HasError, AlertError } from 'vform'
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

import Swal from 'sweetalert2'
window.swal = Swal;
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})
window.toast = Toast;

import vSelect from 'vue-select'
Vue.component('v-select', vSelect)

import Chart from 'chart.js'
// Vue.use(Chart)
window.Chart = Chart;
import VueHtmlToPaper from 'vue-html-to-paper';

const options = {
  name: '_blank',
  specs: [
    'fullscreen=yes',
    'titlebar=yes',
    'scrollbars=yes'
  ],
  styles: [
    'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
    'https://unpkg.com/kidlat-css/css/kidlat.css',
    '/css/printstyle.css'
  ]
}

Vue.use(VueHtmlToPaper, options);
Vue.use(require('vue-moment'));
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
let routes = [
  { path: '/dashboard', component: require('./components/DashboardComponent.vue').default, meta: { title: 'BRIS - Dashboard', } },
  { path: '/users', component: require('./components/UsersComponent.vue').default, meta: { title: 'BRIS - Users', } },
  { path: '/profile', component: require('./components/ProfileComponent.vue').default, meta: { title: 'BRIS - Profile', } },
  { path: '/barangay', component: require('./components/BarangayComponent.vue').default, meta: { title: 'BRIS - Barangay', } },
  { path: '/residents', component: require('./components/ResidentsComponent.vue').default, meta: { title: 'BRIS - Residents', } },
  { path: '/business-clearance', component: require('./components/BusinessClearanceComponent.vue').default, meta: { title: 'BRIS - Barangay Clearance', } },
  { path: '/system', component: require('./components/SystemComponent.vue').default, meta: { title: 'BRIS - System', } },
  { path: '/barangay-officials', component: require('./components/BarangayOfficialComponent.vue').default, meta: { title: 'BRIS - Barangay Officials', } },
]
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const router = new VueRouter({
	mode: 'history',
  routes
})

const app = new Vue({
    el: '#app',
    router
});
router.beforeEach((to, from, next) => {
  const nearestWithTitle = to.matched.slice().reverse().find(r => r.meta && r.meta.title);
  const nearestWithMeta = to.matched.slice().reverse().find(r => r.meta && r.meta.metaTags);
  const previousNearestWithMeta = from.matched.slice().reverse().find(r => r.meta && r.meta.metaTags);
  if(nearestWithTitle) document.title = nearestWithTitle.meta.title;
  Array.from(document.querySelectorAll('[data-vue-router-controlled]')).map(el => el.parentNode.removeChild(el));
  if(!nearestWithMeta) return next();
  nearestWithMeta.meta.metaTags.map(tagDef => {
    const tag = document.createElement('meta');

    Object.keys(tagDef).forEach(key => {
      tag.setAttribute(key, tagDef[key]);
    });
    tag.setAttribute('data-vue-router-controlled', '');
    return tag;
  })
  .forEach(tag => document.head.appendChild(tag));
  next();
});


