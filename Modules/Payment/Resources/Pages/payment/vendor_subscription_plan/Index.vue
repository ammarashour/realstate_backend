<template>
    <Head :title="$t('package_in_app_purchase_module')" />
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
        <ps-table2 :row="row" :search="search" :object="this.vendorSubscriptionPlans" :colFilterOptions="colFilterOptions"
            :columns="columns" :sort_field="sort_field" :sort_order="sort_order"
            @FilterOptionshandle="FilterOptionshandle" @handleSort="handleSorting" @handleSearch="handleSearching"
            @handleRow="handleRow" :searchable="showFilter"
            :globalSearchPlaceholder="$t('payment__be_search_iap_prd_id')"
            :eye_filter="false">

            <template #button>
                    <ps-button v-if="can.createPayment" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-1 font-semibold" /> {{ $t('core__be_add_vendor_subscription_plan') }}</ps-button>
            </template>
             <template #responsive_button>
                    <ps-button v-if="can.createPayment" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-1 font-semibold" /> {{ $t('core__be_add_vendor_subscription_plan') }}</ps-button>
            </template>

            <!-- <template #searchRight> -->
                <!-- currency filter -->
                <!-- <ps-dropdown align="" class="h-10 me-2">
                    <template #select>
                        <ps-dropdown-select
                            class="w-56"
                            :placeholder="$t('payment__be_currency')"
                            :selectedValue="(selected_currency == '' || selected_currency == 'all') ? '' : currencies.filter(option => option.id == selected_currency)[0].currency_short_form" />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleCurrencyfilter('all')">
                                    <ps-label class="text-gray-200 ms-2">{{$t('core__be_select_all')}}</ps-label>
                                </div>
                                <div v-for="currency in currencies" :key="currency.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleCurrencyfilter(currency.id)">
                                    <ps-label class="ms-2" :class="currency.id == selected_currency ? ' font-bold' : ''">
                                        {{ currency.name }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>
                </ps-dropdown> -->

                <!-- type filter -->
                <!-- <ps-dropdown align="" class="h-10">
                    <template #select>
                        <ps-dropdown-select
                            class="w-56"
                            :placeholder="$t('payment__be_type')"
                            :selectedValue="(selected_duration == '' || selected_duration == 'all') ? '' : durations.filter(option => option.name == selected_duration)[0].name" />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleTypefilter('all')">
                                    <ps-label class="text-gray-200 ms-2">{{$t('core__be_select_all')}}</ps-label>
                                </div>
                                <div v-for="type in durations" :key="type.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleTypefilter(type.name)">
                                    <ps-label class="ms-2" :class="type.id == selected_duration ? ' font-bold' : ''">
                                        {{ type.name }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>
                </ps-dropdown> -->
            <!-- </template> -->

            <template #tableActionRow="rowProps">
                <div class="flex flex-row" v-if="rowProps.field == 'action'">
                    <ps-button :disabled="!rowProps.row.authorizations.update" @click="handleEdit(rowProps.row.id)" class="me-2" rounded="rounded-lg" colors="bg-green-400 text-white"
                        padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-green-100"
                        focus="focus:outline-none focus:ring focus:ring-green-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                    </ps-button>
                    <ps-button :disabled="!rowProps.row.authorizations.delete" @click="confirmDeleteClicked(rowProps.row.id)" rounded="rounded-lg" colors="bg-red-400 text-white"
                        padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>
            </template>

            <template #tableRow="rowProps">
                <ps-toggle :disabled="!rowProps.row.authorizations.update" v-if="rowProps.field == 'is_most_popular_plan'" :selectedValue="rowProps.row.is_most_popular_plan == 1 ? true : false"
                    @click="handleIsMostPopularPlan(rowProps.row.core_keys_id,rowProps.row.authorizations.update)"></ps-toggle>

                <ps-toggle :disabled="!rowProps.row.authorizations.update" v-if="rowProps.field == 'status'" :selectedValue="rowProps.row.status == 1 ? true : false"
                    @click="handlePublish(rowProps.row.core_keys_id,rowProps.row.authorizations.update)"></ps-toggle>
            </template>
s
        </ps-table2>
        <!-- data table end -->
    </ps-layout>
</template>

<script>
import { defineComponent, ref, reactive } from 'vue'
import { Link, Head } from '@inertiajs/vue3';
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
import PsIconButton from "@/Components/Core/Buttons/PsIconButton.vue";
import { trans } from 'laravel-vue-i18n';
import { router } from '@inertiajs/vue3';
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";

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
    layout : PsLayout,
    props: {
        can: Object,
        status: Object,
        vendorSubscriptionPlans: Object,
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
        search: String,
    },
    setup(props) {
        // For data table
        const ps_danger_dialog = ref();
        let visible = ref(false)

        const colFilterOptions = ref()

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
            router.get(route('vendor_subscription_plan.index'),
                {
                    sort_field: sort_field.value,
                    sort_order: sort_order.value,
                    page: page ?? props.vendorSubscriptionPlans.meta.current_page,
                    row: row ?? props.vendorSubscriptionPlans.meta.per_page,
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
                label: trans('action_label'),
                field: "action",
                type: 'Action'
            },
            // {
            //     label: trans('payment__iap_prd_id'),
            //     field: "in_app_purchase_prd_id",
            //     type: "String",
            //     action: 'Action',
            //     sort:false
            // },
            {
                label: trans('payment__vendor_sp_title'), // desc col in db
                field: "value",
                type: "String",
                action: 'Action',
                // sort:false
            },
            {
                label: trans('payment__vendor_sp_duration'),
                field: props.durationKey,
                type: 'String',
                action: 'Action'
            },
            {
                label: trans('payment__vendor_sp_sale_price'),
                field: props.salePriceKey,
                type: 'Integer',
                action: 'Action'
            },
            {
                label: trans('payment__vendor_sp_discount_price'),
                field: props.discountPriceKey,
                type: 'Integer',
                action: 'Action'
            },
            {
                label: trans('payment__vendor_sp_currency'),
                field: props.currencyKey,
                type: 'String',
                action: 'Action'
            },
            {
                label: trans('payment__vendor_sp_is_most_popular_plan'),
                field: props.isMostPopularPlanKey,
                type: "String",
                action: 'Action'
            },
            {
                label: trans('payment__vendor_sp_status'),
                field: props.statusKey,
                type: "String",
                action: 'Action'
            },
        ]

        function confirmDeleteClicked(id) {
            ps_danger_dialog.value.openModal(
                trans('payment__delete_package_iap'),
                trans('payment__delete_vendor_subsciption_info'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    router.delete(route("vendor_subscription_plan.destroy", id), {
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

        return {
            columns,
            ps_danger_dialog,
            confirmDeleteClicked,
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
                    label: trans('vendor_subscription_plan_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleCreate() {
            this.$inertia.get(route("vendor_subscription_plan.create"));
        },
        handleEdit(id){
            this.$inertia.get(route('vendor_subscription_plan.edit',id));
        },
        handlePublish(id,hasPermission){
            if(hasPermission){
                this.$inertia.put(route('vendor_subscription_plan.statusChange',id));
            }

        },
        handleIsMostPopularPlan(id,hasPermission){
            if(hasPermission){
                this.$inertia.put(route('vendor_subscription_plan.handleIsMostPopularPlan',id));
            }

        },

        FilterOptionshandle(value) {
            router.put(route('vendor_subscription_plan.screenDisplayUiSetting.store'),
                {
                    value,
                    sort_field: this.sort_field,
                    sort_order: this.sort_order,
                    row: this.vendorSubscriptionPlans.per_page,
                    search: this.search.value,
                    current_page: this.vendorSubscriptionPlans.current_page
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                });
        },
    },
})
</script>
