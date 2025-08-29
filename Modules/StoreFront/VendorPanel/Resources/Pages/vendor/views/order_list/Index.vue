<template>

    <Head :title="$t('core__vendor_order_list_module')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-label class="text-xl font-semibold" >{{ $t('core__vendor_order_list_module') }}</ps-label>

        <!-- alert banner start -->
        <ps-banner-icon v-if="visible" :visible="visible"
            :theme="(status.flag)=='danger'?'bg-red-500':(status.flag)=='warning'?'bg-yellow-500':'bg-green-500'"
            :iconName="(status.flag)=='danger'?'close-circle':(status.flag)=='warning'?'alert-triangle':'rightalert'"
            class="text-white mb-5 sm:mb-6 lg:mb-8" iconColor="white">{{status.msg}}</ps-banner-icon>
        <!-- alert banner end -->

        <!-- data table start -->
        <ps-table2 :row="row" :search="search" :object="this.orders"
            :columns="columns" :sort_field="sort_field" :sort_order="sort_order"
            :globalSearchPlaceholder="$t('core__be_search_payment')"
            @handleSort="handleSorting" @handleSearch="handleSearching"
            @handleRow="handleRow" :searchable="showFilter" :eye_filter="false">

            <template #tableActionRow="rowProps">
                <div class="flex flex-row" v-if="rowProps.field == 'action'">
                    <ps-button :disabled="!rowProps.row.authorizations.update" @click="handleEdit(rowProps.row.id)" class="me-2" rounded="rounded-lg" colors="bg-green-400 text-white"
                        padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-green-100"
                        focus="focus:outline-none focus:ring focus:ring-green-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                    </ps-button>
                    <ps-button :disabled="!rowProps.row.authorizations.delete"
                        @click="confirmDeleteClicked(rowProps.row.id)" rounded="rounded-lg" colors="bg-red-400 text-white"
                        padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>
            </template>
            <template #tableRow="rowProps">
                <div class="flex items-center gap-2" v-if="rowProps.field == 'owner_id@@name'">
                    <img class="w-6 h-6 rounded-full object-cover" width="24px" height="24px"
                    v-lazy="{ src: $page.props.thumb2xUrl + '/' + rowProps.row.owner?.user_cover_photo, loading: $page.props.sysImageUrl + '/loading_gif.gif', error: $page.props.sysImageUrl + '/default_profile.png' }" >
                    <ps-label>{{ rowProps.row.owner.name }}</ps-label>
                </div>
                <div class="" v-if="rowProps.field == 'total_price'">
                    <ps-label>{{rowProps.row.vendor_transaction?.currency?.currency_symbol}}{{ rowProps.row.total_price }}</ps-label>
                </div>
                <div class="" v-if="rowProps.field == 'vendor_payment_status_id@@name'">
                    <ps-badge v-for="(paymentStatus, index) in vendorPaymentStatuses.filter((status)=>status.id == rowProps.row.vendor_payment_status_id)" :key="index"
                        :bgColorCode="paymentStatus.colour+'20'"
                        :textColorCode="paymentStatus.colour" theme="" class="m-2">
                        {{ paymentStatus.name }}
                    </ps-badge>
                </div>
                <div class="" v-if="rowProps.field == 'order_status_id@@name'">
                    <ps-label :style="'color:'+rowProps.row.order_status.colour">{{ rowProps.row.order_status.name }}</ps-label>
                </div>
            </template>
        </ps-table2>
        <!-- data table end -->
    </ps-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { Link, Head } from '@inertiajs/vue3';
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
import PsBadge from "@/Components/Core/Badge/PsBadge.vue"
import { trans } from 'laravel-vue-i18n';
import { router } from '@inertiajs/vue3';

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
        PsBadge,
    },
    layout: PsVendorLayout,
    props: {
        status: Object,
        orders: Object,
        vendorPaymentStatuses: Object,
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
        let visible = ref(false)
        console.log(props.orders);
        const ps_danger_dialog = ref();

        const colFilterOptions = ref();
        const columns = [
            {
                field: 'action'
            },
            {
                label: trans('core_vendor__order_id'),
                field: 'order_code',
                type: 'String',
                action: 'Action'
            },
            {
                label: trans('core_vendor__customer_info'),
                field: "owner_id@@name",
                type: "String",
                action: 'Action',
            },
            // {
            //     label: trans('core_vendor__item_qty'),
            //     field: "quantity",
            //     type: "String",
            //     action: 'Action'
            // },
            {
                label: trans('core_vendor__total_amount'),
                field: "total_price",
                type: "String",
                action: 'Action'
            },
            {
                label: trans('core_vendor__payment_status'),
                field: "vendor_payment_status_id@@name",
                type: "String",
                action: "Action",
            },
            {
                label: trans('core_vendor__order_status'),
                field: "order_status_id@@name",
                type: "String",
                action: 'Action',
            },
            {
                label: trans("core_vendor__order_date"),
                field: "order_date",
                type: "Timestamp",
                action: "Action",
            },
        ];

        const showFilter = ref(false);
        const clearFilter = ref(false);
        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');

        function handleSorting(value) {
            sort_field.value = value.field
            sort_order.value = value.sort_order
            handleSearchingSorting()
        }

        function handleClearFilter() {
            selected_cat.value = 'all';
            selected_sub_cat.value = 'all';
            selected_city.value = 'all';
            selected_township.value = 'all';
            handleSearchingSorting();
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
            router.get(route('vendor_order_list.index'),
                {
                    sort_field: sort_field.value,
                    sort_order: sort_order.value,
                    page: page ?? props.orders.meta.current_page,
                    row: row ?? props.orders.meta.per_page,
                    search: search.value,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }

        function confirmDeleteClicked(id) {
            ps_danger_dialog.value.openModal(
                trans('core_vendor__delete'),
                trans('core_vendor__delete_order_info'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    router.delete(route("vendor_order_list.destroy", id), {
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
            visible, columns, colFilterOptions,
            showFilter,
            clearFilter,
            visible,
            handleSorting,
            handleSearchingSorting,
            handleClearFilter,
            handleRow,
            handleSearching,
            ps_danger_dialog,
            confirmDeleteClicked
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
                    label: trans('core__vendor_order_list_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleCreate() {
            this.$inertia.get(route("vendor_order_status.create"));
        },
        handleEdit(id) {
            this.$inertia.get(route('vendor_order_list.edit', id));
        },
        handlePublish(vendor_id,id,hasPermission) {
            if(hasPermission){
                this.$inertia.put(route('vendor_order_status.statusChange', [vendor_id, id]));
            }

        }
    },
})
</script>
