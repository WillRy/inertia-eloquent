import {createApp, h} from 'vue'
import {createInertiaApp} from '@inertiajs/inertia-vue3'
import VueToast from "vue-toast-notification"
import Loader from "./components/Loader";
import {DatePicker} from 'v-calendar';
import {VueMaskDirective} from 'v-mask';
import outside from './click-outside';

import store from './store/index'

let LaravelError = {
    install: (app, options) => {
        // inject a globally available $translate() method
        app.config.globalProperties.$laravelError = (e, message) => {
            let response = e.response;
            if (response && response.status === 422 && response.data.errors) {
                let erro = Object.keys(response.data.errors)[0];
                app.config.globalProperties.$toast.open({
                    message: response.data.errors[erro][0],
                    type: 'error'
                });
            } else if (response && response.data.error) {
                app.config.globalProperties.$toast.open({
                    message: response.data.error,
                    type: 'error'
                });
            } else {
                app.config.globalProperties.$toast.open({
                    message: message,
                    type: 'error'
                });
            }
        }
    }
}

let filters = {
    install: (app, options) => {
        app.config.globalProperties.$filters = {
            data(value) {
                return value ? new Date(`${value}T00:00:00`).toLocaleDateString() : '';
            },
            dinheiro(valor) {
                return (new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(valor));
            },
            genero(valor) {
                switch (valor) {
                    case 'm':
                        return 'masculino'
                    case 'f':
                        return 'feminino'
                    default:
                        return 'outro'
                }
            }
        }
    }
}

let debounce = {
    install: (app, options) => {
        app.config.globalProperties.$debounce = function () {
            let timeout = null;
            return function (fnc, delayMs) {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    fnc();
                }, delayMs || 500);
            };
        }()
    }
}

const vMaskV2 = VueMaskDirective;
const vMaskV3 = {
    beforeMount: vMaskV2.bind,
    updated: vMaskV2.componentUpdated,
    unmounted: vMaskV2.unbind
};

createInertiaApp({
    resolve: name => import(`./Pages/${name}`),
    setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .use(plugin)
            .use(VueToast)
            .use(LaravelError)
            .use(debounce)
            .use(filters)
            .use(outside)
            .use(store)
            .component('Loader', Loader)
            .component('DatePicker', DatePicker)
            .directive('mask', vMaskV3)
            .mount(el)
    },
})
