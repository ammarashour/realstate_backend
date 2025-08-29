<template>
        <ps-label>
        <button type="submit"
            @click="menuClick"
            class="flex justify-between w-full cursor-pointer "
            :class="{ 'justify-start ': sideMenuStore.isFullSideMenu, 'sm:justify-center ': !sideMenuStore.isFullSideMenu,  
            [activeTheme] :  (hasModule == false && $t(sideMenuStore.sidebarActive) == name) || (hasModule == true && dropdownActive == true), 
            [defaultTheme] : !((hasModule == false && $t(sideMenuStore.sidebarActive) == name) || (hasModule == true && dropdownActive == true)), 
            [rounded] : true ,[padding] : true }">
            <div class="relative flex flex-row items-center">
                <div class="relative ">
                    <div v-if="noti != ''" :class="!sideMenuStore.isFullSideMenu ? '' : 'hidden'" class="absolute w-1.5 h-1.5 ms-3 bg-red-500 rounded-full"></div>
                    <div class="flex flex-col items-center" >
                        <ps-icon class="mx-auto my-auto" v-if="showIcon" :w="!sideMenuStore.isFullSideMenu ? '20' : '24'" :h="!sideMenuStore.isFullSideMenu ? '20' : '24'" :name="icon" />
                    </div>
                </div>
                <span :class="!sideMenuStore.isFullSideMenu ? 'sm:hidden' : ''" class="text-base ms-5 me-2 text-start">
                    {{ name }} 
                </span>
            </div> 
            <div :class="!sideMenuStore.isFullSideMenu ? 'sm:hidden' : ''" class="flex justify-center my-auto" >
                <ps-label v-if="noti != ''" class="text-center rounded-full ms-2 me-2 text-xxs"
                :class="{'text-primary-500' : $t(sideMenuStore.sidebarActive) == name,'bg-primary-500  text-white' : $t(sideMenuStore.sidebarActive) != name, 'w-6 h-6 pt-1.5' : parseInt(noti) > 99 , 'w-5 h-5 pt-1': parseInt(noti) > 9, 'w-4 h-4 pt-0.5': parseInt(noti) < 9 }">
                    {{ parseInt(noti) > 99  ? '99+' : noti }}
                </ps-label>
                <div v-if="showGroupIcon" class="">
                    <ps-icon v-if="!dropdownActive" name="downChervon" />
                    <ps-icon v-else name="upChervon" class="me-1" />
                </div>

            </div>
        </button>
        <ps-error-dialog ref="ps_error_dialog" />
    </ps-label>

</template>

<script>

import { ref } from 'vue';
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import { router } from '@inertiajs/vue3';
import PsErrorDialog from "@/Components/Core/Dialog/PsErrorDialog.vue";
import { useSideMenuStore } from '../../../store/Menu/SideMenuStore';

export default {
    components : {
        PsLabel,
        PsIcon,
        PsErrorDialog
    },
    props: {
        icon: {
            type: String,
            default: '',
        },
        hasModule: {
            type: Boolean,
            default: false,
        },
        showIcon: {
            type: Boolean,
            default: false,
        },
        name: {
            type: String,
            default: '',
        },
        noti: {
            type: String,
            default: '',
        },
        showGroupIcon: {
            type: Boolean,
            default: true,
        },
        defaultTheme: {
            type: String,
            default: "text-secondary-800 bg-primary-50 hover:bg-primary-200 dark:hover:bg-primary-200 dark:hover:text-secondary-800 dark:text-secondary-100 dark:bg-secondary-900 ",
        },
        activeTheme: {
            type: String,
            default: "text-secondary-50 bg-primary-500 dark:text-secondary-900",
        },
        rounded: {
            type: String,
            default: 'rounded-lg',
        },
        padding: {
            type: String,
            default: 'p-2',
        },
        url: {
            type: String,
            default: '',
        },
        dropdownActive: {
            type: Boolean,
            default: false,
        },
    },
    setup(props){

        const sideMenuStore = useSideMenuStore();
        const ps_error_dialog = ref();

        function handleSidebarFull(v){
            sideMenuStore.setFullSideMenuFlag(v);
        }

        function menuClick(){
            if(!props.hasModule){
                
                try{
                    router.get(route(props.url));
                    sideMenuStore.setSidebarActive(props.name);
                }
                catch(err){
                    ps_error_dialog.value.openModal("Can't go to "+props.url,err.message,'Retry',
                    ()=>{
                        menuClick();
                    });

                }
                
                
                
            }
            
            handleSidebarFull(true);
        }

        return{
            sideMenuStore,
            handleSidebarFull,
            menuClick,
            ps_error_dialog,

        }

    }
};
</script>


