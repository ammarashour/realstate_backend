<template>

    <Head>
        <!-- Page Icon -->
        <link v-if="$page.props.favIcon" rel="icon" type="image/svg+xml"
            :href="$page.props.uploadUrl + '/' + $page.props.favIcon.img_path" />
    </Head>

    <div class="flex flex-row" :dir="languageStore.getLanguageDir()">

        <!-- right -->
        <div class="flex flex-col flex-grow w-full dark:bg-secondaryDark-black dark:text-textLight">

            <!-- content -->
            <div @click="clickOutsideSidebar"
                :class="{ 'xl:ms-76 ms-0': sideMenuStore.isFullSideMenu, 'ms-0 xl:ms-20': !sideMenuStore.isFullSideMenu }"
                class="h-screen px-4 overflow-x-hidden overflow-y-auto transition-all pt-18 pb-18 duration-600 scroll-smooth">

                <slot />
                <set-default-vendor-currency-modal ref="set_default_vendor_currency_modal" />
            
            </div>

        </div>

        <div class="fixed" >            
            <title-bar />            
        </div>

        <!-- left -->        
        <div class="fixed flex min-h-screen antialiased ltr:left-0 rtl:right-0">            
            <sidebar-menu />            
        </div>

    </div>
</template>

<script setup>

import { onMounted, watch,  ref } from "vue";
import TitleBar from "@vendorPanel/vendor/components/layouts/titlebar/TitleBar.vue";
import SidebarMenu from "@vendorPanel/vendor/components/layouts/sidebar/SidebarMenu.vue";
import { usePage } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import SetDefaultVendorCurrencyModal from "@/Components/Core/Modals/SetDefaultVendorCurrencyModal.vue";
import { useThemeStore } from "../../../../../../../../../resources/js/store/Utilities/ThemeStore";
import { useLanguageStore } from "../../../../../../../../../resources/js/store/Localization/LanguageStore";
import { useSideMenuStore } from "../../../../../../../../../resources/js/store/Menu/SideMenuStore";
import { useNotificationStore } from "../../../../../../../../../resources/js/store/Notification/NotificationStore";

// Assigning $page.props to a local constant
const { props } = usePage();

onMounted(() => {

    // Hide The Initial Loading
    var loading = document.getElementById("home_loading__container");
    loading.style.display = "none";
        
    if(currencyId.value == "" || currencyId.value == null){
        set_default_vendor_currency_modal.value.openModal();
    }

    themeStore.initDarkMode();    
    languageStore.initActiveLangauge();

});

// Init Stores
const notificationStore = useNotificationStore();
const themeStore = useThemeStore();
const languageStore = useLanguageStore();
const sideMenuStore = useSideMenuStore();

// Main Actions
notificationStore.initFirebase(props.firebaseConfig);
const set_default_vendor_currency_modal = ref();
const currencyId = ref(props.currencyId);

// Init Route and Menu Group
const currentRouteArr = usePage().props.currentRoute;
const currentRoute = currentRouteArr.split(".")[0];
const menugroup = usePage().props.vendorMenuGroups;
sideMenuStore.selectActiveMenu(currentRoute, menugroup);

// @todo may be we don't need
watch(()=>usePage().props.currentRoute,(newValue, oldValue)=>{
    if(newValue != 'vendor_currency.index' && props.currencyId == null){
        set_default_vendor_currency_modal.value.openModal();
        console.log(newValue)
    }
});

// @todo may be we don't need
watch(()=>usePage().props.currentVendorId,(newValue, oldValue)=>{
    if(props.currencyId == null){
        this.set_default_vendor_currency_modal.openModal();
    }
});
        
router.on('start', (event) => {
    sideMenuStore.setSideMenuOpenFlag(false);
})

function clickOutsideSidebar() {
    sideMenuStore.setSideMenuOpenFlag(false);
}

</script>


