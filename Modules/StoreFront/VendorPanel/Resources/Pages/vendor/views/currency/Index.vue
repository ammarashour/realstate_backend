<template>

    <Head :title="$t('core__vendor_currency_module')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <!-- alert banner start -->
        <ps-banner-icon  v-if="visible" :visible="visible"
        :theme="(status.flag)=='danger'?'bg-red-500':(status.flag)=='warning'?'bg-yellow-500':'bg-green-500'"
        :iconName="(status.flag)=='danger'?'close-circle':(status.flag)=='warning'?'alert-triangle':'rightalert'"
        class="text-white mb-5 sm:mb-6 lg:mb-8"
         iconColor="white">{{status.msg}}</ps-banner-icon>
        <!-- alert banner end -->

        <ps-label class="text-xl font-semibold" >{{ $t('core__vendor_currency_module') }}</ps-label>

        <!-- data table start -->
        <ps-table2 :row="row" :search="search" :object="this.currencies" :columns="columns" :sort_field="sort_field" :sort_order="sort_order" @FilterOptionshandle="FilterOptionshandle"
        @handleSort="handleSorting"  @handleSearch="handleSearching" @handleRow="handleRow"
            :globalSearchPlaceholder="$t('core__be_searches')"
            :searchable="showFilter" :eye_filter="false">

            <!-- add new field start -->
            <!-- <template #button>
                <ps-button v-if="can.createCurrency" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-2 font-semibold" />{{$t('core__add_currency')}}</ps-button>
            </template>
            <template #responsive_button>
                <ps-button v-if="can.createCurrency" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-2 font-semibold" />{{$t('core__add_currency')}}</ps-button>
            </template> -->
            <!-- add new field end -->

             <template #tableActionRow="rowProps">
                <div class="flex flex-row" v-if="rowProps.field == 'action'">
                    <ps-button :disabled="rowProps.row.authorization.update ? false : true" @click="handleEdit(rowProps.row.id)" class="me-2" colors="bg-green-400 text-white" padding="p-1.5"
                        hover="hover:outline-none hover:ring hover:ring-green-100"
                        focus="focus:outline-none focus:ring focus:ring-green-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                    </ps-button>
                    <ps-button  :disabled="rowProps.row.authorization.delete ? false : true" @click="confirmDeleteClicked(rowProps.row.id)" colors="bg-red-400 text-white" padding="p-1.5"
                        hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>
            </template>
            <template #tableRow="rowProps">
                <ps-toggle :disabled="true" v-if="rowProps.field == 'status'" :selectedValue="rowProps.row.status == 1 ? true : false"
                    toggleOnTheme="bg-primary-500 border-primary-500 "></ps-toggle>

                <ps-toggle :disabled="!rowProps.row.vendorAuthorization.update" v-if="rowProps.field == 'is_default'" :selectedValue="rowProps.row.id == defaultCurrencyId ? true : false"
                        @click="handleDefault(rowProps.row.id,rowProps.row.vendorAuthorization.update)" toggleOnTheme="bg-primary-500 border-primary-500 "></ps-toggle>
            </template>

        </ps-table2>
        <ps-warning-dialog ref="ps_warning_dialog" />
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
import Dropdown from "@/Components/Core/DropdownModal/Dropdown.vue";
import PsIconButton from "@/Components/Core/Buttons/PsIconButton.vue";
import PsWarningDialog from "@/Components/Core/Dialog/PsWarningDialog.vue";
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
        Dropdown,
        PsIconButton,
        PsWarningDialog,
    },
    layout: PsVendorLayout,
    // props: ['currencies', 'status', 'checkPermission', 'showCurrencyCols', 'showCoreAndCustomFieldArr', 'hideShowFieldForFilterArr'],
    props:{
        can:Object,
        status:Object,
        currencies:Object,
        defaultCurrencyId:String,
        hideShowFieldForFilterArr:Object,
        showCoreAndCustomFieldArr:Object,
        sort_field:{
                type:String,
                default:"",

            },
        sort_order:{
            type:String,
            default:'desc',
        },
        search:String,
    },
    setup(props) {
    console.log(props.currencies);
        let visible = ref(false)

        let sorting = "";
        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
        // let selected_owner = props.selectedOwner ? ref(props.selectedOwner) : ref('');


        // For data table
        // const globalSearchFields = ["currency_short_form"];
        // const globalSearchPlaceholder = trans('search_currency');
        const ps_danger_dialog = ref();
        const ps_warning_dialog = ref();
        const colFilterOptions = ref();
        const columns = [
            {
                label: trans('core_vendor__currency'),
                field: 'currency_short_form',
                type: 'String',
                action: 'Action'
            },
            {
                label: trans('core_vendor__symbol'),
                field: "currency_symbol",
                type: "String",
                action: 'Action',
            },
            {
                label: trans("core_vendor__publish"),
                field: "status",
                type: "Integer",
                action: "Action",
            },
            {
                label: trans("core_vendor__default"),
                field: "is_default",
                type: "Integer",
                action: "Action",
            },
        ];

        function confirmDeleteClicked(id) {
            ps_danger_dialog.value.openModal(
                trans('core__delete'),
                trans('core__comfirm_to_delete_row_currency'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    this.$inertia.delete(route("currency.destroy", id),{
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
        function handlePublish(id,hasPermission) {
            if(hasPermission){
                this.$inertia.put(route('currency.statusChange', id),{
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
        function handleDefault(id,hasPermission) {
            if(hasPermission){
                ps_warning_dialog.value.openModal(
                    trans('vendor_panel__update_item_price_title'),
                    trans('vendor_panel__update_item_price_desc'),
                    trans('vendor_panel__ok'), null,
                    ()=>{
                        this.$inertia.put(route('vendor_currency.defaultChange', id),{
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
                    ()=>{},
                );
            }

        }

        function handleSorting(value){
            sort_field.value = value.field
            sort_order.value = value.sort_order
            handleSearchingSorting()
        }
        function handleSearching(value){
            search.value = value;
            let page=1;
            handleSearchingSorting(page);
        }
        function handleRow(value){
            handleSearchingSorting(1,value);
        }

        function handleSearchingSorting(page = null,row=null){
            router.get(route('vendor_currency.index'),
            {
                sort_field : sort_field.value,
                sort_order: sort_order.value,
                page:page ?? props.currencies.meta.current_page,
                row:row ?? props.currencies.meta.per_page,
                search:search.value,
            },
            {
                preserveScroll: true,
                preserveState:true,
            })
        }

        return {
            handleRow,
            handleSearchingSorting,
            handleSearching,
            handleSorting,
            handlePublish,
            handleDefault,
            visible,
            // globalSearchFields,
            // globalSearchPlaceholder,
            columns, ps_danger_dialog,
            ps_warning_dialog,
            confirmDeleteClicked,
            colFilterOptions
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
                    label: trans('core__vendor_currency_module'),
                    color: "text-primary-500"
                }
            ]

        },
    },
    methods: {
        handleCreate() {
            this.$inertia.get(route("currency.create"));
        },
        handleEdit(id) {
            this.$inertia.get(route('currency.edit', id));
        },
        FilterOptionshandle(value) {
        router.put(route('currency.screenDisplayUiSetting.store'),
            {
                value,
                sort_field :this.sort_field ,
                sort_order:this.sort_order,
                row:this.currencies.per_page,
                search:this.search.value,
                current_page:this.currencies.current_page
            },
            {
                preserveScroll: true,
                preserveState:true,
            });

        },

    },
    // created() {
    //     this.columns = this.showCoreAndCustomFieldArr.map(column => {
    //         return {
    //             action: column.action,
    //             field: column.field,
    //             label: trans(column.label),
    //             sort: column.sort,
    //             type: column.type
    //         };
    //     });

    //     this.colFilterOptions = this.hideShowFieldForFilterArr.map(columnFilterOption => {
    //         return {
    //             hidden: columnFilterOption.hidden,
    //             id: columnFilterOption.id,
    //             key: trans(columnFilterOption.key),
    //             key_id: columnFilterOption.key_id,
    //             label: trans(columnFilterOption.label),
    //             module_name: columnFilterOption.module_name
    //         };
    //     });
    // },
})
</script>
