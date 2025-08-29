import './bootstrap';
import '../css/app.css';
import 'tailwindcss/tailwind.css';

import { i18nVue, getActiveLanguage } from 'laravel-vue-i18n'

import { createApp, h } from 'vue';
import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
// import { InertiaProgress } from '@inertiajs/progress';
import VueLazyLoad from 'vue3-lazyload'
import { createPinia } from 'pinia'
// import Flutterwave from 'flutterwave-vue3ts'
import VLazyImage from "v-lazy-image";
import PsApi from '@templateCore/api/common/PsApi';

let sub_domain_url = '';
let clearSlash =  window.appConfig.VITE_APP_DIR; //import.meta.env.VITE_APP_DIR;
let subFolder = clearSlash.replaceAll("\\", "");

if(import.meta.env.PROD){

    if(subFolder != null && subFolder != '')
    {
        sub_domain_url = '/'  + window.appConfig.VITE_APP_DIR; //import.meta.env.VITE_APP_DIR;
    }
    else
    {
        sub_domain_url = '';
    }


}else{
    sub_domain_url = '';
}

function withVite(pages, name) {
    // console.log(pages)
    for (const path in pages) {
        if (path.endsWith(`${name.replace('.', '/')}.vue`)) {
            return typeof pages[path] === 'function'
                ? pages[path]()
                : pages[path]
        }
    }

    throw new Error('Page not found: ' + name)
}

const pinia = createPinia()

createInertiaApp({
    progress: {
        color: '#1267dc',
        showSpinner: true,
      },
    title: (title) => `${title}`,
    resolve: (name) => {
        let part = name.split("::")
        try {
            return withVite(import.meta.glob('./Pages/**/*.vue'), name)
        } catch (e) {
            return withVite(import.meta.glob('../../Modules/**/Resources/Pages/**/*.vue'), name)
        }
    },
    setup({ el, App, props, plugin }) {

        let sub_domain_url = '';
        let clearSlash =  window.appConfig.VITE_APP_DIR;//import.meta.env.VITE_APP_DIR;
        let subFolder = clearSlash.replaceAll("\\", "");
        PsApi.setDefaultApiToken(props.initialPage.props.api_token);

        if(import.meta.env.PROD){

            if(subFolder != null && subFolder != '')
            {
                sub_domain_url = '/'  + window.appConfig.VITE_APP_DIR;//import.meta.env.VITE_APP_DIR;
            }
            else
            {
                sub_domain_url = '';
            }

        }else{
            sub_domain_url = '';
        }

        const base_url = sub_domain_url+"/api/v1.0"

        let activeLanguage = props.initialPage.props.langSymbol ?? getActiveLanguage();

        if (localStorage.activeLanguage != null) {
            activeLanguage = localStorage.activeLanguage;
        } else {
            localStorage.activeLanguage = activeLanguage;
        }
        document.cookie = `activeLanguage=${activeLanguage}; max-age=31536000; path=/`;

        // const apiToCall = base_url + '/mobile_language/langs?symbol=' + activeLanguage.toLowerCase();
        // console.log("Hello ***********");
        // console.log(JSON.parse(props.initialPage.props.langs));
        // axios.get(apiToCall).then(res => {
            // return
            createApp({ render: () => h(App, props) })
                .use(plugin)
                .use(pinia)
                .use(VueLazyLoad, {})
                .use(i18nVue, {
                    lang: activeLanguage.toLowerCase(),
                    fallbackLang: 'en',
                    resolve: lang => {
                        // return res.data;
                        return JSON.parse(props.initialPage.props.langStrings);

                    },
                })
                // .use(Flutterwave)
                .use(VLazyImage)
                .component('InertiaHead', Head)
                .component('Link', Link)
                .mixin({ methods: { route } })
                .mount(el)

        // }).catch(error => {
        //     return createApp({ render: () => h(App, props) })
        //         .use(plugin)
        //         .use(pinia)
        //         .use(VueLazyLoad, {})
        //         .use(i18nVue, {
        //             lang: activeLanguage.toLowerCase(),
        //             fallbackLang: 'en',
        //             resolve: lang => {
        //                 const langs = import.meta.globEager('../../lang/en.json');
        //                 return langs[`../../lang/${lang}.json`].default;
        //             }
        //         })
        //         .use(Flutterwave)
        //         .use(VLazyImage)
        //         .component('InertiaHead', Head)
        //         .component('Link', Link)
        //         .mixin({ methods: { route } })
        //         .mount(el)
        // });
    },
}).then(() => {
    document.getElementById('app').removeAttribute('data-page');
});

// InertiaProgress.init({ color: '#1267dc', showSpinner: true, includeCSS: true });
