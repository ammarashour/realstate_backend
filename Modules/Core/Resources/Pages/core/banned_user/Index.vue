<template>
    <Head :title="$t('banned_user_module')" />
    <ps-layout>
        <div class="">
           <!-- breadcrumb start -->
            <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
            <!-- breadcrumb end -->

            <!-- alert banner start -->
            <ps-banner-icon v-if="visible" :visible="visible"
                :theme="(status.flag) == 'danger' ? 'bg-red-500' : (status.flag) == 'warning' ? 'bg-yellow-500' : 'bg-green-500'"
                :iconName="(status.flag) == 'danger' ? 'close-circle' : (status.flag) == 'warning' ? 'alert-triangle' : 'rightalert'"
                :globalSearchPlaceholder="$t('core__be_search_user')"
                class="text-white mb-5 sm:mb-6 lg:mb-8" iconColor="white">{{ status.msg }}</ps-banner-icon>
            <!-- alert banner end -->

            <!-- data table start -->
            <ps-table2 :row="row" :search="search" :object="this.users" :colFilterOptions="colFilterOptions"
                :columns="columns" :sort_field="sort_field" :sort_order="sort_order"
                @FilterOptionshandle="FilterOptionshandle" @handleSort="handleSorting" @handleSearch="handleSearching"
                @handleRow="handleRow" :searchable="showFilter"
                :eye_filter="false">

                <template #tableRow="rowProps">

                    <div class="flex flex-row mb-2" v-if="rowProps.field == 'overall_rating'">
                        <ps-rating :grade="rowProps.row.overall_rating" :hasCounter="true" /> {{rowProps.row.overall_rating}}
                    </div>

                    <div v-if="rowProps.field == 'status'">
                        <ps-toggle :disabled="!rowProps.row.authorizations.update" v-if="rowProps.row.role_id != 1" :selectedValue="rowProps.row.status == 1 ? true : false"
                            @click="handlePublish(rowProps.row.id)" toggleOnTheme="bg-primary-500 border-primary-500 "></ps-toggle>
                        <ps-toggle :disabled="!rowProps.row.authorizations.update" v-if="rowProps.row.role_id == 1" :selectedValue="rowProps.row.status == 1 ? true : false"></ps-toggle>
                    </div>

                    <span  v-if="rowProps.field == 'is_banned'" class="mb-2">
                        <ps-button :disabled="!rowProps.row.authorizations.update" @click="handleBanUser(rowProps.row.id)" border="border border-green-500" rounded="rounded" colors="bg-white text-green-500" padding="px-12 py-1" hover="hover:bg-green-500 hover:text-white" focus="">{{$t('core__be_unban_lbl')}} </ps-button>
                    </span>
                    <div v-if="rowProps.field == 'detail'">
                        <ps-button :disabled="!rowProps.row.authorizations.update" @click="handleDetail(rowProps.row.id)" rounded="rounded-lg" class="me-4" padding="p-1.5"> <ps-icon name="eye-on"  w="16" h="16" /> </ps-button>
                    </div>
                </template>
            </ps-table2>
            <!-- data table end -->
        </div>
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
        status: Object,
        users: Object,
        roles: Object,
        customizeHeaders: Object,
        customizeDetails: Object,
        hideShowFieldForFilterArr: Object,
        showCoreAndCustomFieldArr: Object,
        selectedRole: { type: String, default: '' },
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
    },
    setup(props){
        const showFilter = ref(false);
        const clearFilter = ref(false);

        let visible = ref(false)

        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
        let selected_role = props.selectedRole ? ref(props.selectedRole) : ref('');

        const colFilterOptions = ref();

        const columns = [
            {
                label: trans('core__be_user_name'),
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
                label: trans('core__be_role'),
                field: "role_id@@name",
                type: "String",
                sort: false
            },
            {
                label: trans('core__be_view_label'),
                field: "detail",
                type: 'Action',
                sort: false
            },
            {
                label: trans('core__be_user_ban_lbl'),
                field: "is_banned",
                type: 'Integer',
                sort: false
            },
        ]

        function handleBanUser(id){
            this.$inertia.put(route('banned_user.ban',id), {}, {
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
                });
        }

        function handleSorting(value) {
            sort_field.value = value.field
            sort_order.value = value.sort_order
            handleSearchingSorting()
        }

        function handleClearFilter() {
            selected_role.value = 'all';
            handleSearchingSorting();
        }

        function handleRolefilter(value) {
            selected_role.value = value
            let page = props.users.meta.current_page;
            handleSearchingSorting(page);
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
            router.get(route('banned_user.index'),
                {
                    sort_field: sort_field.value,
                    sort_order: sort_order.value,
                    page: page ?? props.users.meta.current_page,
                    row: row ?? props.users.meta.per_page,
                    search: search.value,
                    role_filter: selected_role.value,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }

        function handleDetail(id){
            this.$inertia.get(route('banned_user.edit',id));
        }
        return{
            handleDetail,
            handleBanUser,
            showFilter,
            clearFilter,
            columns,
            colFilterOptions,
            visible,
            handleSorting,
            handleSearchingSorting,
            handleRolefilter,
            handleClearFilter,
            handleRow,
            handleSearching,
            selected_role,
        }
    },
    methods: {
        handleEdit(id){
            this.$inertia.get(route('banned_user.edit',id));
        },
        handlePublish(id) {
            this.$inertia.put(route('banned_user.statusChange', id));
        },
        FilterOptionshandle(value) {
            router.post(route('banned_user.screenDisplayUiSetting.store'),
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
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('banned_user_module'),
                    color: "text-primary-500"
                }
            ]
        }
    },
})
</script>
