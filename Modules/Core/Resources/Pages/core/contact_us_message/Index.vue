<template>

    <Head :title="$t('contact_us_message_module')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 mb-6 rounded-t-xl">
            <ps-label-header6>{{$t('core__be_messages')}}</ps-label-header6>
        </div>

        <div v-if="!showDelete">

            <div class="flex justify-end mb-4">
                <ps-text-button v-if="can.updateContact" @click="makeAllRead" padding="p-1.5">
                    <div class="flex">
                        <ps-icon name="right" class="mt-1.5" fillone="#6366F1" />
                        <ps-label  class="ms-1 my-auto cursor-pointer" textColor="text-primary-500">{{$t('core__be_mark_all_as_read')}}</ps-label>
                    </div>
                </ps-text-button>

                <ps-text-button v-if="can.deleteContact" @click="showDelete = !showDelete"  padding="p-1.5">
                    <div class="flex">
                        <span  class="bg-red-400 text-white p-1 rounded-lg"><ps-icon name="trash"  w="16" h="16" /></span>
                        <ps-label class="ms-1 my-auto " textColor="text-primary-500">{{$t('btn_delete')}}</ps-label>
                    </div>
                </ps-text-button>

            </div>

            <div v-for="contact in contacts" :key="contact.id" class="cursor-pointer">
                <ps-activity @click="goToDetail(contact.id)" :theme="contact.is_read == 0 ? 'bg-primary-50 dark:bg-primary-900': ' bg-secondary-50 dark:bg-secondary-900'" :icon="false" h="h-26">
                    <template #content>
                        <!-- <img class="" src="https://images.unsplash.com/photo-1491528323818-fdd1faba62cc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt=""/> -->
                        <!-- <div class="flex items-end pt-4"> -->
                        <div class="h-8 w-8 sm:w-12 sm:h-12">
                            <img  
                            v-lazy=" { src: $page.props.uploadUrl + '/' + contact.owner?.user_cover_photo, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_profile.png' }"
                             width="20" height="20"
                                    class="mx-2 inline-block h-8 w-8 sm:w-12 sm:h-12 rounded-full ring-2 ring-white object-cover" alt="Profile photo" @error="defaultProfileImg" />
                            <!-- <img v-else :src="defaultProfileImg" width="20" height="20"
                                    class="mx-2 inline-block h-8 w-8 sm:w-12 sm:h-12 rounded-full ring-2 ring-white object-cover" alt="Profile photo" @error="defaultProfileImg" /> -->
                        </div>
                        <!-- </div> -->
                        <span class="w-full ms-4">
                            <span class="flex justify-between text-sm ">
                                <ps-label class="text-sm lg:text-base">{{contact.contact_name}}</ps-label>
                                <ps-label-caption-3>{{moment(contact.added_date).format($page.props.dateFormat)}}</ps-label-caption-3>
                                </span>
                                <ps-label-caption-3>by: {{contact.contact_email}}</ps-label-caption-3>
                        </span>
                    </template>
                </ps-activity>
            </div>
        </div>
        <div v-else>
            <div class="flex justify-between mb-4">
                 <div class="flex flex-row items-center" >
                    <input v-model="selectAll" @change="toogleSelectAll()" class="me-2 border-1 border-primary-500 rounded-full" type="checkbox" >
                    <ps-label class="text-base font-light" textColor="text-primary-500" > {{$t('core__be_select_all')}} </ps-label>
                </div>
                <div class="flex flex-row">
                    <ps-button @click="showDelete = !showDelete" textSize="text-sm" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{ $t('core__be_btn_cancel') }}</ps-button>
                    <ps-button @click="clickDelete" class="transition-all duration-300 min-w-3xs" colors="bg-red-500 text-white" padding="px-5 py-2" rounded="rounded" hover="" focus="" >
                        {{ $t('btn_delete') }}
                    </ps-button>
                </div>
            </div>

            <div v-for="contact in contacts" :key="contact.id" class="cursor-pointer flex flex-row items-center justify-center" >

                <ps-activity @click="booleanContacts[contact.id] = !booleanContacts[contact.id]" :theme="contact.is_read == 0 ? 'bg-primary-50 dark:bg-primary-900': ' bg-secondary-50 dark:bg-secondary-900'" :icon="false" h="h-26">
                    <template #content>
                        <input v-model="booleanContacts[contact.id]"  class="me-2 border-1 border-secondary-200 rounded" type="checkbox" >
                        <!-- <img class="" src="https://images.unsplash.com/photo-1491528323818-fdd1faba62cc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt=""/> -->
                        <!-- <div class="flex items-end pt-4"> -->
                        <div class="h-8 w-8 sm:w-12 sm:h-12">
                            <img 
                            v-lazy=" { src: $page.props.uploadUrl + '/' + contact.owner?.user_cover_photo, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_profile.png' }"
                             width="20" height="20"
                                    class="mx-2 inline-block h-8 w-8 sm:w-12 sm:h-12 rounded-full ring-2 ring-white object-cover" alt="Profile photo" @error="defaultProfileImg" />
                            <!-- <img v-else :src="defaultProfileImg" width="20" height="20"
                                    class="mx-2 inline-block h-8 w-8 sm:w-12 sm:h-12 rounded-full ring-2 ring-white object-cover" alt="Profile photo" @error="defaultProfileImg" /> -->
                        </div>
                        <!-- </div> -->
                        <span class="w-full ms-4">
                            <span class="flex justify-between text-sm ">
                                <ps-label class="text-sm lg:text-base">{{contact.contact_name}}</ps-label>
                                <ps-label-caption-3>{{moment(contact.added_date).format($page.props.dateFormat)}}</ps-label-caption-3>
                                </span>
                                <ps-label-caption-3>by: {{contact.contact_email}}</ps-label-caption-3>
                        </span>
                    </template>
                </ps-activity>
            </div>
        </div>
        <ps-danger-dialog ref="ps_danger_dialog" />
         <ps-warning-dialog ref="ps_warning_dialog" />
    </ps-layout>
