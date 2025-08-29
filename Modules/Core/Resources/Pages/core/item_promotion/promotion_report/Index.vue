<template>
    <Head :title="$t('promotion_report_module')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <!-- alert banner start -->
        <ps-banner-icon  v-if="visible" :visible="visible"
            :theme="(status.flag)=='danger'?'bg-red-500':(status.flag)=='warning'?'bg-yellow-500':'bg-green-500'"
            :iconName="(status.flag)=='danger'?'close-circle':(status.flag)=='warning'?'alert-triangle':'rightalert'"
            class="text-white mb-4"
            iconColor="white">{{status.msg}}</ps-banner-icon>
        <!-- alert banner end -->

        <!-- data table start -->
        <ps-table2 :row="row" :search="search"  :object="paidItems"
                :columns="columns" :sort_field="sort_field" :sort_order="sort_order"
                @FilterOptionshandle="FilterOptionshandle" @handleSort="handleSorting" @handleSearch="handleSearching"
                @handleRow="handleRow" :searchable="showFilter" :eye_filter="false">

            <!-- for csv file import start -->
            <template #searchLeft>
                <div >
                    <a :href="route('paid_item_report.csv.export')" class="font-medium transition duration-150 ease-in-out px-4 py-1.75 ms-1 rounded text-primary-500 border border-primary-500 hover:outline-none hover:ring hover:ring-blue-100 focus:outline-none focus:ring focus:ring-blue-300 opacity-100 flex items-center"><ps-icon name="fileUpload" class="me-2 font-semibold" />{{ $t('core__be_export_btn') }}</a>
                </div>
            </template>
            <!-- for csv file import start -->

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

                <!-- category filter -->
                <ps-dropdown align="" class=" h-10">
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__be_status_label')"
                            :selectedValue="(selected_status == '' || selected_status == 'all') ? '' : statusList.filter(option => option.id == selected_status)[0].name" />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleStatusfilter('all')">
                                    <ps-label class="text-gray-200 ms-2">{{$t('core__be_select_all')}}</ps-label>
                                </div>
                                <div v-for="status in statusList" :key="status.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleStatusfilter(status.id)">
                                    <ps-label class="ms-2" :class="status.id == selected_status ? ' font-bold' : ''">
                                        {{ status.name }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>
                </ps-dropdown>

                <!-- subcategory filter -->
                <ps-dropdown class=" h-10">
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__be_payment_method_label')"
                            :selectedValue="(selected_payment_method == '' || selected_payment_method == 'all') ? '' : payment_methods.filter(option => option.id == selected_payment_method)[0].name"/>
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handlePaymentMethodfilter('all')">
                                    <ps-label class="text-gray-200 ms-2">{{$t('core__be_select_all')}}</ps-label>
                                </div>
                                <div v-for="payment_method in payment_methods" :key="payment_method.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handlePaymentMethodfilter(payment_method.id)">
                                    <ps-label class="ms-2"
                                        :class="payment_method.id == selected_payment_method ? ' font-bold' : ''">
                                        {{ payment_method.name }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>
                </ps-dropdown>

                <date-picker   v-if="reRenderDate" 
                class="rounded shadow-sm pt-0.5  focus:outline-none focus:ring-1 focus:ring-primary-500" 
                :placeholder=" $t('core__be_promotion_date') " 
                :class="(selected_date == null || selected_date == '') ? 'w-full' :'w-full'" 
                v-model:value="selected_date"  @datepick="dateFilter" :range="false" :inline="false" :autoApply="false"/>

                
            </template>

            <template #tableRow="rowProps">
                <div class="flex flex-row" v-if="rowProps.field == 'detail'">
                    <ps-text-button @click="handleEdit(rowProps.row.id)">{{ $t('core__be_btn_detail') }}</ps-text-button>
                </div>

                <!-- <div class="flex flex-row mb-2" v-if="rowProps.field == 'item_id@@title'">
                </div> -->
                <span v-if="rowProps.field == 'start_date'">
                    <ps-label>
                        {{ rowProps.row.start_timestamp ? moment(new Date(rowProps.row.start_timestamp * 1000)).format($page.props.dateFormat) : '' }}
                    </ps-label>
                </span>

                <span v-if="rowProps.field == 'end_date'">
                    <ps-label>
                        {{ rowProps.row.end_timestamp ? moment(new Date(rowProps.row.end_timestamp * 1000)).format($page.props.dateFormat) : '' }}
                    </ps-label>
                </span>


                <div v-if="rowProps.field == 'status'">
                    <ps-label class=" whitespace-nowrap dark:text-white">
                        <ps-label class="flex flex-row" v-if="getStatus(rowProps.row.start_timestamp, rowProps.row.end_timestamp) == 2"
                            textColor="text-green-600">
                            <ps-label class="w-2 h-2 my-auto rounded-full me-2" v-if="getStatus(rowProps.row.start_timestamp, rowProps.row.end_timestamp) == 2"
                                textColor="bg-green-600">
                            </ps-label>
                            {{ $t('core__be_compleated_status') }}
                        </ps-label>

                        <ps-label class="flex flex-row" v-if="getStatus(rowProps.row.start_timestamp, rowProps.row.end_timestamp) == 1"
                            textColor="text-yellow-500">
                            <ps-label class="w-2 h-2 my-auto rounded-full me-2"
                                v-if="getStatus(rowProps.row.start_timestamp, rowProps.row.end_timestamp) == 1" textColor="bg-yellow-500">
                            </ps-label>
                            {{ $t('core__be_ongoing_status') }}
                        </ps-label>

                        <ps-label class="flex flex-row" v-if="getStatus(rowProps.row.start_timestamp, rowProps.row.end_timestamp) == 3"
                            textColor="text-gray-500">
                            <ps-label class="w-2 h-2 my-auto rounded-full me-2"
                                v-if="getStatus(rowProps.row.start_timestamp, rowProps.row.end_timestamp) == 3" textColor="bg-gray-500">
                            </ps-label>
                            {{ $t('core__be_not_yet_start_status') }}
                        </ps-label>
                    </ps-label>
                </div>
            </template>

        </ps-table2>
    </ps-layout>
</template>

<script>
import { defineComponent, ref, reactive } from 'vue'
import { Link, Head, useForm } from '@inertiajs/vue3';
import PsLayout from "@/Components/PsLayout.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextButton from "@/Components/Core/Buttons/PsTextButton.vue";
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";
import PsAlert from "@/Components/Core/Alert/PsAlert.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsCsvModal from '@/Components/Core/Modals/PsCsvModal.vue';
import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";
import Dropdown from "@/Components/Core/DropdownModal/Dropdown.vue";
import PsIconButton from "@/Components/Core/Buttons/PsIconButton.vue";
import { trans } from 'laravel-vue-i18n';
import { router } from '@inertiajs/vue3';
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import DatePicker from "@/Components/Core/DateTime/DatePicker.vue";
import moment from 'moment';

export default defineComponent({
    name: "Index",
    components: {
        PsDropdownSelect,
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
        PsCsvModal,
        PsBannerIcon,
        Dropdown,
        PsIconButton,
        PsTextButton,
        DatePicker,
        PsDropdown
    },
    layout : PsLayout,
    props:{
            can:Object,
            status:Object,
            paidItems:Object,
            payment_methods:Object,
            statusList:Object,
            selectedStatus: { type: String, default: '' },
            selectedPaymentMethod: { type: String, default: '' },
            selectedDate:Object,
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
        // const showFilter = props.selectedCategory || props.selectedSubcategory || props.selectedCity || props.selectedTownship ? ref(true): ref(false);
        const showFilter = ref(false);
        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
        let selected_date = props.selectedDate ? ref(props.selectedDate) : ref('');
        let selected_status = props.selectedStatus ? ref(props.selectedStatus) : ref('');
        let selected_payment_method = props.selectedPaymentMethod ? ref(props.selectedPaymentMethod) : ref('');

        const reRenderDate = ref(true);

        const columns = [
            {
                label: 'item_module',
                field: "item_id@@title",
                type: 'String',
            },
            {
                label: 'core__be_start_date_label',
                field: "start_date",
                type: 'Date',
                action: 'Action'
            },
            {
                // label: trans('core__be_end_date_label'),
                label: 'core__be_end_date_label',
                field: "end_date",
                type: 'Date',
                action: 'Action'
            },
            {
                label: 'core__be_status_label',
                field: "status",
                type: 'Integer',
                action: 'Action'
            },
            {
                label: 'core__be_amount_label',
                field: "amount",
                type: 'Integer',
                action: 'Action'
            },
            //  {
            //     label: "Days",
            //     field: "promoted_days",
            //     type: 'Integer',
            //     action: 'Action'
            // },
            {
                label: 'core__be_paymend_method_label',
                field: "payment_method",
                type: 'String',
                action: 'Action'
            },
            {
                label: 'detail_label',
                field: "detail",
                type: 'Action',
                sort: false
            },
        ]

        function getStatus(start, end){
            let today_date = new Date();
            let start_date = new Date(start * 1000);
            let end_date = new Date(end * 1000);
            let status = 0;

            if(today_date >= start_date && today_date <= end_date)
                status = '1'
            if (today_date > start_date && today_date > end_date)
                status = '2'
            if (today_date < start_date && today_date < end_date)
                status = '3'
            return status
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

        function handleStatusfilter(value) {
            selected_status.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        function handlePaymentMethodfilter(value) {
            selected_payment_method.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        function dateFilter(value) {
            selected_date.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleClearFilter(){
            selected_date.value = '';
            selected_payment_method.value = '';
            selected_status.value = '';
            let page = 1;

            reRenderDate.value = false;
            setTimeout(() => {
                reRenderDate.value = true;
            }, 1);
            handleSearchingSorting(page);
        }

        function handleSearchingSorting(page = null,row=null){

            router.get(route('paid_item.index'),
            {
                sort_field : sort_field.value,
                sort_order: sort_order.value,
                page:page ?? props.paidItems.meta.current_page,
                row:row ?? props.paidItems.meta.per_page,
                search:search.value,
                date_filter:selected_date.value,
                status_filter: selected_status.value,
                payment_method_filter: selected_payment_method.value,
            },
            {
                preserveScroll: true,
                preserveState:true,
            })
        }

        return {
            reRenderDate,
            handleStatusfilter,
            dateFilter,
            handlePaymentMethodfilter,
            selected_date,
            selected_status,
            selected_payment_method,
            handleRow,
            handleSearchingSorting,
            handleSearching,
            handleSorting,
            columns,
            getStatus,
            showFilter,
            handleClearFilter,
            moment: moment,
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
                    label: trans('promotion_report_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },

    methods: {
        handleEdit(id){
            this.$inertia.get(route('paid_item.edit',id));
        },
    },
})
</script>
