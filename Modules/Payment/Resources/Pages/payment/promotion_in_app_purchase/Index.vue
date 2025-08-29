<template>

    <Head :title="$t('promotion_in_app_purchase_module')" />
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
        <ps-table2 :row="row" :search="search" :object="this.inAppPurchases" :colFilterOptions="colFilterOptions"
            :columns="columns" :sort_field="sort_field" :sort_order="sort_order"
            @FilterOptionshandle="FilterOptionshandle" @handleSort="handleSorting" @handleSearch="handleSearching"
            @handleRow="handleRow" :searchable="showFilter"
            :globalSearchPlaceholder="$t('payment__be_search_iap_prd_id')"
            :eye_filter="false">

            <template #button>
                    <ps-button @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-1 font-semibold" /> {{ $t('core__be_add_promotion_in_app_purchase') }}</ps-button>
            </template>
             <template #responsive_button>
                    <ps-button @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-1 font-semibold" /> {{ $t('core__be_add_promotion_in_app_purchase') }}</ps-button>
            </template>

            <template #searchRight>
                <!-- type filter -->
                <ps-dropdown align="" class="h-10">
                    <template #select>
                        <ps-dropdown-select
                            class="w-56"
                            :placeholder="$t('payment__be_type')"
                            :selectedValue="(selected_type == '' || selected_type == 'all') ? '' : types.filter(option => option.name == selected_type)[0].name" />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleTypefilter('all')">
                                    <ps-label class="text-gray-200 ms-2">{{$t('core__be_select_all')}}</ps-label>
                                </div>
                                <div v-for="type in types" :key="type.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleTypefilter(type.name)">
                                    <ps-label class="ms-2" :class="type.id == selected_type ? ' font-bold' : ''">
                                        {{ type.name }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>
                </ps-dropdown>
            </template>

            <template #tableActionRow="rowProps">
                <div class="flex flex-row" v-if="rowProps.field == 'action'">
                    <ps-button v-if="rowProps.row.authorizations.update" @click="handleEdit(rowProps.row.id)" class="me-2" rounded="rounded-lg" colors="bg-green-400 text-white"
                        padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-green-100"
                        focus="focus:outline-none focus:ring focus:ring-green-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                    </ps-button>
                    <ps-button v-if="rowProps.row.authorizations.delete" @click="confirmDeleteClicked(rowProps.row.id)" rounded="rounded-lg" colors="bg-red-400 text-white"
                        padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>
            </template>

            <template #tableRow="rowProps">
                <ps-toggle v-if="rowProps.field == 'status'" :selectedValue="rowProps.row.status == 1 ? true : false"
                    @click="handlePublish(rowProps.row.core_keys_id)"></ps-toggle>

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
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";

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
        PsDropdown,
        PsDropdownSelect
    },
    layout : PsLayout,
    props: {
        can: Object,
        status: Object,
        types: Object,
        currencies: Object,
        inAppPurchases: Object,
        hideShowFieldForFilterArr: Object,
        showCoreAndCustomFieldArr: Object,
        authUser: Object,
        dayKey: String,
        typeKey: String,
        statusKey: String,
        selected_type:Object,
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

        const colFilterOptions = ref();
        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
        let selected_type = props.selected_type ? ref(props.selected_type) : ref('');

        function handleSorting(value) {
            sort_field.value = value.field
            sort_order.value = value.sort_order
            handleSearchingSorting()
        }

        function handleTypefilter(value) {
            selected_type.value = value
            let page = 1;
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
            router.get(route('promotion_in_app_purchase.index'),
                {
                    sort_field: sort_field.value,
                    sort_order: sort_order.value,
                    page: page ?? props.inAppPurchases.meta.current_page,
                    row: row ?? props.inAppPurchases.meta.per_page,
                    search: search.value,
                    type_filter: selected_type.value,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }

        const columns = [
            {
                label: trans('action_label'),
                field: "action",
                type: 'Action'
            },
            {
                label: trans('payment__iap_prd_id'),
                field: "in_app_purchase_prd_id",
                type: "String",
                action: 'Action',
                sort:false
            },
            {
                label: trans('payment__iap_desc'),
                field: "description",
                type: "String",
                action: 'Action',
                 sort:false
            },
            {
                label: trans('payment__iap_day'),
                field: props.dayKey,
                type: 'Integer',
                action: 'Action'
            },
            {
                label: trans('payment__iap_type'),
                field: props.typeKey,
                type: 'String',
                action: 'Action'
            },
            {
                label: trans('payment__iap_status'),
                field: props.statusKey,
                type: "String",
                action: 'Action'
            },
        ]

        function confirmDeleteClicked(id) {
            ps_danger_dialog.value.openModal(
                trans('core__be_delete_promotion_iap'),
                trans('core__be_delete_promotion_iap_info'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    router.delete(route("promotion_in_app_purchase.destroy", id), {
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
            visible,
            handleSorting,
            handleSearchingSorting,
            handleRow,
            handleSearching,
            handleTypefilter,
            selected_type,
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
                    label: trans('promotion_in_app_purchase_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },

    methods: {
        handleCreate() {
            this.$inertia.get(route("promotion_in_app_purchase.create"));
        },
        handleEdit(id){
            this.$inertia.get(route('promotion_in_app_purchase.edit',id));
        },
        handlePublish(id){
            this.$inertia.put(route('promotion_in_app_purchase.statusChange',id));
        },
        FilterOptionshandle(value) {
            router.put(route('promotion_in_app_purchase.screenDisplayUiSetting.store'),
                {
                    value,
                    sort_field: this.sort_field,
                    sort_order: this.sort_order,
                    row: this.inAppPurchases.per_page,
                    search: this.search.value,
                    current_page: this.inAppPurchases.current_page
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                });
        },
    },
})
</script>
