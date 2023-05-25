import './bootstrap';
import '../css/app.css';
import '../css/svg.css';
import '../sass/app.scss';
import 'datatables.net-dt/css/jquery.dataTables.min.css'

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

import { DateTime } from "luxon";

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'VX Project - Flags';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    progress: {
        color: '#9900ff',
    },
});
