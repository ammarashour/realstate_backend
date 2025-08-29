<template>

    <Head :title="$t('offline_package_module')" />
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

        <ps-table2 :row="row" :search="search"  :object="transactions"
                :columns="columns" :sort_field="sort_field" :sort_order="sort_order"
                @FilterOptionshandle="FilterOptionshandle" @handleSort="handleSorting" @handleSearch="handleSearching"
                @handleRow="handleRow" :searchable="showFilter" :eye_filter="false">
            <template #searchRight>
                 <!-- status filter-->
                <ps-dropdown align="" class="me-2 w-56 h-10">
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__be_status_label')"
                            :selectedValue="(selected_status == '' || selected_status == 'all') ? '' : types.filter(option => option.id == selected_status)[0].name" />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleStatusfilter('all')">
                                    <ps-label class="text-gray-200 ms-2">{{$t('core__be_select_all')}}</ps-label>
                                </div>
                                <div v-for="status in types" :key="status.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleStatusfilter(status.id)">
                                    <ps-label class="ms-2" :class="status.id == selected_status ? ' font-bold' : ''">
                                        {{ $t(status.name) }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>
                </ps-dropdown>
                <!-- <ps-text-button v-if="showFilter" @click="clearFilter = true" class="flex text-sm items-center text-red-400"
                    padding="py-1 px-4">
                    <ps-icon name="cross" viewBox="0 0 14 14" w="14" h="14" class="me-3" />
                    {{ $t('core__be_clear_filter') }}
                </ps-text-button>
                <ps-icon-button :colors="!showFilter? '' : 'bg-primary-500 text-white'" focus="" padding="py-1 px-4"
                    hover="hover:bg-primary-500 hover:text-white" border="border border-grey-200" leftIcon="filter"
                    @click="showFilter = !showFilter"> Filter</ps-icon-button> -->
            </template>

            <template #tableActionRow="rowProps">
                <div class="flex flex-row mb-2" v-if="rowProps.field == 'action'">
                    <ps-button :disabled="rowProps.row.authorizations.update ? false : true" @click="handleEdit(rowProps.row.id)" class="me-2" rounded="rounded-lg" colors="bg-green-400 text-white"
                               padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-green-100"
                               focus="focus:outline-none focus:ring focus:ring-green-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                    </ps-button>
                    <ps-button :disabled="rowProps.row.authorizations.delete ? false : true" @click="confirmDeleteClicked(rowProps.row.id)" rounded="rounded-lg" colors="bg-red-400 text-white"
                               padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-red-100"
                               focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>
            </template>
            <template #tableRow="rowProps">

                <ps-label v-if="rowProps.field == 'status' && rowProps.row.status == 0">
                    <ps-label class="mb-2 whitespace-nowrap dark:text-white">
                        <ps-label class="flex flex-row" textColor="text-yellow-500">
                            <ps-label class="w-2 h-2 my-auto rounded-full me-2" textColor="bg-yellow-500"></ps-label> {{$t('core__be_waiting_for_approval_status')}}
                        </ps-label>
                    </ps-label>
                </ps-label>

                <ps-label v-if="rowProps.field == 'status' && rowProps.row.status == 1">
                    <ps-label class="mb-2 whitespace-nowrap dark:text-white">
                        <ps-label class="flex flex-row" textColor="text-green-600">
                            <ps-label class="w-2 h-2 my-auto rounded-full me-2" textColor="bg-green-600"> </ps-label> {{$t('core__be_approved_status')}}
                        </ps-label>
                    </ps-label>
                </ps-label>

                <ps-label v-if="rowProps.field == 'status' && rowProps.row.status == 2">
                    <ps-label class="mb-2 whitespace-nowrap dark:text-white">
                        <ps-label class="flex flex-row" textColor="text-red-500">
                            <ps-label class="w-2 h-2 my-auto rounded-full me-2" textColor="bg-red-500"> </ps-label>  {{$t('core__be_rejected_status')}}
                        </ps-label>
                    </ps-label>
                </ps-label>
            </template>

        </ps-table2>
        <!-- data table end -->
    </ps-layout>
</template>

<script>
import { defineComponent, ref, reactive } from 'vue'
import { Link, Head, useForm } from '@inertiajs/vue3';
import PsLayout from "@/Components/PsLayout.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsDataTable from "@/Components/Core/Table/PsDataTable.vue";
import PsAlert from "@/Components/Core/Alert/PsAlert.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";
import Dropdown from "@/Components/Core/DropdownModal/Dropdown.vue";
import PsIconButton from "@/Components/Core/Buttons/PsIconButton.vue";
import PsTextButton from "@/Components/Core/Buttons/PsTextButton.vue";
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";
import { trans } from 'laravel-vue-i18n';
import { router } from '@inertiajs/vue3';
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsInputWithRightIcon from '@/Components/Core/Input/PsInputWithRightIcon.vue';

export default defineComponent({
    name: "Index",
    components: {
        PsDropdownSelect,
        PsDropdown,
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
        PsInputWithRightIcon
    },
    layout : PsLayout,
    props: {
        can: Object,
        status: Object,
        transactions: Object,
        packages: Object,
        selectedStatus: { type: String, default: '' },
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
    },
    setup(props) {
        const showFilter = ref(false);
        const clearFilter = ref(false);

        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');

        // For data table
        const globalSearchFields = ["package.title", 'user.name'];
        const globalSearchPlaceholder = "Search";
        const ps_danger_dialog = ref();
        let selected_status = props.selectedStatus ? ref(props.selectedStatus) : ref('');

        let types = [
            {
                id: '1',
                name: trans('core__be_approved_status'),
            },
            {
                id: '2',
                name: trans('core__be_rejected_status'),
            }
        ];

        // const searchOptions = [
        //     {
        //         placeholder: "Package",
        //         options: props.packages,
        //         displayField: "title",
        //         checkFieldMain: "package_id",
        //         checkFieldOption: "id",
        //         filterType: "dropdown"
        //     },
        //     {
        //         placeholder: "Payment Status",
        //         options: types,
        //         displayField: "name",
        //         checkFieldMain: "status",
        //         checkFieldOption: "id",
        //         filterType: "dropdown"
        //     }
        // ];

        const columns = [
            {
                label: trans('action_label'),
                field: "action",
                type: 'Action'
            },
            {
                label: trans('core__be_package_name_label'),
                field: "value",
                type: 'String',
                action: 'Action'
            },
            {
                label: trans('core__be_bought_user_label'),
                field: "user_name",
                type: 'String',
                action: 'Action'
            },
            {
                label: trans('core__be_price_label'),
                field: "price",
                type: 'Integer',
                action: 'Action'
            },
            {
                label: trans('core__be_post_count_label'),
                field: "post_count",
                type: 'Integer',
                action: 'Action'
            },
            {
                label: trans('core__be_payment_status_label'),
                field: "status",
                type: 'String',
                action: 'Action'
            },
        ]

        function confirmDeleteClicked(id) {
            ps_danger_dialog.value.openModal(
                trans('core__delete'),
                trans('delete_offline_package'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    this.$inertia.delete(route("offline_package.destroy", id));
                },
                () => { }
            );
        }

        function handleStatusfilter(value) {
            selected_status.value = value
            let page = 1;
            handleSearchingSorting(page);
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
        //  function handleClearFilter() {
        //     selected_package.value = 'all';
        //     // selected_sub_cat.value = 'all';
        //     // selected_city.value = 'all';
        //     // selected_township.value = 'all';
        //     handleSearchingSorting();
        // }
        // function dateFilter(value) {
        //     selected_date.value = value
        //     let page = 1;
        //     handleSearchingSorting(page);
        // }
        // function handlePackagefilter(value) {
        //     selected_package.value = value
        //     let page = 1;
        //     handleSearchingSorting(page);
        // }
        // function handlePaymentfilter(value) {
        //     selected_payment_method.value = value
        //     let page = 1;
        //     handleSearchingSorting(page);
        // }

        function handleSearchingSorting(page = null, row = null) {
            router.get(route('offline_package.index'),
                {
                    sort_field: sort_field.value,
                    sort_order: sort_order.value,
                    page: page ?? props.transactions.meta.current_page,
                    row: row ?? props.transactions.meta.per_page,
                    search: search.value,
                    status_filter: parseInt(selected_status.value),
                    // date_filter:selected_date.value,
                    // selected_payment_method:selected_payment_method.value,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }


        return {
            handleSorting,
            handleSearching,
            handleRow,
            selected_status,handleStatusfilter,types,showFilter, clearFilter, globalSearchFields,  globalSearchPlaceholder, columns, ps_danger_dialog, confirmDeleteClicked }
    },
    computed: {
        breadcrumb() {

            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('offline_package_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },

    methods: {
        handleCreate() {
            this.$inertia.get(route("offline_package.create"));
        },
        handleEdit(id){
            this.$inertia.get(route('offline_package.edit',id));
        },
        handlePublish(id) {
            //this.$inertia.put(route('blog.statusChange', id));
        },
    },
})
</script>
