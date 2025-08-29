<template>
    <Head :title="$t('package_in_app_purchase_module')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <!-- alert banner start -->
        <!-- <ps-banner-icon  v-if="visible" :visible="visible"
            :theme="(status.flag)=='danger'?'bg-red-500':(status.flag)=='warning'?'bg-yellow-500':'bg-green-500'"
            :iconName="(status.flag)=='danger'?'close-circle':(status.flag)=='warning'?'alert-triangle':'rightalert'"
            class="text-white mb-4"
            iconColor="white">{{status.msg}}</ps-banner-icon> -->
        <!-- alert banner end -->

        <!-- data table start -->
        <ps-table2 :row="row" :search="search" :object="subs_history" :colFilterOptions="colFilterOptions"
            :columns="columns" :sort_field="sort_field" :sort_order="sort_order"
            @FilterOptionshandle="FilterOptionshandle" @handleSort="handleSorting" @handleSearch="handleSearching"
            @handleRow="handleRow" :searchable="showFilter" :defaultSearch="false"
            :globalSearchPlaceholder="$t('payment__be_search_iap_prd_id')"
            :eye_filter="false">

            <template #tableRow="rowProps">

                <div class="flex flex-row" v-if="rowProps.field == 'duration'">
                    <ps-label>
                        {{ durations.filter((duration)=>duration.id == rowProps.row.package.payment_attributes.duration)[0].name }}
                    </ps-label>
                </div>

                <div class="flex flex-row" v-if="rowProps.field == 'status'">
                    <span v-if="rowProps.row.status === 1" class="text-green-500">
                        Active
                    </span>
                    <span v-else class="text-red-500">
                        Expired
                    </span>
                </div>
            </template>
s
        </ps-table2>
        <!-- data table end -->
    </ps-layout>
</template>

<script>
import { defineComponent, ref, reactive } from 'vue'
import { Link, Head } from '@inertiajs/vue3';
// import PsLayout from "@/Components/PsLayout.vue";
import PsVendorLayout from "@vendorPanel/vendor/components/layouts/container/PsVendorLayout.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";
import PsAlert from "@/Components/Core/Alert/PsAlert.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";
import PsIconButton from "@/Components/Core/Buttons/PsIconButton.vue";
import { trans } from 'laravel-vue-i18n';
import { router } from '@inertiajs/vue3';
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsVendorLayoutVue from '../../components/layouts/container/PsVendorLayout.vue';

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
        PsIconButton,
        PsDropdown,
        PsDropdownSelect
    },
    layout : PsVendorLayout,
    props: {

        subs_history: Object,
        currencies: Object,
        durations: Object,
        hideShowFieldForFilterArr: Object,
        showCoreAndCustomFieldArr: Object,
        authUser: Object,
        durationKey: String,
        discountPriceKey: String,
        salePriceKey: String,
        isMostPopularPlanKey: String,
        statusKey: String,
        currencyKey: String,
        selected_duration:Object,
        selected_currency:Object,
        sort_field: {
            type: String,
            default: "",
        },
        sort_order: {
            type: String,
            default: 'desc',
        },
    },
    setup(props) {
        console.log(props.subs_history);
        // For data table
        const ps_danger_dialog = ref();
        let visible = ref(false)

        const colFilterOptions = ref();

        const durations = [
            {id:1,name:'One Month'},
            {id:2,name:'Two Months'},
            {id:3,name:'Three Months'},
            {id:6,name:'Six Months'},
            {id:12,name:'One Year'}
        ];

        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
        let selected_duration = props.selected_duration ? ref(props.selected_duration) : ref('');
        let selected_currency = props.selected_currency ? ref(props.selected_currency) : ref('');

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

        function handleTypefilter(value) {
            selected_duration.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleCurrencyfilter(value) {
            selected_currency.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleSearchingSorting(page = null, row = null) {
            router.get(route('vendor_subscription_history.index'),
                {
                    sort_field: sort_field.value,
                    sort_order: sort_order.value,
                    page: page ?? props.subs_history.meta.current_page,
                    row: row ?? props.subs_history.meta.per_page,
                    search: search.value,
                    type_filter: selected_duration.value,
                    currency_filter: selected_currency.value,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }

        const columns = [

            {
                label: trans('vendor_subscription_transaction_id'), // desc col in db
                field: "transaction_id",
                type: "String",
                action: 'Action',
                // sort:false
            },
            {
                label: trans('vendor_subscription_hist_plan'),
                field: 'plan_name',
                type: 'String',
                action: 'Action'
            },
            {
                label: trans('vendor_subscription_hist_duration'),
                field: 'duration',
                type: 'String',
                action: 'Action'
            },
            {
                label: trans('vendor_subscription_payment_method'),
                field: "payment_method",
                type: 'String',
                action: 'Action'
            },
            {
                label: trans('vendor_subscription_amount'),
                field: "price",
                type: 'Integer',
                action: 'Action'
            },
            {
                label: trans('vendor_subscription_status'),
                field: "status",
                type: 'Integer',
                action: 'Action'
            },
            {
                label: trans('vendor_subscription_purchase_date'),
                field: "added_date",
                type: "String",
                action: 'Action'
            },
            {
                label: trans('vendor_subscription_expired_date'),
                field: "expired_date",
                type: "String",
                action: 'Action'
            }
        ]


        return {
            columns,
            ps_danger_dialog,
            colFilterOptions,
            visible,
            handleSorting,
            handleSearchingSorting,
            handleRow,
            handleSearching,
            handleTypefilter,
            handleCurrencyfilter,
            selected_duration,
            selected_currency,
            durations
        }
    },
    computed: {
        breadcrumb() {

            return [
                {
                    label: trans('core__vendor_my_store_module'),
                    url: route('vendor_info.index')
                },
                {
                    label: trans('core__vendor_subscription_hist'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        FilterOptionshandle(value) {
            router.put(route('vendor_subscription_history.index'),
                {
                    value,
                    sort_field: this.sort_field,
                    sort_order: this.sort_order,
                    row: this.subs_history.meta.per_page,
                    current_page: this.subs_history.meta.current_page
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                });
        },
    },
})
</script>
