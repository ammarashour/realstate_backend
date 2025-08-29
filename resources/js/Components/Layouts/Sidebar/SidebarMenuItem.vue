<template>
  <div class="relative" v-if="dataReady">

    <div v-if="group.module != ''" @click="clickSidebarTab">
        <div v-if="group.is_dropdown == 1">
            <side-bar-tab 
                v-if="searchterm==null?true:group.module.filter((module) => $t(module.module_lang_key).toLowerCase().trim().includes(searchterm.toLowerCase().trim())).length > 0"
                :dropdownActive="group.sub_menu_lang_key == dropDown" :name='$t(group.sub_menu_lang_key)' :showIcon="true"
                :icon="group.icon.icon_name" :noti="group.sub_menu_noti" :hasModule='true' />
        </div>
    </div>
    <div v-else>
        <div v-if="group.is_dropdown != 1">
            <side-bar-tab
                v-if="searchterm==null?true:$t(group.sub_menu_lang_key).toLowerCase().trim().includes(searchterm.toLowerCase().trim())"
                :dropdownActive="group.sub_menu_lang_key == dropDown" :name="$t(group.sub_menu_lang_key)" :showIcon="true"
                :icon="group.icon.icon_name" :noti="group.sub_menu_noti" :showGroupIcon="false"
                :url="group.route_name ? group.route_name.route_name : ''" />
        </div>
    </div>

    <!-- dropdown item -->
    <div v-if="group.module != ''">

      <div v-if="group.sub_menu_lang_key == dropDown" class="mb-2 transition ease-in-out delay-150" :class="!sideMenuStore.isFullSideMenu ? 'sm:hidden' : ''">
        <div v-if="searchterm==null">
          <div class="mt-2" v-for="module in group.module" :key="module.id">
            <sidebar-sub-tab :name='$t(module.module_lang_key)'
              :url="module.route_name ? module.route_name.route_name : ''" :showGroupIcon="false"
              :noti='module.module_noti' />
          </div>
        </div>
        <div v-else>
          <div class="mt-2" v-for="module in group.module.filter((module) => $t(module.module_lang_key).toLowerCase().trim().includes(searchterm.toLowerCase().trim()))"
            :key="module.id">
            <sidebar-sub-tab :name='$t(module.module_lang_key)'
              :url="module.route_name ? module.route_name.route_name : ''" :showGroupIcon="false"
              :noti='module.module_noti' />
          </div>
        </div>

      </div>
    </div>
  </div>

</template>

<script>

import { ref, onMounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3';
import SideBarTab from '@/Components/Layouts/Sidebar/SideBarTab.vue'
import SidebarSubTab from '@/Components/Layouts/Sidebar/SidebarSubTab.vue'
import { useSideMenuStore } from '../../../store/Menu/SideMenuStore';

export default {
  props: ["group", "searchterm",'dropDown'],
  components: { Link, Head, SideBarTab, SidebarSubTab },
  setup(props , { emit}) {

    const sideMenuStore = useSideMenuStore();    
    const dataReady = ref(false);

    onMounted(() => {
                    
            setTimeout(() => {     
                let subMenuLangKey = sideMenuStore.findActiveMenu(props.group)
                if( subMenuLangKey != "") {
                    emit('update:dropDown', subMenuLangKey); 
                }

                dataReady.value = true;
                
                // if(!sideMenuStore.isSidebarActiveMenuFound) {
                //     if (props.group && props.group.module && props.group.module != '') {
                //         for (let i = 0; i < props.group.module.length; i++) {
                            
                //             if (props.group.module[i].module_lang_key == sideMenuStore.sidebarActive) {
                //                 emit('update:dropDown', props.group.sub_menu_lang_key); 
                //                 sideMenuStore.isSidebarActiveMenuFound = true;                       
                //                 break;
                //             }                    
                //         }
                //         dataReady.value = true;
                //     } else {
                //         dataReady.value = true;
                //     }
                // }else {
                //     dataReady.value = true;
                // }
            }, 50);
        

    });

    function clickSidebarTab(){
        if(props.group.sub_menu_lang_key != props.dropDown){
            emit('update:dropDown', props.group.sub_menu_lang_key);
        }else{
            emit('update:dropDown', '0');
        }
        
    }

    return {
        sideMenuStore,
        dataReady,
        clickSidebarTab
    }

  }
}
</script>
