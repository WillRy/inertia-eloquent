import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import VueToast from "vue-toast-notification"
import Loader from "../components/Loader";

createInertiaApp({
    resolve: name => import(`./Pages/${name}`),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(VueToast)
            .component('Loader', Loader)
            .mount(el)
    },
})
