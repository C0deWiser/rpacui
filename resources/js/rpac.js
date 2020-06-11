const $$ = q => document.querySelector(q),
    USER = $$('meta[name=user]') && $$('meta[name=user]').content
        ? JSON.parse($$('meta[name=user]').content)
        : {},
    routes = [
        { name: 'home', path: '/', redirect: { name: 'my' } },
        { name: 'my', path: '/my', component: () => import('./components/IndexMy'), meta: { icon: 'el-icon-setting' } },
        { name: 'users', path: '/users', component: () => import('./components/IndexUsers'), meta: { icon: 'el-icon-user' } },
        { name: 'roles', path: '/roles', component: () => import('./components/IndexRoles'), meta: { icon: 'el-icon-medal' } },
        { name: '404', path: '*', component: () => import('./components/NotFound') },
    ],
    AppRoot = () => import('./components/AppRoot'),
    DEBUG = true;

window._l = DEBUG ? console.log : () => { };
window.appUrl = '/';
if ($$('meta[name=api-token]') && $$('meta[name=api-token]').content) USER.token = $$('meta[name=api-token]').content;
if ($$('meta[name=app-url]') && $$('meta[name=app-url]').content) window.appUrl = $$('meta[name=app-url]').content;


import axios from 'axios';
import Vue from 'vue';
import VueRouter from 'vue-router'
import ElementUI from 'element-ui';
// import 'element-ui/lib/theme-chalk/index.css'; // # see rpac.blade <link>

if (USER.token) axios.defaults.headers.common['Authorization'] = 'Bearer ' + USER.token;

// window.dictionary = false;
// window.lang = $$('html').getAttribute('lang') || 'ru';
// if (window.lang.length > 2) window.lang = window.lang.slice(0, 2);
// import('./lang/' + window.lang).then(f => { window.dictionary = f.default; }); // npm run watch
// let locales = [{ name: 'ru', file: 'ru-RU' }],
//     localeFile = locales.find(f => f.name === window.lang),
//     locale = localeFile ? () => import('element-ui/lib/locale/lang/' + localeFile.file) : false;
// import locale from 'element-ui/lib/locale/lang/ru-RU';

import dictionary from './lang/ru';
window.dictionary = dictionary;

Vue.use(VueRouter);
Vue.use(ElementUI, { locale: () => import('element-ui/lib/locale/lang/ru-RU') });

Vue.prototype.$$ = $$;
Vue.prototype.$user = USER;
Vue.prototype.$http = axios.create({baseURL: '/rpac/',}); // # see api.js
Vue.prototype.$routes = routes;
Vue.prototype.__ = (word) => (word && window.dictionary && window.dictionary[word]) ? window.dictionary[word] : word;
Vue.prototype.$catch = (e) => {
    return {
        title: (e.config && e.config.url ? e.config.url + ' ' : '') + (e.response.status || '🐞'),
        message: (e.response.data || '?')
    }
};

new Vue({
    el: '#rpac',
    data: {},
    router: new VueRouter({
        mode: 'history',
        base: 'rpac-ui',
        routes,
    }),
    render: h => h(AppRoot),
});