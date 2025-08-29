<template>
    <Head :title="$t('vendor_list_module')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />

        <!-- alert banner start -->
        <ps-banner-icon v-if="visible" :visible="visible"
            :theme="(status.flag)=='danger'?'bg-red-500':(status.flag)=='warning'?'bg-yellow-500':'bg-green-500'"
            :iconName="(status.flag)=='danger'?'close-circle':(status.flag)=='warning'?'alert-triangle':'rightalert'"
            class="text-white mb-5 sm:mb-6 lg:mb-8" iconColor="white">{{status.msg}}</ps-banner-icon>
        <!-- alert banner end -->
        <ps-table2 :object="this.vendorApplications" :columns="columns" :colFilterOptions="colFilterOptions"
            @handleRow="handleRow" @handleSort="handleSorting" @handleSearch="handleSearching"
            :sort_field="sort_field" :sort_order="sort_order" :eye_filter="false" :searchable="showFilter">

            <template #searchRight>
                <ps-text-button v-if="showFilter" @click="handleClearFilter()"
                    class="flex text-sm items-center text-red-400 dark:text-red-400" padding="py-1 px-4">
                    <ps-icon theme="dark:text-red-400" name="cross" class="me-3" />
                    {{ $t('core__be_clear_filter') }}
                </ps-text-button>
                <ps-icon-button :colors="!showFilter ? '' : 'bg-primary-500 text-white dark:text-secondary-800'" focus="" padding="py-1 px-4"
                    hover="hover:bg-primary-500 hover:text-white" :border="!showFilter ? ' border border-secondary-200' : 'border border-primary-500'"
                    leftIcon="filter" @click="showFilter = !showFilter">{{ $t('core__be_filter') }}</ps-icon-button>
            </template>

            <template #Filter>
                <!-- added date filter -->
                <date-picker
                    v-if="reRenderDate && colFilterOptions.filter(option => option.key == 'added_date')[0] ? !colFilterOptions.filter(option => option.key == 'added_date')[0]?.hidden : false"
                    :placeholder="$t('core__be_posted_date')" @datepick="handleAddedDatefilter"
                    class="rounded shadow-sm pt-0.5   focus:outline-none focus:ring-1 focus:ring-primary-500 "
                    :class="(selected_added_date == null || selected_added_date == '') ? 'w-full' : 'w-full'"
                    v-model:value="selected_added_date" :range="true" :inline="false" :autoApply="false" />
            </template>

            <template #tableActionRow="rowProps">
                <div class="flex flex-row" v-if="rowProps.field == 'action'">
                    <ps-button :disabled="!rowProps.row.authorizations.delete"
                        @click="confirmDeleteClicked(rowProps.row.id)" colors="bg-red-400 text-white" padding="p-1.5"
                        hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>
            </template>

            <template #tableRow="rowProps">
                <div class="flex flex-row mb-2" v-if="rowProps.field == 'detail'">
                    <ps-text-button @click="handleDetail(rowProps.row.id)">{{ $t('core__be_btn_detail') }}</ps-text-button>
                </div>

                <div class="flex flex-row" v-if="rowProps.field == 'accept'">
                    <ps-button :disabled="!rowProps.row.authorizations.update" @click="[form.status='accept',handleStatusChange(rowProps.row.id)]"
                        colors="bg-green-500 text-white" hover="hover:outline-none hover:ring hover:ring-green-100"
                        focus="focus:outline-none focus:ring focus:ring-green-200">{{ $t('core__be_accept_lbl') }}</ps-button>
                </div>
                <div class="flex flex-row" v-if="rowProps.field == 'reject'">
                    <ps-button :disabled="!rowProps.row.authorizations.update" @click="[form.status='reject',handleStatusChange(rowProps.row.id)]"
                        colors="bg-red-500 text-white" hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">{{ $t('core__be_reject_lbl') }}</ps-button>
                </div>
            </template>
        </ps-table2>
    </ps-layout>
</template>

