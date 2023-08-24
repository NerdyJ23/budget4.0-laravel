import './bootstrap';
import '../css/app.scss';

import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { OhVueIcon } from "oh-vue-icons";
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import store from './store'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
			.use(store)
			.component('VIcon', OhVueIcon)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
