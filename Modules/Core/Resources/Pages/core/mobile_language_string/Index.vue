<template>
    <Head :title="$t('mobile_language_module')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <!-- alert banner start -->
         <ps-banner-icon  v-if="visible" :visible="visible"
        :theme="(status.flag)=='danger'?'bg-red-500':(status.flag)=='warning'?'bg-yellow-500':'bg-green-500'"
        :iconName="(status.flag)=='danger'?'close-circle':(status.flag)=='warning'?'alert-triangle':'rightalert'"
        class="text-white mb-5 sm:mb-6 lg:mb-8"
         iconColor="white">{{status.msg.msg}}</ps-banner-icon>
        <!-- alert banner end -->

        <div class="flex items-end justify-end">
            <ps-button v-if="can.createMobileLanguageString" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-2 font-semibold" />{{$t('core__be_add_mobile_language_string')}}</ps-button>
        </div>

        <!-- data table start -->
        <ps-table2 :row="row" :search="search" :object="this.mobile_language_strings" :colFilterOptions="colFilterOptions" :columns="columns" :sort_field="sort_field" :sort_order="sort_order" @FilterOptionshandle="FilterOptionshandle"
        @handleSort="handleSorting"  @handleSearch="handleSearching" @handleRow="handleRow"
            :searchable="showFilter">

             <!-- add new field start -->
            <!-- <template #button>
                <ps-button v-if="can.createMobileLanguageString" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-2 font-semibold" />{{$t('core__be_add_mobile_language_string')}}</ps-button>
            </template>
            <template #responsive_button>
                <ps-button v-if="can.createMobileLanguageString" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-2 font-semibold" />{{$t('core__be_add_mobile_language_string')}}</ps-button>
            </template> -->
            <!-- add new field end -->

            <!-- for csv file import start -->
            <template #searchLeft>
                
                <ps-button v-if="can.createMobileLanguageString" rounded="rounded" @click="csvUploadClicked" class="ms-3 flex flex-wrap items-center ">
                    <ps-icon name="plus" class="me-2 font-semibold" />
                    <ps-label textColor="text-white dark:text-secondary-800">{{ $t('import_data') }}</ps-label>
                </ps-button>
                <a :href="route('mobile_language_string.export.json', mobile_language.id)">
                    <ps-button v-if="can.createMobileLanguageString" rounded="rounded" colors="text-primary-500" border="border border-primary-500" class="ms-4 flex flex-wrap items-center ">
                        <ps-icon name="fileUpload" class="me-2 font-semibold" />
                        <ps-label textColor="text-primary-500">Export Json</ps-label>
                    </ps-button>
                </a>
                <a :href="route('mobile_language_string.export.csv', mobile_language.id)">
                    <ps-button v-if="can.createMobileLanguageString" rounded="rounded" colors="text-primary-500" border="border border-primary-500" class="ms-4 flex flex-wrap items-center ">
                        <ps-icon name="fileUpload" class="me-2 font-semibold" />
                        <ps-label textColor="text-primary-500">Export CSV</ps-label>
                    </ps-button>
                </a>
                <ps-json-modal ref="ps_json_modal" url="https://drive.google.com/file/d/1wu_YeXwQzcrnYNwBhlM_qFMalaWMmvTp/view?usp=sharing"/>
                <ps-csv-modal ref="ps_csv_modal" url="https://drive.google.com/file/d/1zqO4n2zlroGITAblYyGGCdNfoepxiz62/view?usp=sharing" isLanguage/>

            </template>
            <!-- for csv file import start -->

             <template #tableRow="rowProps">
                <div class="flex flex-row" v-if="rowProps.field == 'mobile_language_id@@name'">
                    <ps-label   >
                        {{mobile_language.name}}
                     </ps-label>
                </div>                

            </template>

            <template #tableActionRow="rowProps">
                <!-- For action (edit/delete) start -->
                <div class="flex flex-row" v-if="rowProps.field == 'action'">
                    <ps-button  :disabled="rowProps.row.authorization.update ? false : true" @click="handleEdit(rowProps.row.id)" class="me-4" colors="bg-green-400 text-white" padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-green-100" focus="focus:outline-none focus:ring focus:ring-green-200" > <ps-icon name="editPencil"  w="16" h="16" /> </ps-button>
                    <ps-button  :disabled="rowProps.row.authorization.delete ? false : true" @click="confirmDeleteClicked(rowProps.row.id)" colors="bg-red-400 text-white" padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-red-100" focus="focus:outline-none focus:ring focus:ring-red-200" > <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" /> </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>
                <!-- For action (edit/delete) end -->

            </template>

        </ps-table2>
        <!-- data table end -->
    </ps-layout>
