require('./bootstrap');
window.Vuetify = require('vuetify');

import Vue from 'vue'
import Vuetify from 'vuetify'

import 'vuetify/dist/vuetify.min.css'

import "noty/src/noty.scss";
import "noty/src/themes/mint.scss";

import VueApexCharts from 'vue-apexcharts'
import download from 'downloadjs'

Vue.use(Vuetify, download);

Vue.component('subir-pdf', require('./components/PDF/pdf.vue').default);

Vue.component('usuario-show', require('./components/Usuario/show.vue').default);
Vue.component('usuario-show-despacho', require('./components/Usuario/showPorDespacho.vue').default);

Vue.component('grafica', require('./components/Graficas/prueba.vue').default);



Vue.component('reporte', require('./components/Reportes/Batch.vue').default);
Vue.component('reporte-convenio-liquidacion', require('./components/Reportes/ConvenioLiquidacion.vue').default);
Vue.component('reporte-gestion', require('./components/Reportes/Gestion.vue').default);
Vue.component('reporte-pago', require('./components/Reportes/PagoReporte.vue').default);




Vue.component('cliente', require('./components/Cliente/DireccionCorreo').default);

Vue.component('telefono-component', require('./components/TelefonoComponent.vue').default);

Vue.component('spinner-component', require('./components/spinner.vue').default);

Vue.component('calendario', require('./components/Calendario.vue').default);

Vue.component('despacho-show', require('./components/Despacho/show.vue').default);
Vue.component('despacho-modal-crear', require('./components/Despacho/modalCrear.vue').default);

Vue.component('agregar-telefono-aval', require('./components/Gestion/agregarTelefonoAval.vue').default);
Vue.component('editar-telefono-aval', require('./components/Gestion/editarTelefonoAval.vue').default);
Vue.component('editar-direccion-aval', require('./components/Gestion/editarDireccionAval.vue').default);
Vue.component('editar-nombre-aval', require('./components/Gestion/editarNombreAval.vue').default);
Vue.component('agregar-datos-aval', require('./components/Gestion/agregarDatosAval.vue').default);

Vue.component('pago-intencion', require('./components/Gestion/pagoIntecion.vue').default);

Vue.component('editar-direccion-trabajo', require('./components/Trabajo/editarDireccionTrabajo.vue').default);
Vue.component('agregar-direccion-trabajo', require('./components/Trabajo/agregarDireccionTrabajo.vue').default);

Vue.component('agregar-datos-cliente', require('./components/Gestion/agregarDatosCliente.vue').default);
Vue.component('editar-direccion-cliente', require('./components/Gestion/editarDireccionCliente.vue').default);

Vue.component('crear', require('./components/Gestion/CrearGestionConvenioNota.vue').default);
Vue.component('gestion-pago', require('./components/Gestion/Pago').default);
Vue.component('gestiones-show', require('./components/Gestion/gestiones-show').default);
Vue.component('confirmar', require('./components/Gestion/confirmarIntencion').default);

Vue.component('gestion-convenio-show', require('./components/GestionConvenio/show.vue').default);
Vue.component('apexchart', VueApexCharts);

Vue.component('grafica-general-pasada', require('./components/Graficas/prueba_pasada.vue').default);

Vue.component('grafica-encargado', require('./components/Graficas/encargado.vue').default);
Vue.component('grafica-encargado-pasado', require('./components/Graficas/encargado_pasado').default);

Vue.component('grafica-gestor', require('./components/Graficas/gestor').default);
Vue.component('grafica-gestor_pasado', require('./components/Graficas/gestor_pasado').default);

Vue.component('grafica-gestor-encargado', require('./components/Graficas/gestor_encargado').default);

Vue.component('grafica-todo', require('./components/Graficas/todo').default);

Vue.component('navbar', require('./components/navbar').default);

new Vue({
    el: '#app',
    vuetify: new Vuetify(),
});
