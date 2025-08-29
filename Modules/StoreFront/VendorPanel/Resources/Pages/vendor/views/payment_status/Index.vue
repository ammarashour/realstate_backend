<template>

    <Head :title="$t('core__vendor_payment_status_module')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-label class="text-xl font-semibold" >{{ $t('core__vendor_payment_status_module') }}</ps-label>

        <!-- alert banner start -->
        <ps-banner-icon v-if="visible" :visible="visible"
            :theme="(status.flag)=='danger'?'bg-red-500':(status.flag)=='warning'?'bg-yellow-500':'bg-green-500'"
            :iconName="(status.flag)=='danger'?'close-circle':(status.flag)=='warning'?'alert-triangle':'rightalert'"
            class="text-white mb-5 sm:mb-6 lg:mb-8" iconColor="white">{{status.msg}}</ps-banner-icon>
        <!-- alert banner end -->

        <!-- data table start -->
        <ps-table2 :row="row" :search="search" :object="this.paymentStatuses"
            :columns="columns" :sort_field="sort_field" :sort_order="sort_order"
            :globalSearchPlaceholder="$t('core__be_search_payment')"
            @FilterOptionshandle="FilterOptionshandle" @handleSort="handleSorting" @handleSearch="handleSearching"
            @handleRow="handleRow" :searchable="showFilter" :eye_filter="false">

            <template #button>
                <ps-button v-if="storeCan.createPaymentStatus" @click="handleCreate()" rounded="rounded-lg" type="button"
                    class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-1 font-semibold" /> {{
                        $t('core_vendor__add_payment_status') }}</ps-button>
            </template>

            <template #responsive_button>
                <ps-button v-if="storeCan.createPaymentStatus" @click="handleCreate()" rounded="rounded-lg" type="button"
                    class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-1 font-semibold" /> {{
                        $t('core_vendor__add_payment_status') }}</ps-button>
            </template>

            <template #tableActionRow="rowProps">
                <div class="flex flex-row" v-if="rowProps.field == 'action'">
                    <ps-button :disabled="!rowProps.row.authorizations.update || rowProps.row.is_ps_default" @click="handleEdit(rowProps.row.vendor_id, rowProps.row.id)" class="me-2" rounded="rounded-lg" colors="bg-green-400 text-white"
                        padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-green-100"
                        focus="focus:outline-none focus:ring focus:ring-green-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                    </ps-button>
                    <ps-button :disabled="!rowProps.row.authorizations.delete || rowProps.row.is_ps_default"
                        @click="confirmDeleteClicked(rowProps.row.id, rowProps.row.vendor_id)" rounded="rounded-lg" colors="bg-red-400 text-white"
                        padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>
            </template>
            <template #tableRow="rowProps">
                <div v-if="rowProps.field == 'status'">
                    <ps-toggle :disabled="!rowProps.row.authorizations.update || rowProps.row.is_ps_default" v-if="rowProps.field == 'status'" :selectedValue="rowProps.row.status == 1 ? true : false"
                        @click="handlePublish(rowProps.row,rowProps.row.authorizations.update)"></ps-toggle>
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
    },
    layout: PsVendorLayout,
    props: {
        storeCan: Object,
        status: Object,
        paymentStatuses: Object,
        hideShowFieldForFilterArr: Object,
        showCoreAndCustomFieldArr: Object,
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
        console.log(props.paymentStatuses);
        const ps_danger_dialog = ref();

        const colFilterOptions = ref();
        const columns = [
            {
                field: 'action'
            },
            {
                label: trans('core_vendor__name'),
                field: 'name',
                type: 'String',
                action: 'Action'
            },
            {
                label: trans('core_vendor__description'),
                field: "description",
                type: "String",
                action: 'Action',
                sort: false
            },
            {
                label: trans("core_vendor__enable"),
                field: "status",
                type: "Integer",
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
            router.get(route('vendor_payment_status.index'),
                {
                    sort_field: sort_field.value,
                    sort_order: sort_order.value,
                    page: page ?? props.paymentStatuses.meta.current_page,
                    row: row ?? props.paymentStatuses.meta.per_page,
                    search: search.value,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }

        function confirmDeleteClicked(id, vendor_id) {
            ps_danger_dialog.value.openModal(
                trans('core_vendor__delete'),
                trans('core_vendor__delete_payment_status_info'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    router.delete(route("vendor_payment_status.destroy", [vendor_id, id]), {
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
                    label: trans('core__vendor_payment_status_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleCreate() {
            this.$inertia.get(route("vendor_payment_status.create"));
        },
        handleEdit(vendor_id, id) {
            this.$inertia.get(route('vendor_payment_status.edit', [vendor_id, id]));
        },
        handlePublish(row,hasPermission) {
            if(hasPermission && row.is_ps_default != 1){
                this.$inertia.put(route('vendor_payment_status.statusChange', [row.vendor_id, row.id]));
            }

        },
        FilterOptionshandle(value) {
            router.put(route('payment.screenDisplayUiSetting.store'),
                {
                    value,
                    sort_field: this.sort_field,
                    sort_order: this.sort_order,
                    row: this.payments.per_page,
                    search: this.search.value,
                    current_page: this.payments.current_page
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                });
        },
    },
})
</script>
