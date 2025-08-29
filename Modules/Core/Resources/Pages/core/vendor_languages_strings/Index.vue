<template>
    <Head :title="$t('vendor_language_module')" />
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

        <!-- add new field start -->
        <div class="hidden sm:flex justify-end ">
            <div class="flex">

            </div>
        </div>
        <!-- add new field end -->

        <!-- data table start -->
        <!-- <new-ps-data-table :rows="this.language_strings" :columns="columns" :colFilterOptions="colFilterOptions" :globalSearchFields="globalSearchFields" :globalSearchPlaceholder="globalSearchPlaceholder">

            <template #searchLeft>
                <ps-button @click="csvUploadClicked" class="mb-0.5">
                    <ps-icon name="plus" class="mx-0.5 mt-1.5 font-semibold" />
                    <ps-label textColor="text-white">{{ $t('import_data') }}</ps-label>
                </ps-button>
                <ps-csv-modal ref="ps_csv_modal" />
            </template>


            <template #tableActionRow="rowProps">

                <div class="flex flex-row mb-2" v-if="rowProps.field == 'action'">
                    <ps-button @click="handleEdit(rowProps.row.id)" class="me-4" colors="bg-green-400 text-white" padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-green-100" focus="focus:outline-none focus:ring focus:ring-green-200" > <ps-icon name="editPencil" w="16" h="16" /> </ps-button>
                    <ps-button @click="confirmDeleteClicked(rowProps.row.id)" colors="bg-red-400 text-white" padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-red-100" focus="focus:outline-none focus:ring focus:ring-red-200" > <ps-icon name="trash" w="16" h="16" /> </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>


            </template>

        </new-ps-data-table> -->


        <ps-table2 :row="row" :search="search" :object="vendor_language_strings" :colFilterOptions="colFilterOptions"
            :columns="columns" :sort_field="sort_field" :sort_order="sort_order"
            @FilterOptionshandle="FilterOptionshandle" @handleSort="handleSorting" @handleSearch="handleSearching"
            @handleRow="handleRow" :searchable="showFilter">

            <template #button>
                   <ps-button v-if="can.createVendorLanguageString" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-1 font-semibold" /> {{$t('create_vendor_language_string')}}</ps-button>
            </template>
             <template #responsive_button>
                   <ps-button v-if="can.createVendorLanguageString" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-1 font-semibold" />  {{ $t('create_vendor_language_string') }}</ps-button>
            </template>
            <template #searchLeft>
                <ps-button v-if="can.createVendorLanguageString" rounded="rounded" @click="csvUploadClicked" class="ms-3 flex flex-wrap items-center ">
                    <ps-icon name="plus" class="me-2 font-semibold" />
                    <ps-label textColor="text-white dark:text-secondary-800">{{ $t('core__be_import_data') }}</ps-label>
                </ps-button>
                <a :href="route('vendor_language_string.export.json', language.id)">
                    <ps-button v-if="can.createLanguageString" rounded="rounded" colors="text-primary-500" border="border border-primary-500" class="ms-4 flex flex-wrap items-center ">
                        <ps-icon name="fileUpload" class="me-2 font-semibold" />
                        <ps-label textColor="text-primary-500">Export Json</ps-label>
                    </ps-button>
                </a>
                <a :href="route('vendor_language_string.export.csv', language.id)">
                    <ps-button v-if="can.createLanguageString" rounded="rounded" colors="text-primary-500" border="border border-primary-500" class="ms-4 flex flex-wrap items-center ">
                        <ps-icon name="fileUpload" class="me-2 font-semibold" />
                        <ps-label textColor="text-primary-500">Export CSV</ps-label>
                    </ps-button>
                </a>
                <ps-csv-modal ref="ps_csv_modal" url="https://drive.google.com/file/d/1zqO4n2zlroGITAblYyGGCdNfoepxiz62/view?usp=sharing" isLanguage/>
            </template>
            <template #tableActionRow="rowProps">

                <div class="flex flex-row" v-if="rowProps.field == 'action'">
                    <ps-button :disabled="!rowProps.row.authorizations.update" @click="handleEdit(rowProps.row.id)" class="me-2" colors="bg-green-400 text-white" padding="p-1.5"
                        hover="hover:outline-none hover:ring hover:ring-green-100"
                        focus="focus:outline-none focus:ring focus:ring-green-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                    </ps-button>
                    <ps-button :disabled="!rowProps.row.authorizations.delete" @click="confirmDeleteClicked(rowProps.row.id)" colors="bg-red-400 text-white" padding="p-1.5"
                        hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>


            </template>



        </ps-table2>
        <!-- data table end -->
    </ps-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import PsLayout from "@/Components/PsLayout.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import NewPsDataTable from "@/Components/Core/Table/NewPsDataTable.vue";