</template>

<script>
import { defineComponent, ref, reactive, watch } from 'vue'
import { Link, Head,useForm } from '@inertiajs/vue3';
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
import { trans } from 'laravel-vue-i18n';
import { router } from '@inertiajs/vue3';
import PsJsonModal from '@/Components/Core/Modals/PsJsonModal.vue';
import PsCsvModal from '@/Components/Core/Modals/PsCsvModal.vue';

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
        PsJsonModal,
        PsCsvModal
        },
        layout : PsLayout,
        props:{
            status:Object,
            can:Object,
            mobile_language:Object,
            mobile_language_strings:Object,
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
        let visible = ref(false)

        let sorting = "";
        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
        const ps_danger_dialog = ref();
        const ps_json_modal = ref();
        const ps_csv_modal = ref();

        const colFilterOptions = ref();

        const columns = ref();

        watch(() => props.status, () => {
                visible.value = true;
                    setTimeout(() => {
                        visible.value = false;
                    }, 3000);
            });

        function confirmDeleteClicked(id) {
            ps_danger_dialog.value.openModal(
                // "",
                trans('core__delete'),
                trans('core__comfirm_to_delete_language_string'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    this.$inertia.delete(route("mobile_language_string.destroy", [props.mobile_language, id]),{
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
            router.get(route('mobile_language_string.index',props.mobile_language.id),
            {
                mobile_language : props.mobile_language.id,
                sort_field : sort_field.value,
                sort_order: sort_order.value,
                page:page ?? props.mobile_language_strings.meta.current_page,
                row:row ?? props.mobile_language_strings.meta.per_page,
                search:search.value,
            },
            {
                preserveScroll: true,
                preserveState:true,
            })
        }

        function csvUploadClicked(){
            ps_csv_modal.value.openModal(
                (selectedFile) => {
                    let form = useForm({
                        csvFile: selectedFile,
                        "_method": "put"
                    })
                    router.post(route('mobile_language_string.import.csv', props.mobile_language.id), form, {
                        onSuccess: () => {

                            window.location.reload();

                        },
                    });
                }
            );
        }

        return {
            ps_json_modal,
            csvUploadClicked,
            handleRow,
            handleSearchingSorting,
            handleSearching,
            handleSorting,
            visible,
            columns,
            ps_danger_dialog,
            confirmDeleteClicked,
            colFilterOptions,
            ps_csv_modal,
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
                    label: trans('mobile_language_module'),
                    url: route('mobile_language.index')
                },
                {
                    label: trans('mobile_language_string_module'),
                    color: "text-primary-500"
                }
            ]
        }
    },
    methods: {
        handleCreate() {
            this.$inertia.get(route('mobile_language_string.create',this.mobile_language.id));
        },
        handleEdit(id){
            this.$inertia.get(route('mobile_language_string.edit',[this.mobile_language,id]));
        },        
        FilterOptionshandle(value) {
        router.put(route('mobile_language_string.screenDisplayUiSetting.store'),
            {
                value,
                sort_field :this.sort_field ,
                sort_order:this.sort_order,
                row:this.mobile_language_strings.per_page,
                search:this.search.value,
                current_page:this.mobile_language_strings.current_page
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
})
</script>
