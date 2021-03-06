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
import Cargando from './components/common/Cargando.vue'
// import navComponent from './components/NavComponent.vue'
import Ventas from './components/ventas/Ventas.vue'
import Listado from './components/listado/Listado.vue'
import Ingresar from './components/ingresar/Ingresar.vue'
import VenderProvider from './components/vender/VenderProvider.vue'
import VenderCommerce from './components/vender/VenderCommerce.vue'
import CodigosDeBarra from './components/codigos-de-barra/CodigosDeBarra.vue'
import Empleados from './components/empleados/Empleados.vue'
import Configuracion from './components/configuracion/Configuracion.vue'
// import Empleados from './components/empleados/Empleados.vue'
// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('cargando', Cargando);
// Vue.component('nav-component', navComponent);
Vue.component('ventas', Ventas);
Vue.component('listado', Listado);
Vue.component('ingresar', Ingresar);
Vue.component('vender-provider', VenderProvider);
Vue.component('vender-commerce', VenderCommerce);
Vue.component('codigos-de-barra', CodigosDeBarra);
Vue.component('empleados', Empleados);
Vue.component('configuracion', Configuracion);
// Vue.component('empleados', Empleados);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

Array.prototype.unique=function(a){
  return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
});
