<template>

    <Head :title="$t('download_db_module')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('download_db_module')}}</ps-label-header-6>
                </div>
                <!-- card header end -->

                <!-- card body start -->

                <div class="mt-6">

                   <div class="">
                       <div class="border border-1 rounded p-4 w-1/2">
                           <div class="h-auto">
                               <div class="mb-2">
                                   <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('download_db_desc_title')}}</ps-label-header-6>
                               </div>
                               <div>
                                   <ps-label class="text-gray-800 font-medium">{{$t('download_db_desc')}}</ps-label>
                               </div>
                               <div v-if="can.deleteDataReset" class="flex flex-row justify-start mt-6">
                                    <a :href="route('download_db.downloadDB')">
                                        <ps-button class="bg-primary-500 hover:bg-primary-500">{{$t('download_btn')}}</ps-button>
                                    </a>
                               </div>
                           </div>
                       </div>
                   </div>

                </div>
                <!-- card body end -->
            </div>
        </ps-card>

        <ps-danger-dialog-with-input ref="ps_danger_dialog_with_input"></ps-danger-dialog-with-input>

    </ps-layout>
</template>

<script>
import {defineComponent, reactive, ref} from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import { Head, useForm } from "@inertiajs/vue3";
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";
import { trans } from 'laravel-vue-i18n';
import PsDangerDialogWithInput from "@/Components/Core/Dialog/PsDangerDialogWithInput.vue";

export default defineComponent({
    name: "Edit",
    components: {
        Head,
        PsInput,
        PsLabel,
        PsButton,
        PsTextarea,
        PsLabelHeader4,
        PsBreadcrumb2,
        PsIcon,
        PsTable2,
        PsDangerDialogWithInput
    },
    layout: PsLayout,
    props: ['errors', 'menus', 'status', 'activityLogs', 'can'],
    setup(){
        const ps_danger_dialog_with_input = ref();

        let form = useForm({
           confirmText : "",
        });

        function handleDownloadDB(name) {
            this.$inertia.put(route('download_db.downloadDB'));
        }


        return {
            ps_danger_dialog_with_input,
            handleDownloadDB,
            form
        }
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('download_db_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },

})
</script>
