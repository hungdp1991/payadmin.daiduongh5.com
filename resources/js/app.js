/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

/**
 * Import component
 */
Vue.component('app-component', require('./components/AppComponent.vue').default);
Vue.component('in-loading-page-component', require('./components/ui/InLoadingPageComponent').default);
Vue.component('in-process-component', require('./components/ui/InProcessComponent.vue').default);
Vue.component('in-getting-component', require('./components/ui/InGettingComponent.vue').default);
Vue.component('in-fetching-component', require('./components/ui/InFetchingComponent.vue').default);
Vue.component('pagination-component', require('./components/ui/PaginationComponent.vue').default);
Vue.component('image-choosing-component', require('./components/ui/ImageChoosingComponent.vue').default);
Vue.component('bootstrap-switch-component', require('./components/ui/BootstrapSwitchComponent.vue').default);
Vue.component('ckeditor-5-component', require('./components/ui/CKEditor5Component.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


/**
 * Import router
 */
import router from './router';
/**
 * import vuex store
 */
import store from './vuex/store';


/**
 * Create vue-select2 directive
 */
Vue.directive('select2', {
    twoWay: true,
    bind: function (el, binding, vnode) {
        $(el).on('select2:select', (e) => {
            el.dispatchEvent(new Event('change'));
        });
        $(el).on('select2:unselect', (e) => {
            el.dispatchEvent(new Event('change'));
        });
    },
});

/**
 * Import CKEditor
 */
import CKEditor from '@ckeditor/ckeditor5-vue';
Vue.use( CKEditor );

/**
 * Import Vue Numeral
 * @type {Vue}
 */
import vueNumeralFilterInstaller from 'vue-numeral-filter';
Vue.use(vueNumeralFilterInstaller);


/**
 * Create Vue app after check authentication
 * @type {Vue}
 */
store.dispatch('checkAuthenticationStatus')
    .then(() => {
        const app = new Vue({
            el: '#app',
            router,
            store
        });
    });
