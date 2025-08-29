<template>
    <Head :title="$t('offline_paid_item_module')" />
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
        <ps-table2 :row="row" :search="search" :object="this.paidItems" :colFilterOptions="colFilterOptions"
            :columns="columns" :sort_field="sort_field" :sort_order="sort_order"
            @FilterOptionshandle="FilterOptionshandle" @handleSort="handleSorting" @handleSearch="handleSearching"
            @handleRow="handleRow" :searchable="showFilter"
            :globalSearchPlaceholder="$t('core__be_search_item')"
            :eye_filter="false">


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
                 <!-- category filter -->
                <ps-dropdown @on-click="dropdownClick" align="" class=" h-10" >
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__be_category')" :border="(selected_cat !== '' && selected_cat !== 'all') ?'border border-indigo-500/100':'border border-1 border-secondary-200'"
                            :selectedValue="(selected_cat == '' || selected_cat == 'all') ? '' : selectedCategory.name "
                        />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleCategoryfilter('all')">
                                    <ps-label class="text-gray-200 ms-2">{{$t('core__be_select_all')}}</ps-label>
                                </div>
                                <div v-for="category in categories" :key="category.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleCategoryfilter(category.id)">
                                    <ps-label class="ms-2" :class="category.id == selected_cat ? ' font-bold' : ''">
                                        {{ category.name }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #loadmore>
                       <div  @click="dropdownClick(true)" v-if="category_loadmore_visible" class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center">
                        <div class="flex flex-row items-center justify-between">
                                    <ps-label  class="ms-2 ">
                                        {{is_loading ? $t('core__be_loading') :$t('core__be_load_more')}}
                                    </ps-label>
                                    <ps-icon theme="text-black dark:text-primary-900" name="load" w="16" h="16" />
                         </div>
                       </div>
                    </template>
                     <template #filter>
                        <div class="mt-1 mx-1">
                            <ps-input-with-right-icon  class="w-full h-10" theme="bg-gray-100"  rounded="rounded-lg" v-model:value="catSearch" :placeholder="$t('core__be_search')" >
                                <template #icon >
                                    <ps-icon name="search" class='cursor-pointer' />
                                </template>
                            </ps-input-with-right-icon>
                        </div>
                    </template>
                </ps-dropdown>


                <!-- subcategory filter -->
                <ps-dropdown v-if="$page.props.isSubCategoryOn == '1'" @on-click="subCategoryDropdownClick" class=" h-10">
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__be_subcategory')" :border="(selected_sub_cat !== '' && selected_sub_cat !== 'all') ?'border border-indigo-500/100':'border border-1 border-secondary-200'"
                            :selectedValue="(selected_sub_cat == '' || selected_sub_cat == 'all') ? '' :selectedSubcategory.name"
                            />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleSubcategoryfilter('all')">
                                    <ps-label class="text-gray-200 ms-2">{{$t('core__be_select_all')}}</ps-label>
                                </div>
                                <!-- <div v-if="selected_cat == 'all' || selected_cat==''"> -->
                                    <div v-for="subcategory in subCategories" :key="subcategory.id"
                                        class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                        @click="handleSubcategoryfilter(subcategory.id)">
                                        <ps-label class="ms-2"
                                            :class="subcategory.id == selected_sub_cat ? ' font-bold' : ''">
                                            {{ subcategory.name }} </ps-label>
                                    </div>
                                <!-- </div> -->
                                <!-- <div v-else>
                                    <div v-for="subcategory in subCategories.filter((subCat) => subCat.category_id == selected_cat)" :key="subcategory.id"
                                        class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                        @click="handleSubcategoryfilter(subcategory.id)">
                                        <ps-label class="ms-2"
                                            :class="subcategory.id == selected_sub_cat ? ' font-bold' : ''">
                                            {{ subcategory.name }} </ps-label>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </template>
                     <template #loadmore>
                       <div  @click="subCategoryDropdownClick(true)" v-if="subCategory_loadmore_visible" class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center">
                            <!-- <ps-label v-if="is_loading"  class="ms-2 italic underline">
                                {{$t('core__be_loading')}}
                            </ps-label> -->
                        <div class="flex flex-row items-center justify-between">
                                    <ps-label  class="ms-2 ">
                                        {{is_loading ? $t('core__be_loading') :$t('core__be_load_more')}}
                                    </ps-label>
                                    <ps-icon theme="text-black dark:text-primary-900" name="load" w="16" h="16" />
                         </div>
                       </div>
                    </template>
                     <template #filter>
                        <div class="mt-1 mx-1">
                            <ps-input-with-right-icon  class="w-full h-10" theme="bg-gray-100"  rounded="rounded-lg" v-model:value="subCatSearch" :placeholder="$t('core__be_search')" >
                                <template #icon >
                                    <ps-icon name="search" class='cursor-pointer' />
                                </template>
                            </ps-input-with-right-icon>
                        </div>
                    </template>
                </ps-dropdown>
                <!-- payment status filter -->
                <ps-dropdown align="" class=" h-10">
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__be_payment_status')" :border="(selected_payment_status !== '' && selected_payment_status !== 'all') ?'border border-indigo-500/100':'border border-1 border-secondary-200'"
                            :selectedValue="(selected_payment_status == '' || selected_payment_status == 'all') ? '' : types.filter(option => option.id == selected_payment_status)[0].name"
                        />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handlePaymentStatusfilter('all')">
                                    <ps-label class="text-gray-200 ms-2">{{$t('core__be_select_all')}}</ps-label>
                                </div>
                                <div v-for="type in types" :key="type.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handlePaymentStatusfilter(type.id)">
                                    <ps-label class="ms-2" :class="type.id == selected_payment_status ? ' font-bold' : ''">
                                        {{ type.name }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>
                </ps-dropdown>
            </template>
            <template #tableActionRow="rowProps">
                <div class="flex flex-row " v-if="rowProps.field == 'action'">
                    <ps-button :disabled="!rowProps.row.authorizations.update" @click="handleEdit(rowProps.row.id)" class="me-2" rounded="rounded-lg" colors="bg-green-400 text-white"
                        padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-green-100"
                        focus="focus:outline-none focus:ring focus:ring-green-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                    </ps-button>
                    <ps-button :disabled="!rowProps.row.authorizations.delete" @click="confirmDeleteClicked(rowProps.row.id)" rounded="rounded-lg" colors="bg-red-400 text-white"
                        padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>
            </template>
            <template #tableRow="rowProps">

                <span v-if="rowProps.field == itmPurchasedOption + '@@name'">
                    <ps-badge class="" v-if="rowProps.row[itmPurchasedOption + '@@name']">{{ rowProps.row[itmPurchasedOption + '@@name'] }}</ps-badge>
                </span>

                <span v-if="rowProps.field == itmItemType + '@@name'">
                    <ps-badge theme="text-red-500 bg-red-100" class="" v-if="rowProps.row[itmItemType + '@@name']">{{ rowProps.row[itmItemType + '@@name'] }}</ps-badge>
                </span>

                <span v-if="rowProps.field == itmConditionOfItem + '@@name'">
                    <ps-badge theme="text-red-500 bg-red-100" class="" v-if="rowProps.row[itmConditionOfItem + '@@name']">{{ rowProps.row[itmConditionOfItem + '@@name'] }}</ps-badge>
                </span>

                <ps-label v-if="rowProps.field == 'status' && rowProps.row.status == 0">
                    <ps-label class=" whitespace-nowrap dark:text-white">
                        <ps-label class="flex flex-row" textColor="text-yellow-500">
                            <ps-label class="w-2 h-2 my-auto rounded-full me-2" textColor="bg-yellow-500"></ps-label> {{ $t('core__be_waiting_for_approval') }}
                        </ps-label>
                    </ps-label>
                </ps-label>

                <ps-label v-if="rowProps.field == 'status' && rowProps.row.status == 1">
                    <ps-label class=" whitespace-nowrap dark:text-white">
                        <ps-label class="flex flex-row" textColor="text-green-600">
                            <ps-label class="w-2 h-2 my-auto rounded-full me-2" textColor="bg-green-600"> </ps-label> {{$t('core__be_approved')}}
                        </ps-label>
                    </ps-label>
                </ps-label>

                <ps-label v-if="rowProps.field == 'status' && rowProps.row.status == 2">
                    <ps-label class=" whitespace-nowrap dark:text-white">
                        <ps-label class="flex flex-row" textColor="text-red-500">
                            <ps-label class="w-2 h-2 my-auto rounded-full me-2" textColor="bg-red-500"> </ps-label> {{$t('core__be_rejected')}}
                        </ps-label>
                    </ps-label>
                </ps-label>

            </template>

        </ps-table2>
        <!-- data table end -->
    </ps-layout>
</template>

<script>
import { defineComponent, ref, reactive,watch } from 'vue'
import { Link, Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
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
import PsTextButton from "@/Components/Core/Buttons/PsTextButton.vue";
import PsBadge from "@/Components/Core/Badge/PsBadge.vue"
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import { getCategories, getSubCat, getCustomFields, getCities, getTownships, getUsers } from '@/Api/psApiService.js'
import PsInput from "@/Components/Core/Input/PsInput.vue";
import debounce from 'lodash/debounce';
import { trans } from 'laravel-vue-i18n';
import PsInputWithRightIcon from '@/Components/Core/Input/PsInputWithRightIcon.vue';

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
        PsTextButton,
        PsBadge,
        PsDropdown,
        PsDropdownSelect,
        PsInput,
        PsInputWithRightIcon
    },
    layout : PsLayout,
    props: {
        can: Object,
        status: Object,
        paidItems: Object,
        hideShowFieldForFilterArr: Object,
        showCoreAndCustomFieldArr: Object,
        selectedCategory: { type: String, default: '' },
        selectedSubcategory: { type: String, default: '' },
        selectedPaymentStatus: { type: String, default: '' },
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
        isSubCategoryOn: String,
        search: String,
        itmPurchasedOption: String,
        itmDealOption: String,
        itmConditionOfItem: String,
        itmItemType: String,
    },
    setup(props) {
        const showFilter = ref(false);
        const clearFilter = ref(false);

        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
        let selected_cat = props.selectedCategory ? ref(props.selectedCategory.id) : ref('');
        let selected_sub_cat = props.selectedSubcategory ? ref(props.selectedSubcategory.id) : ref('');
        let selected_payment_status = props.selectedPaymentStatus ? ref(props.selectedPaymentStatus) : ref('');

        let categories = ref([]);
        let category_loadmore_visible= ref(false);
        let catSearch = ref();
        let is_loading = ref(false);

        let subCategories = ref([]);
        let subCategory_loadmore_visible= ref(false);
        let subCatSearch = ref();

        const ps_danger_dialog = ref();

        let types = [
            {
                id: '0',
                name: trans('core__be_waiting_for_approval'),
            },
            {
                id: '1',
                name: trans('core__be_approved'),
            },
            {
                id: '2',
                name: trans('core__be_rejected'),
            }
        ];

        const colFilterOptions = ref()

        let columns = [];
        if(props.isSubCategoryOn == '1'){
            columns = [
                {
                    label: trans('  '),
                    field: "action",
                    type: 'Action'
                },
                {
                    label: trans('core__be_item'),
                    field: "title",
                    type: 'String',
                },
                {
                    label: trans('core__be_category'),
                    field: "category",
                    type: 'String',
                },
                {
                    label: trans('core__be_subcategory'),
                    field: "subcategory",
                    type: 'String',
                },
                {
                    label: trans('core__be_payment_status'),
                    field: "status",
                    type: 'String',
                    action: 'Action'
                },
            ]
        }else{
            columns = [
                {
                    label: trans('  '),
                    field: "action",
                    type: 'Action'
                },
                {
                    label: trans('core__be_item'),
                    field: "title",
                    type: 'String',
                },
                {
                    label: trans('core__be_category'),
                    field: "category",
                    type: 'String',
                },
                {
                    label: trans('core__be_payment_status'),
                    field: "status",
                    type: 'String',
                    action: 'Action'
                },
            ]
        }

        function confirmDeleteClicked(id) {
            ps_danger_dialog.value.openModal(
                trans('itemPromotion__delete_offline_paid_itm'),
                trans('itemPromotion__del_offline_paid_itm_info'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    router.delete(route("offline_paid_item.destroy", id), {
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

        function handleSorting(value) {
            sort_field.value = value.field
            sort_order.value = value.sort_order
            handleSearchingSorting()
        }

        function handleClearFilter() {
            selected_cat.value = 'all';
            selected_sub_cat.value = 'all';
            selected_payment_status.value = 'all';
            handleSearchingSorting();
        }

        function handleCategoryfilter(value) {

            selected_sub_cat.value = "all"
            selected_cat.value = value
            subCategories.value = [];
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleSubcategoryfilter(value) {
            selected_sub_cat.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        function handlePaymentStatusfilter(value) {
            selected_payment_status.value = value
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
            router.get(route('offline_paid_item.index'),
                {
                    sort_field: sort_field.value,
                    sort_order: sort_order.value,
                    page: page ?? props.paidItems.meta.current_page,
                    row: row ?? props.paidItems.meta.per_page,
                    search: search.value,
                    category_filter: selected_cat.value,
                    sub_category_filter: selected_sub_cat.value,
                    payment_status_filter: selected_payment_status.value,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }
                            // Category data
            function getCategoriesData(offset){
                category_loadmore_visible.value = true;
                is_loading.value = true
                getCategories(offset,catSearch.value,props.authUser.id).then(response => {

                    if(!response.data.length){
                        category_loadmore_visible.value = false;
                    }
                    else{
                        response.data.forEach(element =>{
                            categories.value.push(element);
                        });
                    }
                    is_loading.value=false;
                });
            }

            function dropdownClick(loadMore = null) {

                let offset = categories.value ? categories.value.length : 0 ;
                if(offset == 0 || loadMore == true){

                    getCategoriesData(offset);
                }
            }
            watch(catSearch,_.debounce((current,previous)=>{
                let offset= 0;
                categories.value = [];
                getCategoriesData(offset);

            },500))

            // Sub Category
            function getSubCategoriesData(offset){

                subCategory_loadmore_visible.value = true;
                is_loading.value = true
                getSubCat(offset,subCatSearch.value,props.authUser.id,selected_cat.value).then(response => {

                    if(!response.data.length){
                        subCategory_loadmore_visible.value = false;
                    }
                    else{
                        response.data.forEach(element =>{
                            subCategories.value.push(element);
                        });
                    }
                        is_loading.value=false;
                });
            }

            function subCategoryDropdownClick(loadMore = null) {


                let offset = subCategories.value ? subCategories.value.length : 0 ;
                if(offset == 0 || loadMore == true){
                    getSubCategoriesData(offset);
                }
            }
            watch(subCatSearch,_.debounce((current,previous)=>{
                let offset= 0;
                subCategories.value = [];
                getSubCategoriesData(offset);

            },500))


        let visible = ref(false)
        return {
            showFilter,
            clearFilter,
            columns,
            confirmDeleteClicked,
            ps_danger_dialog,
            colFilterOptions,
            visible,
            handleSorting,
            handleSearchingSorting,
            handleCategoryfilter,
            handleSubcategoryfilter,
            handlePaymentStatusfilter,
            handleClearFilter,
            handleRow,
            handleSearching,
            selected_cat,
            selected_sub_cat,
            selected_payment_status,
            types,
            dropdownClick,
            categories,
            category_loadmore_visible,
            catSearch,
            subCategoryDropdownClick,
            subCategories,
            subCategory_loadmore_visible,
            subCatSearch,
            is_loading,
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
                    label: trans('offline_paid_item_module'),
                    color: "text-primary-500"
                }
            ]
        }
    },
    // created() {
    //     this.columns = this.showCoreAndCustomFieldArr.map(column => {
    //         return {
    //             action: column.action,
    //             field: column.field,
    //             label: trans(column.label),
    //             type: column.type,
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
    methods: {
        handleCreate() {
            this.$inertia.get(route("offline_paid_item.create"));
        },
        handleEdit(id){
            this.$inertia.get(route('offline_paid_item.edit',id));
        },
        FilterOptionshandle(value) {
            router.put(route('offline_paid_item.screenDisplayUiSetting.store'),
                {
                    value,
                    sort_field: this.sort_field,
                    sort_order: this.sort_order,
                    row: this.paidItems.per_page,
                    search: this.search.value,
                    current_page: this.paidItems.current_page
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                });
        },
    },
})
</script>
