<template>
    <Head :title="$t('package_report_module')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <!-- alert banner start -->
        <ps-banner-icon   v-if="visible" :visible="visible"
        :theme="(status.flag)=='danger'?'bg-red-500':(status.flag)=='warning'?'bg-yellow-500':'bg-green-500'"
        :iconName="(status.flag)=='danger'?'close-circle':(status.flag)=='warning'?'alert-triangle':'rightalert'"
        class="text-white mb-5 sm:mb-6 lg:mb-8"
         iconColor="white">{{status.msg}}</ps-banner-icon>
        <!-- alert banner end -->

        <ps-table2 :row="row" :search="search"  :object="transactions" :colFilterOptions="colFilterOptions"
                :columns="columns" :sort_field="sort_field" :sort_order="sort_order"
            :globalSearchPlaceholder="$t('core__be_search_user')"
                @FilterOptionshandle="FilterOptionshandle" @handleSort="handleSorting" @handleSearch="handleSearching"
                @handleRow="handleRow" :searchable="showFilter" :eye_filter="false">

            <!-- for csv file export start -->
            <template #searchLeft>
                <a :href="route('package_report.csv.export')" class="font-medium transition duration-150 ease-in-out px-2 py-1.75 ms-1 rounded text-primary-500 border border-primary-500 hover:outline-none hover:ring hover:ring-blue-100 focus:outline-none focus:ring focus:ring-blue-300 opacity-100 flex items-center"><ps-icon name="fileUpload" class="mx-0.5 font-semibold" />{{ $t('core__be_export_btn') }}</a>
            </template>

                <template #searchRight>
                        <ps-text-button
                            v-if="showFilter"
                            @click="handleClearFilter"
                            class="flex text-sm items-center text-red-400"
                            padding="py-1 px-4"
                        >
                            <ps-icon theme="dark:text-red-400" name="cross" class="me-3" />
                            {{ $t('core__be_clear_filter') }}
                        </ps-text-button>
                        <ps-icon-button :colors="!showFilter ? '' : 'bg-primary-500 text-white dark:text-secondary-800'" focus="" padding="py-1 px-4"
                            hover="hover:bg-primary-500 hover:text-white" :border="!showFilter ? ' border border-secondary-200' : 'border border-primary-500'"
                            leftIcon="filter" @click="showFilter = !showFilter">{{ $t('core__be_filter') }}</ps-icon-button>
                            </template>
                    <template #Filter>

                        <ps-dropdown align="" class=" h-10">
                            <template #select>
                                <ps-dropdown-select :placeholder="$t('package_report_package_name')"
                                    :selectedValue="(selected_package == '' || selected_package == 'all') ? '' : packages.filter(option => option.id == selected_package)[0].value" />
                            </template>
                            <template #list>
                                <div class="rounded-md shadow-xs w-56 ">
                                    <div class="pt-2 z-30  ">
                                        <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                           @click="handlePackagefilter('all')">
                                            <ps-label class="text-gray-200 ms-2">{{$t('core__be_select_all')}}</ps-label>
                                        </div>
                                        <div v-for="(p) in packages" :key="p.id"
                                            class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                           @click="handlePackagefilter(p.id)">
                                            <ps-label class="ms-2" :class="p.id == selected_package ? ' font-bold' : ''">
                                                {{ p.value }} </ps-label>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </ps-dropdown>
                        <ps-dropdown align="" class=" h-10">
                            <template #select>
                                <ps-dropdown-select :placeholder="$t('package_report_payment_method')"
                                    :selectedValue="(selected_payment_method == '' || selected_payment_method == 'all') ? '' : payment_methods.filter(option => option == selected_payment_method)[0]" />
                            </template>
                            <template #list>
                                <div class="rounded-md shadow-xs w-56 ">
                                    <div class="pt-2 z-30  ">
                                        <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                           @click="handlePaymentfilter('all')">
                                            <ps-label class="text-gray-200 ms-2">{{$t('core__be_select_all')}}</ps-label>
                                        </div>
                                        <div v-for="(p,index) in payment_methods" :key="index"
                                            class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                           @click="handlePaymentfilter(p)">
                                            <ps-label class="ms-2" :class="p == selected_payment_method ? ' font-bold' : ''">
                                                {{ p }} </ps-label>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </ps-dropdown>
                        <date-picker v-if="reRenderDate" @datepick="handleDatefilter" class="rounded shadow-sm pt-0.5 focus:outline-none focus:ring-1 focus:ring-primary-500" :class="(selected_date == null || selected_date == '') ? 'w-full' :'w-full'"
                                     v-model:value="selected_date" :range="true" :inline="false" :autoApply="false"/>                    </template>


                 <template #tableRow="rowProps">

                    <div class="flex flex-row mb-2" v-if="rowProps.field == 'detail'">
                        <ps-text-button @click="handleDetail(rowProps.row.id)" >{{ $t('core__be_btn_detail') }}</ps-text-button>
                    </div>
                    <div class="flex flex-row mb-2" v-if="rowProps.field == 'package_id'">
                        <ps-label>
                            {{ rowProps.row.package.payment_info.value}}
                        </ps-label>
                    </div>
                </template>
            </ps-table2>
        <!-- data table end -->
    </ps-layout>
</template>

