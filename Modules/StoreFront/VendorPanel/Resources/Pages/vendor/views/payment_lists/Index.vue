<template>

    <Head :title="$t('payment_module')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <!-- noti start -->
        <div v-if="false" class="flex justify-between gap-6 items-start bg-yellow-50 p-6 rounded border-l-4 border-yellow-500 mb-5 sm:mb-6 lg:mb-8">
            <ps-label textColor="text-secondary-500">{{$t("core_vendor__paypal_payment_only")}}</ps-label>
            <ps-label textColor="leading-5 text-sm text-secondary-400 flex items-center gap-2 cursor-pointer" @click="showNoti = false">
                <ps-icon name="close-fill" w="16" h="16" viewBox="0 0 16 16"/>
                {{ $t("core_be__dismiss") }}
            </ps-label>
        </div>
        <!-- noti end -->

        <ps-label class="text-xl font-semibold" >{{ $t('core__vendor_payment_lists_module') }}</ps-label>

        <!-- alert banner start -->
        <ps-banner-icon v-if="visible" :visible="visible"
            :theme="(status.flag)=='danger'?'bg-red-500':(status.flag)=='warning'?'bg-yellow-500':'bg-green-500'"
            :iconName="(status.flag)=='danger'?'close-circle':(status.flag)=='warning'?'alert-triangle':'rightalert'"
            class="text-white mb-5 sm:mb-6 lg:mb-8" iconColor="white">{{status.msg}}</ps-banner-icon>
        <!-- alert banner end -->

        <!-- data table start -->
        <ps-table2 :row="row" :search="search" :object="this.payments" :colFilterOptions="colFilterOptions"
            :columns="columns" :sort_field="sort_field" :sort_order="sort_order"
            :globalSearchPlaceholder="$t('core__be_search_payment')"
            @FilterOptionshandle="FilterOptionshandle" @handleSort="handleSorting" @handleSearch="handleSearching"
            @handleRow="handleRow" :searchable="showFilter" :eye_filter="false">

            <template #tableActionRow="rowProps">
                <div class="flex flex-row" v-if="rowProps.field == 'action'">
                    <ps-button :disabled="!rowProps.row.authorizations.update" @click="handleEdit(rowProps.row.id)" class="me-2" rounded="rounded-lg" colors="bg-green-400 text-white"
                        padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-green-100"
                        focus="focus:outline-none focus:ring focus:ring-green-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                    </ps-button>
                </div>
            </template>
            <template #tableRow="rowProps">
                <div v-if="rowProps.field == 'status'" v-show="colFilterOptions.filter((item) => item.key == 'status').length == 0
                            ? true : !colFilterOptions.filter((item) => item.key == 'status')[0].hidden ">
                    <ps-toggle :disabled="!rowProps.row.authorizations.update" v-if="rowProps.field == 'status'" :selectedValue="rowProps.row.status == 1 ? true : false"
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
        can: Object,
        status: Object,
        payments: Object,
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
        let visible = ref(false)
        console.log(props.payments);

        const colFilterOptions = ref();
        const columns = [
            {
                field: 'action'
            },
            {
                label: trans('Name'),
                field: 'payment_id@@name',
                type: 'String',
                action: 'Action'
            },
            {
                label: trans('Description'),
                field: "payment_id",
                relation: "payment",
                type: "String",
                relationField: "description",
                action: 'Action',
                sort: false
            },
            {
                label: trans("Enable"),
                field: "status",
                type: "Integer",
                action: "Action",
            },
        ];

        const showFilter = ref(false);
        const clearFilter = ref(false);
        const showNoti = ref(true);
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
            router.get(route('vendor_payment.index'),
                {
                    sort_field: sort_field.value,
                    sort_order: sort_order.value,
                    page: page ?? props.payments.meta.current_page,
                    row: row ?? props.payments.meta.per_page,
                    search: search.value,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }


        return { visible, columns, colFilterOptions,
            showFilter,
            clearFilter,
            visible,
            handleSorting,
            handleSearchingSorting,
            handleClearFilter,
            handleRow,
            handleSearching, showNoti}
    },
    computed: {
        breadcrumb() {

            return [
                {
                    label: trans('core__vendor_my_store_module'),
                    url: route('vendor_info.index')
                },
                {
                    label: trans('core__vendor_payment_lists_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleCreate() {
            this.$inertia.get(route("payment.create"));
        },
        handleEdit(id) {
            this.$inertia.get(route('vendor_payment.edit', id));
        },
        handlePublish(row,hasPermission) {
            if(hasPermission){
                this.$inertia.put(route('vendor_payment.statusChange', row.id));
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
    created() {
        // this.columns = this.showCoreAndCustomFieldArr.map(column => {
        //     return {
        //         action: column.action,
        //         field: column.field,
        //         label: trans(column.label),
        //         sort: column.sort,
        //         type: column.type,
        //     };
        // });

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
