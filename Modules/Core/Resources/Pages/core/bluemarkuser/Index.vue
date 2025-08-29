<template>

    <Head :title="$t('bluemark_module')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <!-- alert banner start -->
        <ps-banner-icon v-if="visible" :visible="visible"
            :theme="(status.flag)=='danger'?'bg-red-500':(status.flag)=='warning'?'bg-yellow-500':'bg-green-500'"
            :iconName="(status.flag)=='danger'?'close-circle':(status.flag)=='warning'?'alert-triangle':'rightalert'"
            class="text-white mb-5 sm:mb-6 lg:mb-8" iconColor="white">{{status.msg}}</ps-banner-icon>
        <!-- alert banner end -->

        <!-- data table start -->
        <ps-table2 :row="row" :search="search" :object="this.users" :colFilterOptions="colFilterOptions"
            :columns="columns" :sort_field="sort_field" :sort_order="sort_order"
            @FilterOptionshandle="FilterOptionshandle" @handleSort="handleSorting" @handleSearch="handleSearching"
            @handleRow="handleRow" :searchable="showFilter" :globalSearchPlaceholder="$t('core__be_search')"
            :eye_filter="false">

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
                            :selectedValue="(selected_status == '' || selected_status == 'all') ? '' : verifyBlueMarkList.filter(option => option.id == selected_status)[0].name" />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleStatusfilter('all')">
                                    <ps-label class="text-gray-200 ms-2">{{$t('core__be_select_all')}}</ps-label>
                                </div>
                                <div v-for="status in verifyBlueMarkList" :key="status.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleStatusfilter(status.id)">
                                    <ps-label class="ms-2" :class="status.id == selected_status ? ' font-bold' : ''">
                                        {{ status.name }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>
                </ps-dropdown>

                <!-- <date-picker v-if="reRenderDate" class="rounded shadow-sm pt-0.5 border border-secondary-200 dark:border-secondary-200 focus:outline-none focus:ring-1 focus:ring-primary-500 60"  v-model:value="selected_date"  @datepick="handleDatefilter" :range="false" :inline="false" :autoApply="false"/> -->
                <date-picker @datepick="handleDatefilter" class="rounded shadow-sm pt-0.5 focus:outline-none focus:ring-1 focus:ring-primary-500" :class="(selected_date == null || selected_date == '') ? 'w-full' :'w-full'" v-model:value="selected_date" :range="true" :inline="false" :autoApply="false"/>
            </template>

            <template #tableActionRow="rowProps">
                <div class="flex flex-row" v-if="rowProps.field == 'action'">
                    <ps-button :disabled="rowProps.row.authorizations.update ? false : true"  @click="handleEdit(rowProps.row.id)" class="me-2" rounded="rounded-lg" colors="bg-green-400 text-white"
                        padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-green-100"
                        focus="focus:outline-none focus:ring focus:ring-green-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                    </ps-button>
                    <ps-button :disabled="rowProps.row.authorizations.delete ? false : true"  @click="confirmDeleteClicked(rowProps.row.id)" rounded="rounded-lg" colors="bg-red-400 text-white"
                        padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>
            </template>
            <template #tableRow="rowProps">
                 <div class="flex flex-row" v-if="rowProps.field == usrIsVerifyBlueMark">
                    <ps-label v-if="rowProps.row[usrIsVerifyBlueMark] == 1" textColor="text-green-500" class="flex flex-row"> <ps-label class="w-2 h-2 my-auto rounded-full me-2" textColor="bg-green-500"></ps-label>{{ $t('bluemarkuser__be_applied_label') }} </ps-label>
                    <ps-label v-if="rowProps.row[usrIsVerifyBlueMark] == 2" textColor="text-yellow-500" class="flex flex-row"> <ps-label class="w-2 h-2 my-auto rounded-full me-2" textColor="bg-yellow-500"></ps-label>{{ $t('bluemarkuser__be_pending_label') }} </ps-label>
                    <ps-label v-if="rowProps.row[usrIsVerifyBlueMark] == 3" textColor="text-red-500" class="flex flex-row"> <ps-label class="w-2 h-2 my-auto rounded-full me-2" textColor="bg-red-500"></ps-label>{{ $t('bluemarkuser__be_rejected_label') }} </ps-label>
                </div>
            </template>
        </ps-table2>
        <!-- data table end -->
    </ps-layout>
</template>

<script>
import { ref, defineComponent } from "vue";
import PsLayout from "@/Components/PsLayout.vue";
import { Head } from "@inertiajs/vue3";
import { router } from '@inertiajs/vue3';
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextButton from "@/Components/Core/Buttons/PsTextButton.vue";
import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import Dropdown from "@/Components/Core/DropdownModal/Dropdown.vue";
import PsIconButton from "@/Components/Core/Buttons/PsIconButton.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsLink1 from '@/Components/Core/Link/PsLink1.vue';
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";
import PsRating from "@/Components/Core/Rating/PsRating.vue";
import DatePicker from "@/Components/Core/DateTime/DatePicker.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Index",
    components: {
        Head,
        PsButton,
        PsTextButton,
        PsBannerIcon,
        PsBreadcrumb2,
        PsDangerDialog,
        PsIcon,
        PsDropdown,
        PsDropdownSelect,
        Dropdown,
        PsIconButton,
        PsLabel,
        PsLink1,
        PsToggle,
        PsTable2,
        PsRating,
        DatePicker
    },
    layout: PsLayout,
    props: {
        verifyBlueMarkList: Object,
        status: Object,
        users: Object,
        roles: Object,
        customizeHeaders: Object,
        customizeDetails: Object,
        hideShowFieldForFilterArr: Object,
        showCoreAndCustomFieldArr: Object,
        selectedStatus: { type: String, default: '' },
        selectedDate:Object,
        authUser: Object,
        uis: Object,
        sort_field: {
            type: String,
            default: "",
        },
        sort_order: {
            type: String,
            default: 'desc',
        },
        search: String,
        usrBlueMarkNote: String,
        usrIsVerifyBlueMark: String
    },
    setup(props) {
        // For data table
        const showFilter = ref(false);
        const clearFilter = ref(false);

        let visible = ref(false)

        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
        let selected_status = props.selectedStatus ? ref(props.selectedStatus) : ref('');
        let selected_date = props.seleDateRole ? ref(props.selectedDate) : ref('');

        const reRenderDate = ref(true);

        const colFilterOptions = ref();

        const columns = [
            {
                label: trans('user_name_label'),
                field: "name",
                type: "String"
            },
            {
                label: trans('core__be_user_email'),
                field: "email",
                type: 'String',
                sort: false
            },
            {
                label: trans('core__be_user_phone'),
                field: "user_phone",
                type: "String",
                sort: false
            },
            {
                label: trans('core__be_blue_mark_status'),
                field: props.usrIsVerifyBlueMark,
                type: "String",
            },
            {
                label: trans('core__be_blue_mark_note'),
                field: props.usrBlueMarkNote,
                type: "String",
                sort: false
            },
            {
                label: trans('core__be_applied_date'),
                field: "bluemark_updated_at",
                type: 'Timestamp',
                sort: false
            },
            {
                label: trans('core__be_action_label'),
                field: "action",
                type: 'Action'
            },
        ]

        function handleSorting(value) {
            sort_field.value = value.field
            sort_order.value = value.sort_order
            handleSearchingSorting()
        }

        function handleStatusfilter(value) {
            selected_status.value = value
            let page = props.users.meta.current_page;
            handleSearchingSorting(page);
        }

        function handleDatefilter(value) {
            selected_date.value = value
            let page = props.users.meta.current_page;
            handleSearchingSorting(page);
        }

        function handleClearFilter(){
            selected_date.value = '';
            selected_status.value = '';
            let page = 1;
            handleSearchingSorting(page);

            reRenderDate.value= false;
            setTimeout(() => {
                reRenderDate.value = true;
            }, 500);
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
            router.get(route('bluemarkuser.index'),
                {
                    sort_field: sort_field.value,
                    sort_order: sort_order.value,
                    page: page ?? props.users.meta.current_page,
                    row: row ?? props.users.meta.per_page,
                    search: search.value,
                    status_filter: selected_status.value,
                    date_filter: selected_date.value,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }

        function confirmDeleteClicked(id) {
            ps_danger_dialog.value.openModal(
                trans('core__be_delete_bluemarkuser'),
                trans('core__be_delete_bluemarkuser_info'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    router.delete(route("bluemarkuser.destroy", id), {
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

        const ps_danger_dialog = ref();
        return{
            reRenderDate,
            handleClearFilter,
            showFilter,
            clearFilter,
            columns,
            colFilterOptions,
            visible,
            handleSorting,
            handleSearchingSorting,
            handleStatusfilter,
            handleDatefilter,
            handleRow,
            handleSearching,
            selected_status,
            selected_date,
            confirmDeleteClicked,
            ps_danger_dialog
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
                    label: trans('bluemark_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleEdit(id) {
            this.$inertia.get(route('bluemarkuser.edit', id));
        },
        FilterOptionshandle(value) {
            router.post(route('bluemarkuser.screenDisplayUiSetting.store'),
                {
                    value,
                    sort_field: this.sort_field,
                    sort_order: this.sort_order,
                    row: this.users.per_page,
                    search: this.search.value,
                    current_page: this.users.current_page
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                });
        },
    },
    created(){
        // this.columns = this.showCoreAndCustomFieldArr.map(column => {
        //     return {
        //         action: column.action,
        //         field: column.field,
        //         label: trans(column.label),
                // type: column.type
        //     };
        // });

        // this.colFilterOptions = this.hideShowFieldForFilterArr.map(columnFilterOption => {
        //     return {
        //         hidden: columnFilterOption.hidden,
        //         id: columnFilterOption.id,
        //         key: trans(columnFilterOption.key),
        //         key_id: columnFilterOption.key_id,
        //         label: trans(columnFilterOption.label),
        //         module_name: columnFilterOption.module_name
        //     };
        // });
    },
})
</script>
