<template>
    <ps-layout :can="can">
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <!-- alert banner start -->
        <ps-banner-icon v-if="visible" :visible="visible"
            :theme="(status.flag) == 'danger' ? 'bg-red-500' : (status.flag) == 'warning' ? 'bg-yellow-500' : 'bg-green-500'"
            :iconName="(status.flag) == 'danger' ? 'close-circle' : (status.flag) == 'warning' ? 'alert-triangle' : 'rightalert'"
            class="text-white mb-5 sm:mb-6 lg:mb-8" iconColor="white">{{ status.msg }}</ps-banner-icon>
        <!-- alert banner end -->

        <!-- data table start -->
        <ps-table2 :row="row" :search="search" :object="this.roles" :colFilterOptions="colFilterOptions"
            :columns="columns" :sort_field="sort_field" :sort_order="sort_order"
            :globalSearchPlaceholder="$t('core__be_search')"
            @FilterOptionshandle="FilterOptionshandle" @handleSort="handleSorting" @handleSearch="handleSearching"
            @handleRow="handleRow" :searchable="showFilter">

            <template #button>
                    <ps-button v-if="can.createRole" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-1 font-semibold" /> {{ $t('core__be_add_user_role') }}</ps-button>
            </template>
             <template #responsive_button>
                    <ps-button v-if="can.createRole" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-1 font-semibold" /> {{ $t('core__be_add_user_role') }}</ps-button>
            </template>

            <template #tableActionRow="rowProps">

                <div class="flex flex-row" v-if="rowProps.field == 'action'">
                    <ps-button :disabled="!rowProps.row.authorizations.update" v-if="rowProps.row.id != 1" @click="handleEdit(rowProps.row.id)" class="me-2" rounded="rounded-lg" colors="bg-green-400 text-white"
                        padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-green-100"
                        focus="focus:outline-none focus:ring focus:ring-green-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                    </ps-button>
                    <ps-button :disabled="!rowProps.row.authorizations.delete" v-if="rowProps.row.id != 1 && rowProps.row.id != 2" @click="confirmDeleteClicked(rowProps.row.id)" rounded="rounded-lg" colors="bg-red-400 text-white"
                        padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>

                    <ps-button :disabled="authUser.id != 1" v-if="rowProps.row.id == 1" @click="handleEdit(rowProps.row.id)" class="me-2" rounded="rounded-lg" colors="bg-green-400 text-white"
                        padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-green-100"
                        focus="focus:outline-none focus:ring focus:ring-green-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                    </ps-button>
                    <ps-button :disabled="true" v-if="rowProps.row.id == 1 || rowProps.row.id == 2" @click="confirmDeleteClicked(rowProps.row.id)" rounded="rounded-lg" colors="bg-red-400 text-white"
                        padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>
            </template>

            <template #tableRow="rowProps">
                <div v-if="rowProps.field == 'status'">
                    <ps-toggle :disabled="!rowProps.row.authorizations.update" v-if="rowProps.row.id != 1 && rowProps.row.id != 2" :selectedValue="rowProps.row.status == 1 ? true : false"
                        @click="handlePublish(rowProps.row.id,rowProps.row.authorizations.update)" toggleOnTheme="bg-primary-500 border-primary-500 "></ps-toggle>

                    <ps-toggle v-if="rowProps.row.id == 1 || rowProps.row.id == 2" :disabled="true" :selectedValue="rowProps.row.status == 1 ? true : false"></ps-toggle>
                </div>
                <div v-if="rowProps.field == 'can_access_admin_panel'">
                    <ps-toggle :disabled="!rowProps.row.authorizations.update" :selectedValue="rowProps.row.can_access_admin_panel == 1 ? true : false"
                        @click="handleAdminPanelAccess(rowProps.row.id,rowProps.row.authorizations.update)" toggleOnTheme="bg-primary-500 border-primary-500 "></ps-toggle>
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
        can: Object,
        status: Object,
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
    setup(props) {
        // For data table
        const showFilter = ref(false);
        const clearFilter = ref(false);

        let visible = ref(false)
        const roleIDArr = [1,2];

        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');

        const colFilterOptions = ref();
        let columns = ref();

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

        function handleSearchingSorting(page = null, row = null) {
            router.get(route('user_role.index'),
                {
                    sort_field: sort_field.value,
                    sort_order: sort_order.value,
                    page: page ?? props.roles.meta.current_page,
                    row: row ?? props.roles.meta.per_page,
                    search: search.value,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }

        const ps_danger_dialog = ref();

        function confirmDeleteClicked(id) {
            ps_danger_dialog.value.openModal(
                trans('core__be_delete_role'),
                trans('delete_role'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    router.delete(route("user_role.destroy", id), {
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

        function handleEdit(id){
            this.$inertia.get(route('user_role.edit',id));
        }
        function handleCreate() {
            this.$inertia.get(route("user_role.create"));
        }
        function handlePublish(id,hasPermission) {
            if(hasPermission){
                this.$inertia.put(route('user_role.statusChange', id));
            }

        }

        function handleAdminPanelAccess(id,hasPermission) {
            if(hasPermission){
                this.$inertia.put(route('user_role.updateAdminPanelAccess', id));
            }

        }

        return {
            showFilter,
            handleAdminPanelAccess,
            clearFilter,
            columns,
            colFilterOptions,
            visible,
            handleSorting,
            handleSearchingSorting,
            handleRow,
            handleSearching,
            handleEdit,
            handleCreate,
            ps_danger_dialog,
            confirmDeleteClicked,
            handlePublish,
            roleIDArr
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
                    label: trans('user_role_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        FilterOptionshandle(value) {
            router.post(route('user_role.screenDisplayUiSetting.store'),
                {
                    value,
                    sort_field: this.sort_field,
                    sort_order: this.sort_order,
                    row: this.roles.per_page,
                    search: this.search.value,
                    current_page: this.roles.current_page
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                });
        },
    },
    created() {
        this.columns = this.showCoreAndCustomFieldArr.map(column => {
        return {
            action: column.action,
            field: column.field,
            label: trans(column.label),
            sort: column.sort,
            type: column.type
        };
        });

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
