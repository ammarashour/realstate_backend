<template>
    <Head :title="$t('complaint_item_report_module')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <!-- alert banner start -->
        <ps-banner-icon  v-if="visible" :visible="visible"
            :theme="(status.flag)=='danger'?'bg-red-500':(status.flag)=='warning'?'bg-yellow-500':'bg-green-500'"
            :iconName="(status.flag)=='danger'?'close-circle':(status.flag)=='warning'?'alert-triangle':'rightalert'"
            class="text-white mb-5 sm:mb-6 lg:mb-8"
            :globalSearchPlaceholder="$t('core__be_search_item')"
            iconColor="white">{{status.msg}}</ps-banner-icon>
        <!-- alert banner end -->

        <!-- data table start -->
        <ps-table2 :row="row" :search="search" :object="this.items" :colFilterOptions="colFilterOptions"
            :columns="columns" :sort_field="sort_field" :sort_order="sort_order"
            :globalSearchPlaceholder="$t('core__be_search_item')"
            @FilterOptionshandle="FilterOptionshandle" @handleSort="handleSorting" @handleSearch="handleSearching"
            @handleRow="handleRow" :searchable="showFilter"
            :eye_filter="false">

            <!-- for csv file export start -->
            <template #searchLeft>
                <a :href="route('complaint_item_report.csv.export')" class="font-medium transition duration-150 ease-in-out px-2 py-1.75 ms-1 rounded text-primary-500 border border-primary-500 hover:outline-none hover:ring hover:ring-blue-100 focus:outline-none focus:ring focus:ring-blue-300 opacity-100 flex items-center"><ps-icon name="fileUpload" class="mx-0.5 font-semibold" />{{ $t('core__be_export_btn') }}</a>
            </template>
            <!-- for csv file export start -->

            <template #searchRight>
                <ps-text-button v-if="showFilter" @click="handleClearFilter()"
                    class="flex text-sm items-center text-red-400 dark:text-red-400" padding="py-2 px-4">
                    <ps-icon theme="dark:text-red-400" name="cross" class="me-3"/>
                    {{ $t('core__be_clear_filter') }}
                </ps-text-button>
                <ps-icon-button :colors="!showFilter ? '' : 'bg-primary-500 text-white dark:text-secondary-800'" focus="" padding="py-1 px-4"
                    hover="hover:bg-primary-500 hover:text-white" :border="!showFilter ? ' border border-secondary-200' : 'border border-primary-500'"
                    leftIcon="filter" @click="showFilter = !showFilter">{{ $t('core__be_filter') }}</ps-icon-button>
            </template>

            <template #Filter>
                <!-- status filter -->
                <ps-dropdown align="" class="h-10">
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__be_status')"
                            :selectedValue="(selected_status == '' || selected_status == 'all') ? '' : types.filter(option => option.id == selected_status)[0].title"
                        />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleStatusfilter('all')">
                                    <ps-label class="text-gray-200">{{$t('core__be_select_all')}}</ps-label>
                                </div>
                                <div v-for="type in types" :key="type.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleStatusfilter(type.id)">
                                    <ps-label class="ms-2" :class="type.id == selected_status ? ' font-bold' : ''">
                                        {{ type.title }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>
                </ps-dropdown>

                <!-- added date filter -->
                <date-picker v-if="reRenderDate"  :placeholder="$t('core__be_select_date')" @datepick="handleDatefilter" class="rounded shadow-sm pt-0.5 focus:outline-none focus:ring-1 focus:ring-primary-500 " :class="(selected_date == null || selected_date == '') ? 'w-full' :'w-full'"
                v-model:value="selected_date" :range="true" :inline="false" :autoApply="false"/>
            </template>
            <template #tableRow="rowProps">
                <ps-label v-if="rowProps.field == 'reported_item_status_id'">
                    <ps-label class=" whitespace-nowrap dark:text-white">
                        <ps-label class="flex flex-row" :textColor="rowProps.row.reported_item_status_id ==1 ?'text-green-600':'text-red-500' ">
                            <ps-label class="w-2 h-2 my-auto rounded-full me-2" :textColor="rowProps.row.reported_item_status_id ==1 ?'bg-green-600':'bg-red-500'"></ps-label>
                            {{ rowProps.row.reported_item_status.title }}
                        </ps-label>
                    </ps-label>
                </ps-label>

                <div class="flex flex-row " v-if="rowProps.field == 'complete'">
                    <ps-button @click="handleComplete(rowProps.row.id,rowProps.row.authorizations.update)" rounded="rounded"
                    :colors="rowProps.row.reported_item_status_id ==1 ? 'bg-pink-400 text-white': 'bg-secondary-100 text-secondary-300 dark:bg-gray-800 dark:text-gray-600'"
                    :cursor="rowProps.row.authorizations.update == true ? 'cursor-pointer': 'cursor-not-allowed'">
                    {{ rowProps.row.reported_item_status_id ==1?$t('core__be_complete'):$t('core__be_completed') }}</ps-button>
                </div>

                <div class="flex flex-row " v-if="rowProps.field == 'detail'">
                    <ps-text-button @click="handleDetail(rowProps.row.id)" >{{$t('core__be_detail')}}</ps-text-button>
                </div>
            </template>

            <template #tableActionRow="rowProps">
                <div class="flex flex-row " v-if="rowProps.field == 'action'">
                    <ps-button :disabled="rowProps.row.authorizations.delete ? false : true"  @click="confirmDeleteClicked(rowProps.row.id)" rounded="rounded-lg" colors="bg-red-400 text-white"
                        padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-red-100"
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
import { ref, defineComponent } from "vue";
import PsLayout from "@/Components/PsLayout.vue";
import { Head } from "@inertiajs/vue3";
import { router } from '@inertiajs/vue3';
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextButton from "@/Components/Core/Buttons/PsTextButton.vue";
import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsIconButton from "@/Components/Core/Buttons/PsIconButton.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsLink1 from '@/Components/Core/Link/PsLink1.vue';
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";
import DatePicker from "@/Components/Core/DateTime/DatePicker.vue";
import PsBadge from "@/Components/Core/Badge/PsBadge.vue"
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Index",
    components: {
        Head,
        PsButton,
        PsTextButton,
        PsBannerIcon,
        PsBreadcrumb2,
        PsDangerDialog,
        PsIcon,
        PsDropdown,
        PsDropdownSelect,
        PsIconButton,
        PsLabel,
        PsLink1,
        PsToggle,
        PsTable2,
        PsBadge,
        DatePicker
    },
    layout: PsLayout,
    props: {
        can: Object,
        status: Object,
        items: Object,
        types: Object,
        hideShowFieldForFilterArr: Object,
        showCoreAndCustomFieldArr: Object,
        authUser: Object,
        sort_field: {
            type: String,
            default: "",
        },
        sort_order: {
            type: String,
            default: 'desc',
        },
        search: String,
    },
    setup(props) {
        const ps_danger_dialog = ref();

        const colFilterOptions = ref();
        const reRenderDate = ref(true);

        const columns = [
            {
                label: trans('core__be_action_label'),
                field: "action",
                type: 'Action'
            },
            {
                label: trans('core__be_item'),
                field: "item@@title",
                type: 'String',
                action: 'Action'
            },
            {
                label:trans('core__be_user_name_label'),
                field: "added_user_id@@name",
                type: 'String',
                action: 'Action'
            },
            {
                label: trans('core__be_description'),
                field: "item@@description",
                type: 'String',
                sort: false,
                action: 'Action',
            },
            {
                label: trans('core__be_item_report_status'),
                field: "reported_item_status_id",
                type: 'Integer',
                action: 'Action'
            },
            {
                label: trans('core__be_complete'),
                field: "complete",
                type: "String",
            },
            {
                label: trans('core__be_date'),
                field: "added_date",
                sort: false,
                type: 'Timestamp',
                action: 'Action'
            },
            {
                label: trans('core__be_detail_label'),
                field: "detail",
                sort: false,
                type: 'Action'
            },
        ]

        function confirmDeleteClicked(id) {
            ps_danger_dialog.value.openModal(
                trans('complaintItem__delete_complaint_item'),
                trans('complaintItem__delete_complaint_item_info'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    router.delete(route("complaint_item_report.destroy", id), {
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

        const showFilter = props.selectedStatus || props.selectedDate ? ref(true): ref(false);
        const clearFilter = ref(false);
        let visible = ref(false)

        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
        let selected_status = props.selectedStatus ? ref(props.selectedStatus) : ref('');
        let selected_date = props.selectedDate ? ref(props.selectedDate) : ref('');

        function handleSorting(value) {
            sort_field.value = value.field
            sort_order.value = value.sort_order
            handleSearchingSorting()
        }

        function handleStatusfilter(value) {
            selected_status.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleDatefilter(value) {
            selected_date.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleClearFilter() {
            selected_status.value = 'all';
            selected_date.value = '';
            handleSearchingSorting();

            reRenderDate.value= false;
            setTimeout(() => {
                reRenderDate.value = true;
            }, 1);
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
            router.get(route('complaint_item_report.index'),
                {
                    sort_field: sort_field.value,
                    sort_order: sort_order.value,
                    page: page ?? props.items.meta.current_page,
                    row: row ?? props.items.meta.per_page,
                    search: search.value,
                    status_filter: selected_status.value,
                    date_filter: selected_date.value,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }

        return {
            reRenderDate,
            showFilter,
            clearFilter,
            columns,
            confirmDeleteClicked,
            ps_danger_dialog,
            colFilterOptions,
            visible,
            handleSorting,
            handleSearchingSorting,
            handleClearFilter,
            handleRow,
            handleSearching,
            selected_status,
            selected_date,
            handleStatusfilter,
            handleDatefilter
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
                    label: trans('complaint_item_report_module'),
                    color: "text-primary-500"
                }
            ]
        }
    },
    methods: {
        handleComplete(id,hasPermission){
            if(hasPermission){
                this.$inertia.put(route('complaint_item_report.statusChange',id));
            }

        },
        handleDetail(id){
            this.$inertia.get(route('complaint_item_report.show',id));
        },
        FilterOptionshandle(value) {
            router.put(route('complaint_item_report.screenDisplayUiSetting.store'),
                {
                    value,
                    sort_field: this.sort_field,
                    sort_order: this.sort_order,
                    row: this.items.per_page,
                    search: this.search.value,
                    current_page: this.items.current_page
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                });
        },
    },
    created() {
        // this.columns = this.showCoreAndCustomFieldArr.map(column => {
        //     return {
        //         action: column.action,
        //         field: column.field,
        //         label: trans(column.label),
        //         type: column.type
        //     };
        // });

        // this.colFilterOptions = this.hideShowFieldForFilterArr.map(columnFilterOption => {
        //     return {
        //         hidden: columnFilterOption.hidden,
        //         id: columnFilterOption.id,
        //         key: trans(columnFilterOption.key),
        //         key_id: columnFilterOption.key_id,
        //         label: trans(columnFilterOption.label),
        //         module_name: columnFilterOption.module_name
        //     };
        // });
    },

})
</script>



<style scoped>
</style>
