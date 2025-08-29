import { defineStore } from 'pinia';
import { ref, watch } from 'vue';

export const useThemeStore = defineStore('CoreThemeStore', () => {
    const isDarkMode = ref(false);

    // Init Dark Mode
    function initDarkMode() {
        const savedMode = localStorage.getItem('isDarkMode');
        if (savedMode !== null && savedMode == 'true') {
            isDarkMode.value = true;
            document.documentElement.classList.add('dark');
        }

        watch(() => this.isDarkMode, (newValue, _) => {
            if(newValue){
                document.documentElement.classList.add('dark');
            }else{
                document.documentElement.classList.remove('dark');
            }
        })
    }

    function toggleDarkMode() {
        
        if (localStorage.isDarkMode != null &&
            localStorage.isDarkMode == "true"
        ) {
            localStorage.setItem('isDarkMode', 'false');
            isDarkMode.value = false;
        } else {
            localStorage.setItem('isDarkMode', 'true');
            isDarkMode.value = true;
        }
              
    }
    return {
        isDarkMode,
        toggleDarkMode,
        initDarkMode
    };
});