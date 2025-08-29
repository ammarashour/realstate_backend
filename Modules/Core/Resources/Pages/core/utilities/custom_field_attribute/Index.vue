<template>
    <Head :title="$t('core__be_attribute_label')" />
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
        <ps-table2 :row="row" :search="search" :object="this.customizeDetails" :colFilterOptions="colFilterOptions" :columns="columns" :sort_field="sort_field" :sort_order="sort_order" @FilterOptionshandle="FilterOptionshandle"
        @handleSort="handleSorting"  @handleSearch="handleSearching" @handleRow="handleRow">

             <!-- add new field start -->
            <template #button>
                <ps-button v-if="can.createCustomFieldAttribute" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-2 font-semibold" />{{$t('core__be_add_attribute')}}</ps-button>
            </template>
            <template #responsive_button>
                <ps-button v-if="can.createCustomFieldAttribute" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-2 font-semibold" />{{$t('core__be_add_attribute')}}</ps-button>
            </template>
            <!-- add new field end -->

            <!-- for csv file import start -->
<!--            <template #searchLeft>-->
<!--                <ps-button @click="csvUploadClicked" class="mb-0.5">-->
<!--                    <ps-icon name="plus" class="mx-0.5 mt-1.5 font-semibold" />-->
<!--                    <ps-label textColor="text-white">{{ $t('import_data') }}</ps-label>-->
<!--                </ps-button>-->
<!--                <ps-csv-modal ref="ps_csv_modal" />-->
<!--            </template>-->
            <!-- for csv file import start -->

            <template #tableActionRow="rowProps">
                <!-- For action (edit/delete) start -->
                <div class="flex flex-row mb-2" v-if="rowProps.field == 'action'">
                    <ps-button :disabled="rowProps.row.authorization.update ? false : true" @click="handleEdit(rowProps.row.id)" class="me-4" colors="bg-green-400 text-white" padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-green-100" focus="focus:outline-none focus:ring focus:ring-green-200" > <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" /> </ps-button>
                    <ps-button :disabled="rowProps.row.authorization.delete ? false : true" @click="confirmDeleteClicked(rowProps.row.id)" colors="bg-red-400 text-white" padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-red-100" focus="focus:outline-none focus:ring focus:ring-red-200" > <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" /> </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>
                <!-- For action (edit/delete) end -->

            </template>

        </ps-table2>
        <!-- data table end -->
    </ps-layout>
</template>

<script>
import { defineComponent, ref, reactive } from 'vue'
import { Link, Head, useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3'
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
import Dropdown from "@/Components/Core/DropdownModal/Dropdown.vue";
import PsIconButton from "@/Components/Core/Buttons/PsIconButton.vue";
import PsCsvModal from '@/Components/Core/Modals/PsCsvModal.vue';
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Index",
    components: {
        Link,
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
        Dropdown,
        PsIconButton,
        PsCsvModal,
    },
    layout : PsLayout,
    props:{
            can:Object,
            customizeHeader:Object,
            tableId:String,
            status:Object,
            customizeDetails:Object,
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
        const ps_csv_modal = ref();
        let visible = ref(false);

        const colFilterOptions = ref();
        const columns = ref();

        function confirmDeleteClicked(attributeId) {
            ps_danger_dialog.value.openModal(
                trans('core__delete'),
                trans('core__comfirm_to_delete_customize_ui_row'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    this.$inertia.delete(route("attribute.destroy", [props.tableId, props.customizeHeader.core_keys_id, attributeId]),{
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
                    });
                },
                () => { }
            );
        }

        function csvUploadClicked(){
            ps_csv_modal.value.openModal(
                (selectedFile) => {
                    let form = useForm({
                        csvFile: selectedFile,
                        "_method": "put"
                    })
                    router.post(route('language_string.import.csv', props.language), form);
                }
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
            router.get(route('attribute.index',[props.tableId, props.customizeHeader.core_keys_id]),
            {
                sort_field : sort_field.value,
                sort_order: sort_order.value,
                page:page ?? props.customizeDetails.meta.current_page,
                row:row ?? props.customizeDetails.meta.per_page,
                search:search.value,
            },
            {
                preserveScroll: true,
                preserveState:true,
            })
        }

        return {
            csvUploadClicked,
            ps_csv_modal,
            columns,
            ps_danger_dialog,
            confirmDeleteClicked,
            colFilterOptions,
            handleRow,
            handleSearchingSorting,
            handleSearching,
            handleSorting,
            visible
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
                    label: trans('table_setting_group'),
                    url: route('table.index')
                },
                {
                    label: trans('core__be_fields_label'),
                    url: route('tables.fields.index', this.tableId)
                },
                {
                    label: trans('core__be_attribute_label'),
                    color: "text-primary-500"
                }
            ]
        }
    },
    methods: {
        handleCreate() {
            this.$inertia.get(route('attribute.create', [this.tableId, this.customizeHeader.core_keys_id]));
        },
        handleEdit(attributeId){
            this.$inertia.get(route('attribute.edit',[this.tableId, this.customizeHeader.core_keys_id, attributeId]));
        },
        FilterOptionshandle(value) {
        router.put(route('attribute.screenDisplayUiSetting.store'),
            {
                value,
                sort_field :this.sort_field ,
                sort_order:this.sort_order,
                row:this.customizeDetails.per_page,
                search:this.search.value,
                current_page:this.customizeDetails.current_page
            },
            {
                preserveScroll: true,
                preserveState:true,
            });

        },
    },
    created() {
        this.columns = this.showCoreAndCustomFieldArr.map(column => {
            return {
                action: column.action,
                field: column.field,
                label: trans(column.label),
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
})
</script>
