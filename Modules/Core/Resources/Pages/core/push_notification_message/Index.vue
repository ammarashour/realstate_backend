<template>

    <Head :title="$t('push_notification_module')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <!-- alert banner start -->
        <ps-banner-icon  v-if="visible" :visible="visible"
        :theme="(status.flag)=='danger'?'bg-red-500':(status.flag)=='warning'?'bg-yellow-500':'bg-green-500'"
        :iconName="(status.flag)=='danger'?'close-circle':(status.flag)=='warning'?'alert-triangle':'rightalert'"
        class="text-white mb-5 sm:mb-6 lg:mb-8"
         iconColor="white">{{status.msg}}</ps-banner-icon>
        <!-- alert banner end -->

        <!-- data table start -->
        <ps-table2 :row="row" :search="search" :object="this.push_notification_messages" :colFilterOptions="colFilterOptions" :columns="columns" :sort_field="sort_field" :sort_order="sort_order" @FilterOptionshandle="FilterOptionshandle"
        @handleSort="handleSorting"  @handleSearch="handleSearching" @handleRow="handleRow">

         <!-- add new field start -->
            <template #button>
                <ps-button v-if="can.createPushNotificationMessage" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-2 font-semibold" />{{$t('core__be_new_push_notification')}}</ps-button>
            </template>
            <template #responsive_button>
                <ps-button v-if="can.createPushNotificationMessage" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-2 font-semibold" />{{$t('core__be_new_push_notification')}}</ps-button>
            </template>
            <!-- add new field end -->

            <template #tableActionRow="rowProps">
                <!-- For action (edit/delete) start -->
                <div class="flex flex-row " v-if="rowProps.field == 'action'">
                    <ps-button  :disabled="rowProps.row.authorization.delete ? false : true" @click="confirmDeleteClicked(rowProps.row.id)" colors="bg-red-400 text-white" padding="p-1.5"
                        hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>
                <!-- For action (edit/delete) end -->
            </template>
            <template #tableRow="rowProps">

                <div class="flex flex-row" v-if="rowProps.field == 'photo' ">

                    <img class="w-28 h-16 rounded" width="50" height="50"  
                    v-lazy=" { src: $page.props.thumb2xUrl+'/'+rowProps.row.cover?.img_path, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"
                     alt="Sunset in the mountains">
                </div>

                <ps-toggle  :disabled="rowProps.row.authorization.update ? false : true" v-if="rowProps.field == 'status'" :selectedValue="rowProps.row.status == 1 ? true : false" @click="handlePublish(rowProps.row.id)"></ps-toggle>

            </template>

        </ps-table2>
        <!-- data table end -->
    </ps-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { Head } from '@inertiajs/vue3';
import PsLayout from "@/Components/PsLayout.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";
import PsAlert from "@/Components/Core/Alert/PsAlert.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";
import { trans } from 'laravel-vue-i18n';
import { router } from '@inertiajs/vue3';

export default defineComponent({
    name: "Index",
    components: {
        Head,
        PsLabel,
        PsButton,
        PsTable2,
        PsAlert,
        PsBreadcrumb2,
        PsDangerDialog,
        PsToggle,
        PsIcon,
        PsBannerIcon,
    },
    layout : PsLayout,
    // props: ['push_notification_messages', 'status', 'thumb1xUrl', 'checkPermission', 'showPushNotificationMessageCols', 'showCoreAndCustomFieldArr', 'hideShowFieldForFilterArr'],
    props:{
            can:Object,
            status:Object,
            push_notification_messages:Object,
            hideShowFieldForFilterArr:Object,
            showCoreAndCustomFieldArr:Object,
            sort_field:{
                    type:String,
                    default:"",

                },
            sort_order:{
                type:String,
                default:'desc',
            },
            search:String,
        },
    setup(props) {
        // For data table
         let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
        const ps_danger_dialog = ref();
        let visible = ref(false)

        const colFilterOptions = ref();
        const columns = ref();

        function confirmDeleteClicked(id) {
            ps_danger_dialog.value.openModal(
                trans('core__be_delete_push_not'),
                trans('core__comfirm_to_delete_push_noti_row'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    this.$inertia.delete(route("push_notification_message.destroy", id),{
                    onSuccess: () => {
                        visible.value = true;
                        setTimeout(() => {
                            visible.value = false;
                        }, 3000);
                    },
                    onError: () => {
                        visible.value = true;
                        setTimeout(() => {
                            visible.value = false;
                        }, 3000);
                    },
                    })

                },
                () => { }
            );
        }
        function handleSorting(value){
            sort_field.value = value.field
            sort_order.value = value.sort_order
            handleSearchingSorting()
        }
        function handleSearching(value){
            search.value = value;
            let page=1;
            handleSearchingSorting(page);
        }
        function handleRow(value){
            handleSearchingSorting(1,value);
        }

        function handleSearchingSorting(page = null,row=null){
            router.get(route('push_notification_message.index'),
            {
                sort_field : sort_field.value,
                sort_order: sort_order.value,
                page:page ?? props.push_notification_messages.meta.current_page,
                row:row ?? props.push_notification_messages.meta.per_page,
                search:search.value,
            },
            {
                preserveScroll: true,
                preserveState:true,
            })
        }

        return {
             handleRow,
            handleSearchingSorting,
            handleSearching,
            handleSorting,
            visible,
            // ps_csv_modal,
            // globalSearchPlaceholder,
            columns, ps_danger_dialog,
            confirmDeleteClicked,
            colFilterOptions ,
            // csvUploadClicked,
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
                    label: trans('push_notification_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    created() {
        this.columns = this.showCoreAndCustomFieldArr.map(column => {
            return {
                action: column.action,
                field: column.field,
                label: trans(column.label),
                sort: column.sort,
                type: column.type
            };
        });

        this.colFilterOptions = this.hideShowFieldForFilterArr.map(columnFilterOption => {
            return {
                hidden: columnFilterOption.hidden,
                id: columnFilterOption.id,
                key: trans(columnFilterOption.key),
                key_id: columnFilterOption.key_id,
                label: trans(columnFilterOption.label),
                module_name: columnFilterOption.module_name
            };
        });
    },
    methods: {
        handleCreate() {
            this.$inertia.get(route("push_notification_message.create"));
        },
         FilterOptionshandle(value) {
        router.put(route('push_notification_message.screenDisplayUiSetting.store'),
            {
                value,
                sort_field :this.sort_field ,
                sort_order:this.sort_order,
                row:this.push_notification_messages.per_page,
                search:this.search.value,
                current_page:this.push_notification_messages.current_page
            },
            {
                preserveScroll: true,
                preserveState:true,
            });

        },
    },
})
</script>
