<template>
  <div ref="sidebarMenuC"
    class="fixed h-screen p-4 overflow-x-hidden overflow-y-scroll transition-all bg-primary-50 dark:border-gray-600 dark:bg-secondary-900 text-secondary-800 dark:text-secondary-100 duration-30 xl:relative scroll-smooth"
    :class="{ 'w-76': sideMenuStore.isFullSideMenu,'w-76 xl:w-20': !sideMenuStore.isFullSideMenu, 'top-0 start-0': sideMenuStore.isSideMenuOpen,'top-0 -left-76 xl:left-0': !sideMenuStore.isSideMenuOpen,}">
    <!-- sidebar title -->

    <ps-dropdown h="h-auto" align="left" class='w-full mt-1 lg:mt-2'>
        <template #select>
            
            <div  class="flex flex-row justify-center mt-4 cursor-pointer ms-1 xl:ms-0 " :class="!sideMenuStore.isFullSideMenu ? '' : 'ps-2 pe-2'">
                <div v-if="$page.props.currentVendor?.logo != null" class="rounded-lg pe-1" :class="!sideMenuStore.isFullSideMenu ? 'w-8 h-8' : 'h-12 w-12'">
                    <img  v-lazy=" { src: $page.props.uploadUrl + '/' + $page.props.currentVendor?.logo.img_path, loading: $page.props.sysImageUrl+'/default_vendor_shop.png', error: $page.props.sysImageUrl+'/default_vendor_shop.png' }"

                    class="object-contain rounded-lg" :class="!sideMenuStore.isFullSideMenu ? 'w-8 h-8' : 'h-12 w-12'"
                    />
                </div>
                <div v-else class="rounded-lg pe-1" :class="!sideMenuStore.isFullSideMenu ? 'w-8 h-8' : 'h-12 w-12'">
                    <img  :src="$page.props.sysImageUrl+'/default_vendor_shop.png'"

                    class="object-contain rounded-lg" :class="!sideMenuStore.isFullSideMenu ? 'w-8 h-8' : 'h-12 w-12'"
                    />
                </div>

            </div>
            <div  class="flex flex-row mt-4 font-semibold cursor-pointer" :class="!sideMenuStore.isFullSideMenu ? 'xl:hidden' : ''">
                <div  class="flex flex-row items-center justify-center flex-grow" >
                    <ps-label class="text-base font-semibold truncate w-52" > {{ $page.props.currentVendor?.name }} </ps-label>
                </div>
                <ps-icon name="downChervon" />
            </div>
        </template>
        <template #list>
            <div class="rounded-md shadow-xs " :class="!sideMenuStore.isFullSideMenu ? 'xl:w-10 w-64' : 'w-64'">
                <div class="z-30 pt-2 ">
                    <div v-for="vendor in $page.props.vendorList" :key="vendor.id"
                        class="flex flex-row items-center w-full px-2 py-4 cursor-pointer hover:bg-primary-000 dark:hover:bg-secondary-700"
                        @click="clickVendor(vendor.id)"
                        >
                        <img  v-lazy=" { src: $page.props.uploadUrl + '/' + vendor?.logo?.img_path, loading: $page.props.sysImageUrl+'/default_vendor_shop.png', error: $page.props.sysImageUrl+'/default_vendor_shop.png' }"
                            :class="!sideMenuStore.isFullSideMenu ? 'xl:mx-0.5 mx-2' : 'mx-2'"
                            class="object-contain w-8 h-8 rounded-lg " 
                            />
                        <div :class="!sideMenuStore.isFullSideMenu ? 'xl:hidden' : ''">
                            <ps-label class="w-48 truncate" 
                                :class="vendor.id == $page.props.currentVendor?.id ? ' font-semibold' : ''">
                                {{ vendor.name }} </ps-label>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </ps-dropdown>

    <!-- search -->
    <div class="mt-5 mb-5" :class="sideMenuStore.isFullSideMenu ? 'flex' : 'flex xl:hidden'">
        <ps-input-with-right-icon v-model:value="searchterm"
        theme="bg-white dark:bg-secondaryDark-black border-none rounded-lg placeholder-secondary-800 dark:placeholder-secondary-200" class="flex w-full rounded-full" :placeholder="$t('core__be_search')" >
            <template #icon >
                <ps-icon name="search" class='cursor-pointer' theme="text-secondary-800 dark:text-secondary-200" />
            </template>
        </ps-input-with-right-icon>
    </div>
    <div class="flex items-center w-full p-1 mt-6 mb-8 rounded-lg text-secondary-800 bg-primary-50 hover:bg-primary-200 dark:hover:bg-secondary-700 dark:text-secondary-300 dark:bg-secondary-900"
    :class="sideMenuStore.isFullSideMenu ? 'hidden' : 'hidden xl:flex'"
     @click="handleSidebarFull">
        <ps-icon name="search" class='cursor-pointer' w="20" h="20" />
    </div>
    <!-- sidebar navigation -->
    <div class="grid grid-cols-1 divide-y-4">
        <div class="">
            
            <div v-for="menugroup in $page.props.vendorMenuGroups" :key="menugroup.id">
                <ps-label :class="{ 'flex': sideMenuStore.isFullSideMenu, 'hidden' : !sideMenuStore.isFullSideMenu }" class="mt-4 mb-2" v-if="menugroup.sub_menu_group.length != 0 && searchterm==null && menugroup.is_invisible_group_name != 1"> {{ $t(menugroup.group_lang_key) }} </ps-label>
                <ps-label :class="{ 'flex': sideMenuStore.isFullSideMenu, 'hidden' : !sideMenuStore.isFullSideMenu }" class="mt-4 mb-2" v-else-if="menugroup.sub_menu_group.length != 0 && menugroup.is_invisible_group_name != 1">
                    <span v-if="$t(menugroup.group_lang_key).toLowerCase().trim().includes(searchterm.toLowerCase().trim())">
                        {{ $t(menugroup.group_lang_key) }}
                    </span>
                    <span v-else-if="menugroup.sub_menu_group.filter(
                        (sub)=>$t(sub.sub_menu_lang_key).toLowerCase().trim().includes(searchterm.toLowerCase().trim()))
                        .length>0">
                        {{ $t(menugroup.group_lang_key) }}
                    </span>
                    <span v-else-if="menugroup.sub_menu_group.filter(
                        (sub)=> sub.is_dropdown==1 && sub.module.filter((module)=>
                            $t(module.module_lang_key).toLowerCase().trim().includes(searchterm.toLowerCase().trim())
                        ).length>0).length>0">
                        {{ $t(menugroup.group_lang_key) }}
                    </span>
                    <span v-else></span>
                </ps-label>
                <div v-for="group in menugroup.sub_menu_group" :key="group.id" class="mt-2">
                    <sidebar-menu-item v-model:dropDown="dropDownOpen" :group="group" :searchterm="searchterm" ></sidebar-menu-item>
                    <!-- <sidebar-menu-item v-if="group.module.length > 0 && group.is_dropdown == 1" :group="group" :searchterm="searchterm" ></sidebar-menu-item> -->
                    <!-- <sidebar-menu-item v-if="group.is_dropdown == 0" :group="group" :searchterm="searchterm" ></sidebar-menu-item>   -->
                </div>


            </div>
        </div>
        <div class="flex items-center justify-center py-2 my-2 ">
                    <ps-label
                        class="text-xs font-regular"
                        textColor="text-gray-500"
                        :class="!sideMenuStore.isFullSideMenu ? 'xl:hidden' : 'ms-1 mt-1'">
                        {{ $t('core__be_version') }} {{ $page.props.backendSetting.backend_version_no }}
                    </ps-label>
        </div>
    </div>
  </div>
