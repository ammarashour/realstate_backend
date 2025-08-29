<template>
    <Head :title="$t('menu_group_module')" />
    <ps-layout>
        <div class="">
            <!-- breadcrumb start -->
            <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
            <!-- breadcrumb end -->

           <!-- alert banner start -->


       <ps-banner-icon   v-if="visible" :visible="visible"
        :theme="(status.flag)=='danger'?'bg-red-500':(status.flag)=='warning'?'bg-yellow-500':'bg-green-500'"
        :iconName="(status.flag)=='danger'?'close-circle':(status.flag)=='warning'?'alert-triangle':'rightalert'"
        class="text-white mb-5 sm:mb-6 lg:mb-8"
         iconColor="white">{{status.msg}}</ps-banner-icon>
            <!-- alert banner end -->

            <ps-table2 :row="row" :search="search" :object="menu_groups" :colFilterOptions="colFilterOptions"
                :columns="columns" :sort_field="sort_field" :sort_order="sort_order"
                @FilterOptionshandle="FilterOptionshandle" @handleSort="handleSorting" @handleSearch="handleSearching"
                @handleRow="handleRow" :searchable="showFilter">
                <template #button>
                   <ps-button v-if="can.createCoreMenu" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-1 font-semibold" />{{$t('core_add_menu_group_module')}}</ps-button>
                </template>
                <template #responsive_button>
                    <ps-button v-if="can.createCoreMenu" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-1 font-semibold" />{{$t('core_add_menu_group_module')}}</ps-button>
                </template>
                 <template #tableActionRow="rowProps">
                    <!-- For action (edit/delete) start -->
                    <span v-if="rowProps.field == 'action'">
                        <div class="flex">
                            <ps-button :disabled="!rowProps.row.authorizations.update" @click="handleEdit(rowProps.row.id)" class="me-2" colors="bg-green-400 text-white"
                                padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-green-100"
                                focus="focus:outline-none focus:ring focus:ring-green-200">
                                <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                            </ps-button>
                            <ps-button :disabled="!rowProps.row.authorizations.delete" @click="confirmClicked(rowProps.row.id)" colors="bg-red-400 text-white" padding="p-1.5"
                                hover="hover:outline-none hover:ring hover:ring-red-100"
                                focus="focus:outline-none focus:ring focus:ring-red-200">
                                <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                            </ps-button>
                        </div>
                    </span>
                    <!-- For action (edit/delete) end -->
                </template>
                <template #tableRow="rowProps">
                    <span v-if="rowProps.field == 'is_show_on_menu'">
                        <ps-toggle :disabled="!rowProps.row.authorizations.update" :selectedValue="rowProps.row.is_show_on_menu == 1 ? true : false" @click="handlePublish(rowProps.row.id,rowProps.row.authorizations.update)">
                        </ps-toggle>
                    </span>
                </template>

            </ps-table2>
            <!-- data table end -->
             <ps-danger-dialog ref="ps_danger_dialog" />
        </div>
    </ps-layout>
</template>

<script>
import { defineComponent,ref } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head } from '@inertiajs/vue3';
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsAlert from "@/Components/Core/Alert/PsAlert.vue";
import NewPsDataTable from "@/Components/Core/Table/NewPsDataTable.vue";
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import { trans } from 'laravel-vue-i18n';
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";
import { router } from '@inertiajs/vue3';
import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";

export default defineComponent({
    name: "Index",
    components: {
        Head,
        PsInput,
        PsLabel,
        PsButton,
        PsTextarea,
        PsLabelHeader4,
        PsIcon,
        PsAlert,
        NewPsDataTable,
        PsDangerDialog,
        PsToggle,
        PsBreadcrumb2,
        PsTable2,
        PsBannerIcon
    },
    layout : PsLayout,
    // props: ['menu_groups', 'status', 'checkPermission', 'showMenuGroupCols', 'showCoreAndCustomFieldArr', 'hideShowFieldForFilterArr'],
    props: {
        status: Object,
        language:Object,
        menu_groups:Object,
        checkPermission:Object,
        showMenuGroupCols:Object,
        showCoreAndCustomFieldArr:Object,
        hideShowFieldForFilterArr:Object,
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
        const globalSearchFields = ["group_name"];
        const globalSearchPlaceholder = "Search";
        const ps_danger_dialog = ref();

        const colFilterOptions = ref();
        const columns = ref();
        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
        let visible = ref(false)

        function confirmClicked(id) {

            ps_danger_dialog.value.openModal(
                trans('core__be_delete_menugroup'),
                trans('core__be_delete_menugroup_info'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                        this.$inertia.delete(route('menu_group.destroy',id),{
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
                },
                () => { }
            );
        }
        function handleEdit(id){
            this.$inertia.get(route('menu_group.edit',id));
        }
        function handlePublish(id,hasPermission) {
            if(hasPermission){
                this.$inertia.put(route('menu_group.statusChange', id),{}, {
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

        }
        function handleCreate() {
            this.$inertia.get(route("menu_group.create"));
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

        function handleSearchingSorting(page = null, row = null) {
            router.get(route('menu_group.index'),
                {
                    sort_field: sort_field.value,
                    sort_order: sort_order.value,
                    page: page ?? props.menu_groups.meta.current_page,
                    row: row ?? props.menu_groups.meta.per_page,
                    search: search.value,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }
        return {
            handleEdit,
            ps_danger_dialog,
            confirmClicked,
            handleEdit,
            globalSearchFields,
            globalSearchPlaceholder,
            columns,
            handlePublish,
            handleCreate,
            colFilterOptions,
            handleSorting,
            handleSearching,
            handleRow,
            visible
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
                    label: trans('menu_group_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        FilterOptionshandle(value) {

            router.post(route('menu_group.screenDisplayUiSetting.store'),
                {
                    value,
                    sort_field: this.sort_field,
                    sort_order: this.sort_order,
                    row: this.menu_groups.per_page,
                    search: this.search.value,
                    current_page: this.menu_groups.current_page
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