<script>
import { ref, defineComponent, watch } from "vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import { trans } from 'laravel-vue-i18n';
import PsLayout from "@/Components/PsLayout.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsTextButton from "@/Components/Core/Buttons/PsTextButton.vue";
import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";
import PsIconButton from "@/Components/Core/Buttons/PsIconButton.vue";
import DatePicker from "@/Components/Core/DateTime/DatePicker.vue";
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";

    export default defineComponent({
        name: 'Index',
        components: {
            Head,
            PsBreadcrumb2,
            PsTable2,
            PsIcon,
            PsButton,
            PsLabel,
            PsTextButton,
            PsBannerIcon,
            PsIconButton,
            DatePicker,
            PsDangerDialog
        },
        layout: PsLayout,
        props: {
            vendorApplications : Object,
            showVendorCols:Object,
            showCoreAndCustomFieldArr : Object,
            hideShowFieldForFilterArr : Object,
            can : Object,
            status: Object,
            sort_field: {
                type: String,
                default: "",
            },
            sort_order: {
                type: String,
                default: 'desc',
            },
            selectedAddedDate: {
                type: String,
                default: '',
            },
            search: String,
        },
        setup(props) {
            let visible = ref(false);
            const ps_danger_dialog = ref();
            const colFilterOptions = ref();
            const showFilter = ref(false);
            const reRenderDate = ref(true);
            let search = props.search ? ref(props.search) : ref('');
            let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
            let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
            let selected_added_date = props.selectedAddedDate ? ref(props.selectedAddedDate) : ref('');
            const columns = [
                {
                    label: trans('core__be_action_label'),
                    field: "action",
                    type: "String"
                },
                {
                    label: trans('core__be_vendor_user_name'),
                    field: "owner_name",
                    relation: "owner",
                    relationField: "name",
                    type: "String"
                },
                {
                    label: trans('core__be_vendor_user_email'),
                    field: "email",
                    type: 'String',
                },
                {
                    label: trans('core__be_vendor_name'),
                    field: "name",
                    type: 'String',
                },
                {
                    label: trans('core__be_accept_lbl'),
                    field: "accept",
                    type: "String",
                    sort: false,
                },
                {
                    label: trans('core__be_reject_lbl'),
                    field: "reject",
                    type: "String",
                    sort: false,
                },
                {
                    label: trans('core__be_vendor_applied_date'),
                    field: "added_date",
                    type: "Timestamp",
                    sort: false,
                },
                {
                    label: trans('detail_label'),
                    field: "detail",
                    type: 'Action',
                    sort: false,
                },
            ];

            let form = useForm(
                {
                    status: '',
                    "_method": "put"
                }
            )

            function confirmDeleteClicked(id) {
                ps_danger_dialog.value.openModal(
                    trans('core__be_delete_vendor'),
                    trans('core__be_delete_vendor_info'),
                    trans('core__be_btn_confirm'),
                    trans('core__be_btn_cancel'),
                    () => {
                        router.delete(route("pending_vendor.destroy", id), {
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

            function handleStatusChange(id) {
                this.$inertia.put(route('pending_vendor.statusChange', id), form);
            }

            function handleSorting(value) {
                sort_field.value = value.field
                sort_order.value = value.sort_order
                handleSearchingSorting();
            }

            function handleSearching(value) {
                search.value = value;
                let page = 1;
                handleSearchingSorting(page);
            }

            function handleAddedDatefilter(value) {
                selected_added_date.value = value
                let page = 1;
                handleSearchingSorting(page);
            }

            function handleClearFilter() {
                selected_added_date.value = '';
                search.value = '';
                handleSearchingSorting();

                reRenderDate.value = false;
                setTimeout(() => {
                    reRenderDate.value = true;
                }, 100);
            }

            function handleRow(value) {
                handleSearchingSorting(1, value);
            }

            function handleSearchingSorting(page = null, row = null) {
                router.get(route('pending_vendor.index'),
                    {
                        sort_field: sort_field.value,
                        sort_order: sort_order.value,
                        page: page ?? props.vendorApplications.meta.current_page,
                        row: row ?? props.vendorApplications.meta.per_page,
                        search: search.value,
                        added_date_filter: selected_added_date.value,
                    },
                    {
                        preserveScroll: true,
                        preserveState: true,
                    })
            }

            return {
                visible,
                ps_danger_dialog,
                columns,
                showFilter,
                colFilterOptions,
                form,
                reRenderDate,
                sort_field,
                sort_order,
                selected_added_date,
                handleStatusChange,
                handleSorting,
                handleAddedDatefilter,
                handleSearchingSorting,
                handleSearching,
                handleClearFilter,
                confirmDeleteClicked,
                handleRow,
            }
        },
        methods: {
            handleDetail(id) {
                this.$inertia.get(route('pending_vendor.show', id));
            },
            handleDownload(id) {
                this.$inertia.get(route('pending_vendor.download_document', id));
            }
        },
        created() {
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
        computed: {
            breadcrumb() {
                return [
                    {
                        label: trans('core__be_dashboard_label'),
                        url: route('admin.index')
                    },
                    {
                        label: trans('pending_vendor_module'),
                        color: "text-primary-500"
                    }
                ]
            },
        },
    })
</script>