</template>

<script>

import { Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';
import SidebarMenuItem from '@/Components/Layouts/Sidebar/SidebarMenuItem.vue';
import SideBarTab from '@/Components/Layouts/Sidebar/SideBarTab.vue'
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsInputWithRightIcon from '@/Components/Core/Input/PsInputWithRightIcon.vue';
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import { router } from '@inertiajs/vue3';
import { useSideMenuStore } from '../../../../../../../../../resources/js/store/Menu/SideMenuStore';

export default {
  components:{ Link, SidebarMenuItem,SideBarTab,PsLabel,PsIcon,PsInputWithRightIcon,PsDropdown},
  setup(){
    const searchterm = ref('')
    const sideMenuStore = useSideMenuStore();    
    const dropDownOpen = ref('0');

    function handleSidebarNavOpen(v){
        sideMenuStore.setSideMenuOpenFlag(v);
    }
    function handleSidebarFull(v){
        sideMenuStore.setFullSideMenuFlag(v);
    }

    const sidebarMenuC = ref(null);

    function handleScroll(){
        localStorage.sidebarScroll = sidebarMenuC.value.scrollTop;
    }

    onUnmounted(() => {
        // sidebarMenuC.value.removeEventListener('scroll', handleScroll);
    })

    onMounted(() => {
        sidebarMenuC.value.addEventListener("scroll", handleScroll);

        if(localStorage.sidebarScroll != null){
            setTimeout(() => {
                sidebarMenuC.value.scrollTo(0,parseInt(localStorage.sidebarScroll));
            }, 1000);
        }
    })

    function clickVendor(id){
        router.put(route("vendor.changeVendor", id));
    }

    return{
        searchterm,
        handleSidebarFull,
        dropDownOpen,
        handleSidebarNavOpen,
        sidebarMenuC,
        clickVendor,
        sideMenuStore
    }
  },
};
</script>


