<template>
    <Head :title="$t('offline_payment_setting_module')" />
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
        <ps-table2 :row="row" :search="search" :object="this.offlinePayments" :colFilterOptions="colFilterOptions"
            :columns="columns" :sort_field="sort_field" :sort_order="sort_order"
            :globalSearchPlaceholder="$t('core__be_searches')"
            @FilterOptionshandle="FilterOptionshandle" @handleSort="handleSorting" @handleSearch="handleSearching"
            @handleRow="handleRow" :searchable="showFilter"
            :eye_filter="false">

            <template #button>
                    <ps-button v-if="can.createPayment" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-1 font-semibold" /> {{ $t('payment__add_offline_payment') }}</ps-button>
            </template>
             <template #responsive_button>
                    <ps-button v-if="can.createPayment" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-1 font-semibold" /> {{ $t('payment__add_offline_payment') }}</ps-button>
            </template>

            <template #tableRow="rowProps">
                <ps-toggle :disabled="!rowProps.row.authorizations.update" v-if="rowProps.field == 'status'" :selectedValue="rowProps.row.status == 1 ? true : false"
                    @click="handlePublish(rowProps.row.core_keys_id,rowProps.row.authorizations.update)"></ps-toggle>
            </template>

            <template #tableActionRow="rowProps">
                <div class="flex flex-row " v-if="rowProps.field == 'action'">
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
import PsCsvModal from '@/Components/Core/Modals/PsCsvModal.vue';
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
        PsCsvModal,
        PsBannerIcon,
        PsIconButton
    },
    layout : PsLayout,
    props: {
        can: Object,
        status: Object,
        offlinePayments: Object,
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
        // For data table
        const ps_danger_dialog = ref();
        let visible = ref(false)

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
            router.get(route('offline_payment_setting.index'),
                {
                    sort_field: sort_field.value,
                    sort_order: sort_order.value,
                    page: page ?? props.offlinePayments.meta.current_page,
                    row: row ?? props.offlinePayments.meta.per_page,
                    search: search.value,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }

        const colFilterOptions = reactive([
            {
                label: trans('payment__offline_pmt_title'),
                key: "title",
                hidden: false
            },
            {
                label: trans('payment__offline_pmt_desc'),
                key: "description",
                hidden: false
            },
            {
                label: trans('payment__offline_pmt_status'),
                key: "status",
                hidden: false
            },
        ])

        const columns = [
            {
                label: trans('action_label'),
                field: "action",
                type: 'Action'
            },
            {
                label: trans('payment__offline_pmt_title'),
                field: "title",
                type: 'String',
                action: "Action",
            },
            {
                label: trans('payment__offline_pmt_desc'),
                field: "description",
                type: "String",
                action: 'Action',
                sort:false
            },
            {
                label: trans('core__publish_label'),
                field: "status",
                type: "String",
                action: 'Action'
            },
        ]

        function confirmDeleteClicked(id) {
            ps_danger_dialog.value.openModal(
                trans('core__be_delete_offline_pmt'),
                trans('core__be_delete_offline_payment_info'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    router.delete(route("offline_payment_setting.destroy", id), {
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
            showFilter,
            clearFilter,
            visible,
            handleSorting,
            handleSearchingSorting,
            handleClearFilter,
            handleRow,
            handleSearching,
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
                    label: trans('offline_payment_setting_module'),
                    color: "text-primary-500"
                }
            ]
        }
    },
    methods: {
        handleCreate() {
            this.$inertia.get(route("offline_payment_setting.create"));
        },
        handleEdit(id){
            this.$inertia.get(route('offline_payment_setting.edit',id));
        },
        handlePublish(id,hasPermission){
            if(hasPermission){
                this.$inertia.put(route('offline_payment_setting.statusChange',id));
            }

        },
        FilterOptionshandle(value) {
            router.put(route('offline_payment_setting.screenDisplayUiSetting.store'),
                {
                    value,
                    sort_field: this.sort_field,
                    sort_order: this.sort_order,
                    row: this.offlinePayments.per_page,
                    search: this.search.value,
                    current_page: this.offlinePayments.current_page
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                });
        },
    },
})
</script>