import PsAlert from "@/Components/Core/Alert/PsAlert.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";
import PsIconButton from "@/Components/Core/Buttons/PsIconButton.vue";
import PsCsvModal from '@/Components/Core/Modals/PsCsvModal.vue';
import { trans } from 'laravel-vue-i18n';
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";


export default defineComponent({
    name: "Index",
    components: {
        Head,
        PsLabel,
        PsButton,
        NewPsDataTable,
        PsAlert,
        PsBreadcrumb2,
        PsDangerDialog,
        PsToggle,
        PsIcon,
        PsBannerIcon,
        PsIconButton,
        PsCsvModal,
        PsTable2,
    },
    layout : PsLayout,
    // props: ['language', 'language_strings', 'status', 'checkPermission', 'showLanguageCols', 'showCoreAndCustomFieldArr', 'hideShowFieldForFilterArr'],
    props: {
        status: Object,
        language:Object,
        vendor_language_strings:Object,
        checkPermission:Object,
        showLanguageCols:Object,
        showCoreAndCustomFieldArr:Object,
        hideShowFieldForFilterArr:Object,
        sort_field: {
            type: String,
            default: "",
        },
        sort_order: {
            type: String,
            default: 'desc',
        },
        search: String,
        can:Object,
    },
    setup(props) {
        // For data table
        const globalSearchFields = ["key", "value"];
        const globalSearchPlaceholder = trans('lang_search');
        const ps_danger_dialog = ref();
        const ps_csv_modal = ref();

        const colFilterOptions = ref();
        const columns = ref();
        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
        let visible = ref(false)

        function confirmDeleteClicked(id) {
            ps_danger_dialog.value.openModal(
               trans('core__be_delete_vendor_languagestring'),
                trans('core__be_delete_vendor_languagestring_info'),
                trans('core__be_btn_confirm'),
                trans('btn_cancel'),
                () => {
                    this.$inertia.delete(route("vendor_language_string.destroy", [props.language, id]),{
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
                    // this.$inertia.delete(route("language_string.destroy", [props.language, id]));
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
                    router.post(route('vendor_language_string.import.csv', props.language), form, {
                        onSuccess: () => {

                            window.location.reload();

                        },
                    });
                }
            );
        }
        function handleSorting(value) {
            sort_field.value = value.field
            sort_order.value = value.sort_order
            handleSearchingSorting()
        }
        function handleSearching(value) {
            search.value = value;
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleRow(value) {
            handleSearchingSorting(1, value);
        }

        function handleSearchingSorting(page = null, row = null) {
            router.get(route('vendor_language_string.index',props.language.id),
                {
                    sort_field: sort_field.value,
                    sort_order: sort_order.value,
                    page: page ?? props.vendor_language_strings.meta.current_page,
                    row: row ?? props.vendor_language_strings.meta.per_page,
                    search: search.value,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }

        return { globalSearchFields, globalSearchPlaceholder,csvUploadClicked,ps_csv_modal, columns, ps_danger_dialog, confirmDeleteClicked, colFilterOptions,handleSorting ,handleSearching ,handleRow ,visible }
    },
    computed: {
        breadcrumb() {

            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('vendor_language_module'),
                    url: route('vendor_language.index')
                },
                {
                    label: trans('vendor_language_string_module'),
                    color: "text-primary-500"
                }
            ]
        }
    },
    methods: {
        handleCreate() {
            this.$inertia.get(route('vendor_language_string.create',this.language.id));
        },
        handleEdit(id){
            this.$inertia.get(route('vendor_language_string.edit',[this.language,id]));
        },
        FilterOptionshandle(value) {

            router.post(route('vendor_language_string.screenDisplayUiSetting.store'),
                {
                    value,
                    sort_field: this.sort_field,
                    sort_order: this.sort_order,
                    row: this.vendor_language_strings.per_page,
                    search: this.search.value,
                    current_page: this.vendor_language_strings.current_page
                },
                {
                    preserveScroll: true,
                    preserveState: true,
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
