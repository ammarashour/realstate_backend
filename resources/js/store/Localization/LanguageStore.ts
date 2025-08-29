import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useLanguageStore = defineStore('CoreLanguageStore', () => {
    const DEFAULT_LANG = "en";
    const activeLanguage = ref(false);

    function initActiveLangauge() {
        if (localStorage.activeLanguage != null) {
            this.activeLanguage = localStorage.getItem("activeLanguage");
        } else {
            localStorage.activeLanguage = this.DEFAULT_LANG;
            this.activeLanguage = this.DEFAULT_LANG;
        }
    }

    function getLanguageDir(){
        if(localStorage.activeLanguage != null 
            && localStorage.activeLanguage != undefined 
            && localStorage.activeLanguage == 'ar'){
            return 'rtl';
        }else{
            return 'ltr';
        }
    }

    function setActiveLanguage(lang) {
        localStorage.setItem('activeLanguage', lang);
        this.activeLanguage = lang;
        setTimeout(()=>{
            window.location.reload();
        },100)
    }
    return {
        activeLanguage,
        initActiveLangauge,
        setActiveLanguage,
        getLanguageDir
    };
});