</template>

<script>
import { defineComponent, ref,reactive } from 'vue'
import { Head,useForm } from '@inertiajs/vue3';
import PsLayout from "@/Components/PsLayout.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsLabelCaption3 from "@/Components/Core/Label/PsLabelCaption3.vue";
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextButton from "@/Components/Core/Buttons/PsTextButton.vue";
import PsAlert from "@/Components/Core/Alert/PsAlert.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsWarningDialog from "@/Components/Core/Dialog/PsWarningDialog.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";
import PsActivity from '@/Components/Core/Activity/PsActivity.vue';
import moment from 'moment';
import { trans } from 'laravel-vue-i18n';
import { router } from '@inertiajs/vue3';

export default defineComponent({
    name: "Index",
    components: {
        Head,
        PsLabel,
        PsButton,
        PsAlert,
        PsBreadcrumb2,
        PsDangerDialog,
        PsIcon,
        PsBannerIcon,
        PsLabelHeader6,
        PsActivity,
        PsWarningDialog,
        PsLabelCaption3,
        PsTextButton
    },
    layout: PsLayout,
    data(){
        return{
            moment: moment,
        }
    },
    props: ['can','contacts', 'checkPermission', 'defaultProfileImg'],
    setup(props) {
        // For data table
        const ps_danger_dialog = ref();
        const showDelete = ref(false);
        let booleanContacts = reactive([]);

        for(let i=0; i<props.contacts.length; i++){
            booleanContacts[props.contacts[i].id] = false;
        }

        const ps_warning_dialog = ref();

        let selectAll = ref(false);

        function toogleSelectAll(){
            for(let i=0; i<props.contacts.length; i++){
                if(selectAll.value == false){
                    booleanContacts[props.contacts[i].id] = false;
                }else{
                    booleanContacts[props.contacts[i].id] = true;
                }

            }
        }

         function makeAllRead(){
            ps_warning_dialog.value.openModal(
                trans('core__warning'),
                trans('core__comfirm_to_mark_as_read'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    router.put(route("contact.allasread"));
                },
                () => { }
            );
    }
    function goToDetail(id){
         router.get(route("contact.edit",id));
    }

    function clickDelete(){

        let contactToDel = [];
        for(let i=0; i<props.contacts.length; i++){
            if(booleanContacts[props.contacts[i].id] == true){
                contactToDel.push(props.contacts[i].id);
            }
        }
        let form = useForm(
                {
                    ids: contactToDel
                }
            )

        ps_danger_dialog.value.openModal(
                trans('core__delete'),
                trans('core__comfirm_to_delete_contact_multiple'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    router.delete(route("contact.multiDelete", form));
                    showDelete.value = !showDelete.value;
                },
                () => { }
            );
    }


        return {
            clickDelete,
            booleanContacts,
            toogleSelectAll,
            selectAll,
            showDelete,
            goToDetail,
            ps_danger_dialog,
            makeAllRead,
            ps_warning_dialog
        }
    },
    methods: {
        handleDetail(id) {
            this.$inertia.get(route('contact.show', id));
        },
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('contact_us_message_module'),
                    color: "text-primary-500"
                }
            ]
        }
    }
})
</script>
