<template>
    <!-- <ps-label>
    <div @click="clickedButton"

        class="flex justify-between w-full cursor-pointer "
        :class="{ 'justify-start': sidebarFull, 'sm:justify-center': !sidebarFull,    [activeTheme] : sidebarActive == name, [defaultTheme] : sidebarActive != name, [rounded] : true, [padding] : true,  }">
        <div class="relative items-center ps-10"> -->
            <!-- <ps-icon v-if="showIcon" :name="icon" viewBox="0 0 24 24" w="24" h="24" /> -->
            <!-- <span :class="!sidebarFull ? 'sm:hidden' : ''" class="text-base me-2">
                {{ name }}
            </span>
        </div>
        <div :class="!sidebarFull ? 'sm:hidden' : ''" class="flex justify-center my-auto" >
            <ps-label v-if="noti != ''" class="text-center rounded-full ms-2 me-2 text-xxs"
            :class="{'text-primary-500' : sidebarActive == name,'bg-primary-500  text-white' : sidebarActive != name, 'w-6 h-6 pt-1.5' : parseInt(noti) > 99 , 'w-5 h-5 pt-1': parseInt(noti) > 9, 'w-4 h-4 pt-0.5': parseInt(noti) < 9 }">
                {{ parseInt(noti) > 99  ? '99+' : noti }}
            </ps-label>
            <div v-if="showGroupIcon" class="">
                <ps-icon v-if="!isDropdownActive" name="downChervon"  />
                <ps-icon v-else name="upChervon" />
            </div>
        </div>
    </div>
    <ps-error-dialog ref="ps_error_dialog" />
    </ps-label> -->
    <div />
</template>

<script>

// import { computed,ref } from 'vue'
// import { useStore } from 'vuex'
// import { router } from '@inertiajs/vue3';
// import PsLabel from "@/Components/Core/Label/PsLabel.vue";
// import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
// import PsErrorDialog from "@/Components/Core/Dialog/PsErrorDialog.vue";

// export default {
//     components: {PsLabel,PsIcon,PsErrorDialog },
//   props: {
//     icon: {
//         type: String,
//         default: '',
//     },
//     showIcon: {
//         type: Boolean,
//         default: false,
//     },
//     name: {
//         type: String,
//         default: '',
//     },
//     url: {
//         type: String,
//         default: '',
//     },
//     noti: {
//         type: String,
//         default: '',
//     },
//     showGroupIcon: {
//         type: Boolean,
//         default: true,
//     },
//     defaultTheme: {
//         type: String,
//         default: "text-secondary-800 bg-primary-50 hover:bg-primary-200 dark:hover:bg-primary-200 dark:hover:text-secondary-800 dark:text-secondary-100 dark:bg-secondary-900 ",
//     },
//     activeTheme: {
//         type: String,
//         default: "text-secondary-50 bg-primary-500 dark:text-secondary-900",
//     },
//     rounded: {
//         type: String,
//         default: 'rounded-lg',
//     },
//     padding: {
//         type: String,
//         default: 'p-2',
//     },
//   },
//   setup(props){

//     const store = useStore();
//     const sidebarFull = ref(computed(() => store.getters.sidebarFull));
//     const sidebarActive = ref(computed(() => store.getters.sidebarActive));
//     const isDropdownActive = ref(false);
//     const ps_error_dialog = ref();

//     function handleSidebarFull(v){
//         store.dispatch('handleSidebarFull',v);
//     }

//     function clickedButton(){


//         try{
//             router.get(route(props.url));
//             store.dispatch('handleSidebarActive',props.name);
//             isDropdownActive.value = !isDropdownActive.value;
//             handleSidebarFull(true);
//         }
//         catch(err){
//             ps_error_dialog.value.openModal("Can't go to "+props.url,err.message,'Retry',
//                 ()=>{
//                     clickedButton();
//                 });

//         }


//     }

//     return{
//         sidebarFull,
//         sidebarActive,
//         isDropdownActive,
//         handleSidebarFull,
//         clickedButton,
//         ps_error_dialog

//     }

//   }
// };
</script>





