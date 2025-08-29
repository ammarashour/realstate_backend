import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useScrollStore = defineStore('CoreScrollRestorationStore', () => {

    const atTopOfPage = ref(true);
    
    function initStoreRestoration() {

        // For Scroll Position Restore When click Back button
        // Handling manual for polyfill
        if ('scrollRestoration' in window.history) {
            window.history.scrollRestoration = 'manual';
        }

        // Monitoring the back action
        // it will set true when back action.
        // But current is only supported for item list
        // That means if back action is target to item list
        // it will mark as true in popStateDetected
        // @ts-ignore
        window.popStateDetected = false; // declared in resources/js/Types/index.d.ts
        window.addEventListener('popstate', (event) => {
            if(event != null
                && event.state != null
                && event.state.url != null
                && ( String(event.state.url).includes("item-list", 1)
                    || String(event.state.url).includes("active-items", 1)
                    || String(event.state.url).includes("other-profile", 1)
                )) {
                    // @ts-ignore
                    window.popStateDetected = true;                
            }else {
                // @ts-ignore
                window.popStateDetected = false;
            }

        });
    }

    function addHandleScrollListener() {
        window.addEventListener('scroll', handleScroll);
    }

    function removeHandleScrollListener() {
        window.removeEventListener('scroll', handleScroll);
    }

    function handleScroll(){
        // when the user scrolls, check the pageYOffset
        if(window.pageYOffset>30){
            // user is scrolled
            if(atTopOfPage.value) atTopOfPage.value = false;
        }else{
            // user is at top of page
            if(!atTopOfPage.value) atTopOfPage.value = true;
        }
    }
    return {
        atTopOfPage,
        initStoreRestoration,
        addHandleScrollListener,
        removeHandleScrollListener
    };
});