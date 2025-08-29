<template>
    
    <!-- Active license -->
    <small v-if="!(props.project.ps_license_code)" class="fixed top-0 z-50 w-full h-8 p-1 text-center bg-red-100 text-white-50">
        ⚠Please Activate Your Application!⚠
    </small>
    
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
                :style="[!$page.props.project.ps_license_code ? 'margin-top: 24px !important;' : '']"
                :class="{ 'xl:ms-76 ms-0': sideMenuStore.isFullSideMenu, 'ms-0 xl:ms-20': !sideMenuStore.isFullSideMenu }"
                class="h-screen px-4 overflow-x-hidden overflow-y-auto transition-all pt-18 pb-18 duration-600 scroll-smooth">
                
                    <!-- Version Update Notificaiton Card -->
                    <notification-card 
                        v-if="appInfoStore.isNewVersionAvailable"
                        :title="$t('core_be__version_update_noti_title')"
                        :description="$t('core_be__version_update_noti_desc')"
                        icon="hond"       
                        @dismiss="($value) => appInfoStore.isNewVersionAvailable = $value"                  
                    >
                        <template v-slot:action>
                            <ps-button class="mt-6" rounded="rounded">
                                <Link :href="route('NextLaravelUpdater::welcome')">
                                    {{ $t("btn_update") }}
                                </Link>
                            </ps-button>
                        </template>
                    </notification-card>                     

                    <!-- Project Change Notificaiton Card -->
                    <notification-card 
                        v-if="appInfoStore.isProjectChanged"
                        :title="$t('core_be__project_change_noti_title')"
                        :description="$t('core_be__project_change_noti_desc')"
                        icon="project"     
                        @dismiss="($value) => appInfoStore.isProjectChanged = $value"
                    >
                        <template v-slot:action>
                            <ps-button class="mt-6" rounded="rounded" @click="replaceTable()">
                                {{ $t("setup") }}
                            </ps-button>
                        </template>
                    </notification-card>   

                    <!-- Connection Notificaiton Card -->
                    <notification-card 
                        v-if="appInfoStore.isConnectedWithBuilder"
                        :title="$t('core_be__losing_connection_noti_title')"
                        :description="$t('core_be__losing_connection_noti_desc')"
                        icon="connection"       
                        @dismiss="($value) => appInfoStore.isConnectedWithBuilder = $value"                  
                    >
                        <template v-slot:action>
                            <ps-button class="mt-6" rounded="rounded">
                                <a href="https://www.docs.panacea-soft.com/psx-mpc/manual/web-manual/api-token-deleted-or-updated" target="_blank">{{ $t("core_be__open_token_key_documentation") }}</a>
                            </ps-button>
                        </template>
                    </notification-card>  

                    <!-- Content Slot -->
                    <slot />
                    
            </div>

        </div>

        <div class="fixed" :style="[!$page.props.project.ps_license_code ? 'top: 24px !important;' : '']" >
            <title-bar />
        </div>

        <!-- left -->
        <div class="fixed flex min-h-screen antialiased ltr:left-0 rtl:right-0"
            :style="[!$page.props.project.ps_license_code ? 'top: 24px !important;' : '']">
            <sidebar-menu />
        </div>

    </div>
    
    <ps-license-activate-modal 
        v-if="psLicenseActivationModalVisible"
        :isCountDownShow=true 
        :hasError="hasError" :logMessages="logMessages" 
        :status="status" :purchased_code="purchased_code" 
        :project="$page.props.project" :errors="errors" 
        :systemCode2="systemCode2" ref="psLicenseActivationModalRef" />

</template>

<script setup>

import { onMounted, ref, defineAsyncComponent } from "vue";
import { Head, Link, usePage } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { router } from '@inertiajs/vue3'
import TitleBar from "@/Components/Layouts/TitleBar/TitleBar.vue";
import SidebarMenu from "@/Components/Layouts/Sidebar/SidebarMenu.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
// import PsLicenseActivateModal from '@/Components/Core/Modals/PsLicenseActivateModal.vue';
const PsLicenseActivateModal = defineAsyncComponent(() => import('@/Components/Core/Modals/PsLicenseActivateModal.vue'));
import NotificationCard from "@/Components/Layouts/Notification/NotificationCard.vue";
import { useAppInfoStore } from "@/store/AppInfo/AppInfoStore";
import { useThemeStore } from "../store/Utilities/ThemeStore";
import { useLanguageStore } from "../store/Localization/LanguageStore";
import { useSideMenuStore } from "../store/Menu/SideMenuStore";
import { useNotificationStore } from "../store/Notification/NotificationStore";

// Assigning $page.props to a local constant
const { props } = usePage();

onMounted(async () => {

    // Hide The Initial Loading
    var loading = document.getElementById("home_loading__container");
    loading.style.display = "none";
    
    // License Checking and Open the Activation Modal
    if(props.builderAppInfo != null && props.builderAppInfo.isValid == 0){
        
        psLicenseActivationModalVisible.value = true;
        await PsUtils.waitingComponent(psLicenseActivationModalRef);
        psLicenseActivationModalRef.value.openModal(trans('pls_activate_your_app'),'You have successfully imported the file.','Back',
        ()=>{
            console.log('open');
        },
        false);
    }
  
    themeStore.initDarkMode();    
    languageStore.initActiveLangauge();
    
});

const notificationStore = useNotificationStore();
notificationStore.initFirebase(props.firebaseConfig);

// Init Stores
const themeStore = useThemeStore();
const languageStore = useLanguageStore();
const sideMenuStore = useSideMenuStore();
const appInfoStore = useAppInfoStore();

// Init refs
const psLicenseActivationModalRef = ref();
const psLicenseActivationModalVisible = ref(false);

// Main Actions

// Init Route and Menu Group
const currentRouteArr = usePage().props.currentRoute;
const currentRoute = currentRouteArr.split(".")[0];
const menugroup = usePage().props.menuGroups;
sideMenuStore.selectActiveMenu(currentRoute, menugroup);
    
appInfoStore.checkNewVersionAvailable(props.builderAppInfo, props.checkVersionUpdate);
appInfoStore.checkProjectChanged(props.builderAppInfo);
appInfoStore.checkConnectionWithBuilder(props.builderAppInfo);

// Functions
function replaceTable() {
    router.get(route("table.replace"));
}

function clickOutsideSidebar() {
    sideMenuStore.setSideMenuOpenFlag(false);
}
 
router.on('start', (_) => {
    sideMenuStore.setSideMenuOpenFlag(false);
})

</script>


