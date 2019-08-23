/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import moment from 'moment';
// ES6 Modules or TypeScript
import Swal from 'sweetalert2';
window.Swal = Swal;

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end', //'top', 'top-start', 'top-end', 'center', 'center-start', 'center-end', 'bottom', 'bottom-start', or 'bottom-end'.
  showConfirmButton: false,
  timer: 3000
});
window.Toast = Toast;

import Datepicker from 'vuejs-datepicker';
window.Datepicker = Datepicker;

import { Form, HasError, AlertError } from 'vform';

window.Form = Form;

Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
Vue.component('pagination', require('laravel-vue-pagination'));

import VueProgressBar from 'vue-progressbar';


const options = {
  color: '#bffaf3',
  failedColor: '#874b4b',
  thickness: '5px',
  transition: {
    speed: '0.2s',
    opacity: '0.6s',
    termination: 300
  },
  autoRevert: true,
  location: 'top',
  inverse: false
};

Vue.use(VueProgressBar, options);



import VueRouter from 'vue-router';

Vue.use(VueRouter);


// 2. ルートをいくつか定義します
// 各ルートは 1 つのコンポーネントとマッピングされる必要があります。
// このコンポーネントは実際の `Vue.extend()`、
// またはコンポーネントオプションのオブジェクトでも構いません。
// ネストされたルートに関しては後で説明します

const routes = [
    { path: '/medcheck/public/users', component: require('./components/User/ManageUsers.vue').default },
    { path: '/medcheck/public/import', component: require('./components/ReceptionList/ImportList.vue').default },
    { path: '/medcheck/public/reserve', component: require('./components/Reserve/ManageReserves.vue').default },
    { path: '/medcheck/public/progress', component: require('./components/ProgressManagement/ManageProgress.vue').default },
    { path: '/medcheck/public/exam_result', component: require('./components/ExamResult/ExamResult.vue').default },
    { path: '/medcheck/public/exam_area/:id', component: require('./components/ExamArea/AreaResult.vue').default },
    { path: '/medcheck/public/manage_exam_area', component: require('./components/ExamArea/ManageExamArea.vue').default },
    { path: '/medcheck/public/edit_config', component: require('./components/Config/EditConfig.vue').default  },
    { path: '/medcheck/public/dash_board', component: require('./components/Top/DashBoard.vue').default },
    { path: '/medcheck/public/manage_auth', component: require('./components/passport/manageAuth.vue').default }
  ]

// 3. ルーターインスタンスを作成して、ルートオプションを渡します
// 追加のオプションをここで指定できますが、
// この例ではシンプルにしましょう
const router = new VueRouter({
    mode: 'history',
    routes, // `routes: routes` の短縮表記
    linkActiveClass: 'active'
  });


Vue.filter('myDate',function(raw_date){
  if(raw_date == null){
    return "--";
  }
  return moment(raw_date).format('YYYY-MM-DD');
});



import EventHub from './EventHub';
Vue.use(EventHub);


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('side-menu-area', require('./components/SideMenuArea.vue').default);
Vue.component('right-side-bar', require('./components/RightSideBar.vue').default);

Vue.component(
  'passport-clients',
  require('./components/passport/Clients.vue').default
);

Vue.component(
  'passport-authorized-clients',
  require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
  'passport-personal-access-tokens',
  require('./components/passport/PersonalAccessTokens.vue').default
);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    watch:{
      $route:function(to, from){
        $('.modal-backdrop').remove();
      }
  } 
});
