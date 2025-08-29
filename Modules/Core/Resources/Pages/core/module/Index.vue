<template>
    <Head :title="$t('module_registering_module')" />
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

            <!-- data table start -->
             <ps-table2 :row="row" :search="search" :object="modules" :colFilterOptions="colFilterOptions"
                :columns="columns" :sort_field="sort_field" :sort_order="sort_order"
                @FilterOptionshandle="FilterOptionshandle" @handleSort="handleSorting" @handleSearch="handleSearching"
                @handleRow="handleRow" :searchable="showFilter">
                    <template #button>
                       <ps-button v-if="can.createModule" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-1 font-semibold" /> {{$t('core_add_module')}}</ps-button>
                    </template>
                    <template #responsive_button>
                         <ps-button v-if="can.createModule" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-1 font-semibold" /> {{$t('core_add_module')}}</ps-button>
                    </template>
                    <template #tableActionRow="rowProps">
                    <!-- For action (edit/delete) start -->
                    <div class="flex flex-row" v-if="rowProps.field == 'action'">
                        <ps-button :disabled="!rowProps.row.authorizations.update"  @click="handleEdit(rowProps.row.id)" class="me-2" colors="bg-green-400 text-white" padding="p-1.5"
                            hover="hover:outline-none hover:ring hover:ring-green-100"
                            focus="focus:outline-none focus:ring focus:ring-green-200">
                            <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                        </ps-button>
                        <ps-button :disabled="!rowProps.row.authorizations.delete"  @click="confirmDeleteClicked(rowProps.row.id)" colors="bg-red-400 text-white" padding="p-1.5"
                            hover="hover:outline-none hover:ring hover:ring-red-100"
                            focus="focus:outline-none focus:ring focus:ring-red-200">
                            <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                        </ps-button>
                        <ps-danger-dialog ref="ps_danger_dialog" />
                    </div>
                    <!-- For action (edit/delete) end -->
                </template>
                 <template #tableRow="rowProps">
                     <ps-toggle :disabled="!rowProps.row.authorizations.update" v-if="rowProps.field == 'status'" :selectedValue="rowProps.row.status == 1 ? true : false"
                                @click="handlePublish(rowProps.row.id,rowProps.row.authorizations.update)"></ps-toggle>

                 </template>
            </ps-table2>
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
    // props: ['modules', 'status','sub_menu_groups', 'checkPermission', 'showModuleCols', 'showCoreAndCustomFieldArr', 'hideShowFieldForFilterArr'],
    props: {
        status: Object,
        modules:Object,
        checkPermission:Object,
        showSubMenuGroupCols:Object,
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
        const globalSearchFields = ["title", "route_name"];
        const globalSearchPlaceholder = "Search Module";
        const ps_danger_dialog = ref();

        const colFilterOptions = ref();
        const columns = ref();
        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
        let visible = ref(false)


        function confirmDeleteClicked(id) {
            ps_danger_dialog.value.openModal(
                trans('core_delete_module'),
                trans('delete_module'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    this.$inertia.delete(route('module.destroy',id),{
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
            this.$inertia.get(route('module.edit',id));
        }
        function handleCreate() {
            this.$inertia.get(route("module.create"));
        }
        function handlePublish(id,hasPermission){
            if(hasPermission){
                this.$inertia.put(route('module.statusChange',id));
            }
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
            router.get(route('module.index'),
                {
                    sort_field: sort_field.value,
                    sort_order: sort_order.value,
                    page: page ?? props.modules.meta.current_page,
                    row: row ?? props.modules.meta.per_page,
                    search: search.value,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }

        return {
            handleEdit,
            handleCreate,
            globalSearchFields,
            globalSearchPlaceholder,
            ps_danger_dialog,
            columns,
            confirmDeleteClicked,
            route,
            colFilterOptions,
            handlePublish,
            handleSorting,
            handleSearching,
            handleRow,
            visible,
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
                    label: trans('module_registering_module'),
                    color: "text-primary-500"
                }
            ]
        }
    },
      methods: {
        FilterOptionshandle(value) {

            router.post(route('module.screenDisplayUiSetting.store'),
                {
                    value,
                    sort_field: this.sort_field,
                    sort_order: this.sort_order,
                    row: this.modules.per_page,
                    search: this.search.value,
                    current_page: this.modules.current_page
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
