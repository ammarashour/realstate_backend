<template>

    <Head :title="$t('core__phone_country_code_module')" />
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

        <!-- data table start -->
        <ps-table2 :row="row" :search="search" :object="this.phoneCountryCodes" :colFilterOptions="colFilterOptions" :columns="columns" :sort_field="sort_field" :sort_order="sort_order" @FilterOptionshandle="FilterOptionshandle"
        @handleSort="handleSorting"  @handleSearch="handleSearching" @handleRow="handleRow"
            :searchable="showFilter">
            <template #button>
                    <ps-button v-if="can.createPhoneCountryCode" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-1 font-semibold" /> {{ $t('core__create_phone_country_code') }}</ps-button>
            </template>
             <template #responsive_button>
                    <ps-button v-if="can.createPhoneCountryCode" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-1 font-semibold" /> {{ $t('core__create_phone_country_code') }}</ps-button>
            </template>
            <template #tableActionRow="rowProps">

                <div class="flex flex-row" v-if="rowProps.field == 'action'">
                    <ps-button :disabled="!rowProps.row.authorizations.update" @click="handleEdit(rowProps.row.id)" class="me-2" colors="bg-green-400 text-white" padding="p-1.5"
                        hover="hover:outline-none hover:ring hover:ring-green-100"
                        focus="focus:outline-none focus:ring focus:ring-green-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                    </ps-button>
                    <ps-button :disabled="!rowProps.row.authorizations.delete" @click="confirmDeleteClicked(rowProps.row)" colors="bg-red-400 text-white" padding="p-1.5"
                        hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                    <ps-error-dialog ref="ps_error_dialog" />
                </div>
            </template>
            <template #tableRow="rowProps">
              <div  v-if="rowProps.field == 'status' && reRenderToogle" v-show="colFilterOptions.filter((item) => item.key == 'status').length == 0
                            ? true : !colFilterOptions.filter((item) => item.key == 'status')[0].hidden ">
                    <ps-toggle  :disabled="!rowProps.row.authorizations.update" v-if="reRenderToogle"  :selectedValue="rowProps.row.status == 1 ? true : false"
                        @click="handlePublish(rowProps.row.id,rowProps.row.authorizations.update)" toggleOnTheme="bg-primary-500 border-primary-500 "></ps-toggle>
                </div>

                 <div  v-if="rowProps.field == 'is_default' && reRenderToogle" v-show="colFilterOptions.filter((item) => item.key == 'is_default').length == 0
                            ? true : !colFilterOptions.filter((item) => item.key == 'is_default')[0].hidden ">

                    <ps-toggle :disabled="!rowProps.row.authorizations.update" v-if="reRenderToogle"  :selectedValue="rowProps.row.is_default == 1 ? true : false"
                        @click="handleDefault(rowProps.row.id,rowProps.row.authorizations.update)" toggleOnTheme="bg-primary-500 border-primary-500 "></ps-toggle>

                </div>
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
import Dropdown from "@/Components/Core/DropdownModal/Dropdown.vue";
import PsIconButton from "@/Components/Core/Buttons/PsIconButton.vue";
import { trans } from 'laravel-vue-i18n';
import { router } from '@inertiajs/vue3'
import PsErrorDialog from "@/Components/Core/Dialog/PsErrorDialog.vue";

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
        PsErrorDialog
    },
    layout: PsLayout,
    //props: ['phoneCountryCodes', 'status', 'checkPermission', 'showAvailableCurrencyCols', 'showCoreAndCustomFieldArr', 'hideShowFieldForFilterArr'],
    props:{
            can:Object,
            status:Object,
            phoneCountryCodes:Object,
            checkPermission:Object,
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
        let visible = ref(false)

        // For data table
        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');

        const ps_danger_dialog = ref();
        const colFilterOptions = ref();
        const ps_error_dialog = ref();
        const columns = ref();
         const reRenderToogle = ref(true);

        function confirmDeleteClicked(phone) {


                ps_danger_dialog.value.openModal(
                trans('core__delete_phone_country_code'),
                trans('core__delete_phone_country_code_info'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {


                    this.$inertia.delete(route("phone_country_code.destroy", phone.id));
                     visible.value = true;
                     setTimeout(() => {
                        visible.value = false;
                    }, 3000);
                },
                () => { }
            );


        }

        function handlePublish(id,hasPermission) {
            if(hasPermission){
                this.$inertia.put(route('phone_country_code.statusChange', id));
                setTimeout(() => {
                    reRenderToogle.value= false;
                        setTimeout(() => {
                        reRenderToogle.value = true;
                    }, 200);
                }, 2000);
            }

        }

        function handleDefault(id,hasPermission) {
            if(hasPermission){
                this.$inertia.put(route('phone_country_code.defaultChange', id),{
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
                    setTimeout(() => {
                    reRenderToogle.value= false;
                        setTimeout(() => {
                        reRenderToogle.value = true;
                    }, 200);
                }, 2000);
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
            router.get(route('phone_country_code.index'),
            {
                sort_field : sort_field.value,
                sort_order: sort_order.value,
                page:page ?? props.phoneCountryCodes.meta.current_page,
                row:row ?? props.phoneCountryCodes.meta.per_page,
                search:search.value,
            },
            {
                preserveScroll: true,
                preserveState:true,
            })
        }

        //return { visible, globalSearchFields, globalSearchPlaceholder, columns, ps_danger_dialog, confirmDeleteClicked, colFilterOptions, csvUploadClicked, ps_csv_modal }

        function loadEyeFilter(value) {
            colFilterOptions.value = value;
        }

        return {
            loadEyeFilter,
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
            confirmDeleteClicked,
            ps_error_dialog,
            colFilterOptions,
            reRenderToogle
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
                    label: trans('core__phone_country_code_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleCreate() {
            this.$inertia.get(route("phone_country_code.create"));
        },
        handleEdit(id) {
            this.$inertia.get(route('phone_country_code.edit', id));
        },
        FilterOptionshandle(value) {
        router.put(route('phone_country_code.screenDisplayUiSetting.store'),
            {
                value,
                sort_field :this.sort_field ,
                sort_order:this.sort_order,
                row:this.phoneCountryCodes.per_page,
                search:this.search.value,
                current_page:this.phoneCountryCodes.current_page
            },
            {
                preserveScroll: true,
                preserveState:true,
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
    watch: {
        hideShowFieldForFilterArr(current, previous){
            this.loadEyeFilter(current);
        },
        showCoreAndCustomFieldArr(value){
            this.columns = value.map(column => {
                return {
                    action: column.action,
                    field: column.field,
                    label: trans(column.label),
                    sort: column.sort,
                    type: column.type,
                };
            });
        }
    },
})
</script>
