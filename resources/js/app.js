import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import VueToast from 'vue-toast-notification';
import {DatePicker} from 'v-calendar';
import FloatingVue from 'floating-vue'

import {filters, LaravelError, EventBus, VerificaPermissao} from './plugins'
import outside from './helpers/click-outside';

import Loader from "./components/Loader.vue";
import BaseModal from "./components/base/modal/BaseModal.vue";

import store from './store';

import { InertiaProgress } from '@inertiajs/progress'
InertiaProgress.init({
    // The delay after which the progress bar will
    // appear during navigation, in milliseconds.
    delay: 250,

    // The color of the progress bar.
    color: '#f2b80c',

    // Whether to include the default NProgress styles.
    includeCSS: true,

    // Whether the NProgress spinner will be shown.
    showSpinner: false
})

import { VueMaskDirective } from 'v-mask';

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
            .use(store)
            .use(VueToast)
            .use(filters)
            .use(LaravelError)
            .use(FloatingVue)
            .use(outside)
            .use(EventBus)
            .use(VerificaPermissao)
            .directive('mask', vMaskV3)
            .component('DatePicker', DatePicker)
            .component("Loader", Loader)
            .component("BaseModal", BaseModal)
            .mount(el)
    },
})
