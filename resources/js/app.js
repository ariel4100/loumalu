require('./bootstrap');

require('moment');

import Vue from 'vue';

import { InertiaApp } from '@inertiajs/inertia-vue';
import { InertiaForm } from 'laravel-jetstream';
import PortalVue from 'portal-vue';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'jodit/build/jodit.min.css'
import JoditVue from 'jodit-vue'
import Slick from 'vue-slick';
import Multiselect from 'vue-multiselect'


Vue.mixin({
    data(){
        return {
            menu: [
                {
                    nombre: 'Inicio',
                    route: 'home',
                    url: 'home',
                    mostrar: 0,
                },
                {
                    nombre: 'NOSOTROS',
                    route: 'empresa',
                    url: 'empresa',
                    mostrar: 1,
                },
                {
                    nombre: 'Productos',
                    route: 'familias',
                    url: 'familias',
                    mostrar: 1,
                },
                {
                    nombre: 'NOVEDADES Y OFERTAS',
                    route: 'novedades',
                    url: 'blog',
                    mostrar: 1,
                },
                {
                    nombre: 'DESCARGAS',
                    route: 'descargas',
                    url: 'descargas',
                    mostrar: 1,
                },
                {
                    nombre: 'Contacto',
                    route: 'contacto',
                    url: 'contacto',
                    mostrar: 1,
                },
            ]
        }
    },
    methods: {
        t(key) {
            // console.log('aca')
            let item = 0
            if(this.$page.traducciones){
                item = this.$page.traducciones.find((item) => {
                    if(item.key == key){
                        return item
                    }
                })
            }
            if(item){
                return item.traduccion
            }else{
                return key
            }
        },
        logout() {
            axios.post(route('logout').url()).then(response => {
                window.location = route('home');
            })
        },
    }
})
Vue.mixin({ methods: { route } });
Vue.use(InertiaApp);
Vue.use(InertiaForm);
Vue.use(PortalVue);

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(JoditVue)

Vue.component(JoditVue)
Vue.component(Slick)
Vue.component('multiselect', Multiselect)

const app = document.getElementById('app');

new Vue({
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);