<script>
import { defineComponent, ref, reactive } from "vue";
import { Link, Head, useForm } from "@inertiajs/vue3";
import PsLayout from "@/Components/PsLayout.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsDataTable from "@/Components/Core/Table/PsDataTable.vue";
import PsAlert from "@/Components/Core/Alert/PsAlert.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsToggle from "@/Components/Core/Toggle/PsToggle.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";
import Dropdown from "@/Components/Core/DropdownModal/Dropdown.vue";
import PsIconButton from "@/Components/Core/Buttons/PsIconButton.vue";
import PsTextButton from "@/Components/Core/Buttons/PsTextButton.vue";
import { trans } from "laravel-vue-i18n";
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";
import { router } from '@inertiajs/vue3';
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import DatePicker from "@/Components/Core/DateTime/DatePicker.vue";


export default defineComponent({
    name: "Index",
    components: {
        Link,
        Head,
        PsLabel,
        PsButton,
        PsDataTable,
        PsAlert,
        PsBreadcrumb2,
        PsDangerDialog,
        PsToggle,
        PsIcon,
        PsBannerIcon,
        Dropdown,
        PsIconButton,
        PsTextButton,
        PsTable2,
        PsDropdown,
        PsDropdownSelect,
        DatePicker


    },
    layout: PsLayout,
    // props: ["transactions", "status", "packages"],
    props: {
        status: Object,
        transactions:Object,
        packages:Object,
        checkPermission:Object,
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
        selected_package:Object,
        selectedDate:Object,
        selected_payment_method:Object,
        payment_methods:Object
    },
    setup(props) {
        const showFilter = ref(false);
        const clearFilter = ref(false);
        const reRenderDate = ref(true);

        // For data table
        const globalSearchFields = ["package.title", "user.name"];
        const globalSearchPlaceholder = "Search";
        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
        let visible = ref(false);
        let selected_package = props.selected_package ? ref(props.selected_package) : ref('');
        let selected_date = props.selectedDate ? ref(props.selectedDate) : ref('');
        let selected_payment_method = props.selected_payment_method ? ref(props.selected_payment_method) : ref('');

        let types = [
            {
                id: "0",
                name: "Waiting For Approval",
            },
            {
                id: "1",
                name: "Approved",
            },
            {
                id: "2",
                name: "Rejected",
            },
        ];

        // const searchOptions = [
        //     {
        //         placeholder: "Package",
        //         options: props.packages,
        //         displayField: "title",
        //         checkFieldMain: "package_id",
        //         checkFieldOption: "id",
        //         filterType: "dropdown",
        //     },
        //     {
        //         placeholder: "Payment Status",
        //         options: types,
        //         displayField: "name",
        //         checkFieldMain: "status",
        //         checkFieldOption: "id",
        //         filterType: "dropdown",
        //     },
        // ];

        const colFilterOptions = reactive([
            {
                label: "User",
                key: "user_id",
                hidden: false,
            },
            {
                label: "Package",
                key: "package_id",
                hidden: false,
            },
            {
                label: "Amount",
                key: "price",
                hidden: false,
            },
            {
                label: "Payment Method",
                key: "payment_method",
                hidden: false,
            },
            {
                label: "Date",
                key: "added_date",
                hidden: false,
            },
        ]);

        const columns = [
            {
                label: trans("package_report_user"),
                field: "user_id",
                relation: "user",
                type: "String",
                relationField: "name",
                action: "Action",
            },
            {
                label:trans("package_report_package_name"),
                field: "package_id",
                relation: "package",
                type: "String",
                relationField: ["payment_info","id"],
                action: "Action",
            },
            {
                label: trans("package_amount"),
                field: "price",
                type: "Integer",
                action: "Action",
            },
            {
                label: trans("package_payment"),
                field: "payment_method",
                type: "String",
                action: "Action",
                // sort:false
            },
            {
                label: trans("package_date"),
                field: "added_date",
                type: "Timestamp",
                action: "Action",
            },
            {
                label: trans('detail_label'),
                field: "detail",
                type: 'Action',
                sort:false
            },
        ];

        function handleDetail(id){
            this.$inertia.get(route('package_report.show',id));
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
         function handleClearFilter() {
            selected_package.value = '';
            selected_date.value = null;
            selected_payment_method.value = '';
            // selected_city.value = 'all';
            // selected_township.value = 'all';
            handleSearchingSorting();

            reRenderDate.value= false;
            setTimeout(() => {
                reRenderDate.value = true;
            }, 1);


        }
        function dateFilter(value) {
            selected_date.value = value
            let page = 1;
            handleSearchingSorting(page);
        }
        function handlePackagefilter(value) {
            selected_package.value = value
            let page = 1;
            handleSearchingSorting(page);
        }
        function handlePaymentfilter(value) {
            selected_payment_method.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleDatefilter(value) {
            selected_date.value = value
            let page = props.transactions.meta.current_page;
            handleSearchingSorting(page);
        }

        function handleSearchingSorting(page = null, row = null) {
            router.get(route('package_report.index'),
                {
                    sort_field: sort_field.value,
                    sort_order: sort_order.value,
                    page: page ?? props.transactions.meta.current_page,
                    row: row ?? props.transactions.meta.per_page,
                    search: search.value,
                    package_filter: selected_package.value,
                    date_filter:selected_date.value,
                    selected_payment_method:selected_payment_method.value,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }


        return {
            showFilter,
            clearFilter,
            globalSearchFields,
            // searchOptions,
            globalSearchPlaceholder,
            columns,
            colFilterOptions,
            handleDetail,
            handleSorting,
            handleSearching,
            handleRow,
            visible,
            handleClearFilter,
            handlePackagefilter,
            selected_package,
            selected_date,
            dateFilter,
            selected_payment_method,
            handlePaymentfilter,
            reRenderDate,
            handleDatefilter
        };
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans("core__be_dashboard_label"),
                    url: route("admin.index"),
                },
                {
                    label: trans("package_report_module"),
                    color: "text-primary-500",
                },
            ];
        },
    },
});
</script>
