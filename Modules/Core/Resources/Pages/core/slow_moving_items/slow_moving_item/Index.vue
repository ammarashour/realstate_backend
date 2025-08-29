<template>
    <Head :title="$t('slow_moving_item_module')" />
    <ps-layout>

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
        <ps-table2 :row="row" :search="search" :object="this.items" :colFilterOptions="colFilterOptions" :columns="columns"
            :sort_field="sort_field" :sort_order="sort_order" @FilterOptionshandle="FilterOptionshandle"
            @handleSort="handleSorting" @handleSearch="handleSearching" :globalSearchPlaceholder="$t('core__be_search')"
            @handleRow="handleRow" :searchable="showFilter" :eye_filter="false">

            <template #searchRight>
                <!-- date filter -->
                <date-picker v-if="reRenderDate" @datepick="handleDateFilter"
                    class="rounded text-primary-400 shadow-sm pt-0.5 me-2 focus:outline-none focus:ring-1 focus:ring-primary-500"
                    :class="(selected_date == null || selected_date == '') ? 'w-full' : 'w-full'"
                    v-model:value="selected_date" :range="true" :inline="false" :autoApply="false" />

                <ps-text-button v-if="showFilter" @click="handleClearFilter()"
                    class="flex text-sm items-center text-red-400 dark:text-red-400" padding="py-1 px-4">
                    <ps-icon theme="dark:text-red-400" name="cross" class="me-3" />
                    {{ $t('core__be_clear_filter') }}
                </ps-text-button>
                <ps-icon-button :colors="!showFilter ? '' : 'bg-primary-500 text-white dark:text-secondary-800'" focus=""
                    padding="py-1 px-4" hover="hover:bg-primary-500 hover:text-white"
                    :border="!showFilter ? ' border border-secondary-200' : 'border border-primary-500'" leftIcon="filter"
                    @click="showFilter = !showFilter">{{ $t('core__be_filter') }}</ps-icon-button>
            </template>

            <template #Filter>

                <!-- category filter -->
                <ps-dropdown @on-click="dropdownClick" align="" class=" h-10">
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__be_category')"
                            :border="(selected_cat !== '' && selected_cat !== 'all') ? 'border border-indigo-500/100' : 'border border-1 border-secondary-200'"
                            :selectedValue="(selected_cat == '' || selected_cat == 'all') ? '' : selectedCategory.name" />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleCategoryfilter('all')">
                                    <ps-label class="text-gray-200 ms-2">{{ $t('core__be_select_all') }}</ps-label>
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
                        <div @click="dropdownClick(true)" v-if="category_loadmore_visible"
                            class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center">
                            <div class="flex flex-row items-center justify-between">
                                <ps-label class="ms-2 ">
                                    {{ is_loading ? $t('core__be_loading') : $t('core__be_load_more') }}
                                </ps-label>
                                <ps-icon theme="text-black dark:text-primary-900" name="load" w="16" h="16" />
                            </div>
                        </div>
                    </template>
                    <template #filter>
                        <!-- <ps-input type="text" :placeholder="$t('core__be_category')" v-model:value="catSearch" class=""/> -->
                        <div class="mt-1 mx-1">
                            <ps-input-with-right-icon class="w-full h-10" theme="bg-gray-100" rounded="rounded-lg"
                                v-model:value="catSearch" :placeholder="$t('core__be_search')">
                                <template #icon>
                                    <ps-icon name="search" class='cursor-pointer' />
                                </template>
                            </ps-input-with-right-icon>
                        </div>
                    </template>
                </ps-dropdown>

                <!-- purchase option -->
                <!-- <ps-dropdown align="" class=" h-10">
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__be_purchased_option')"
                            :selectedValue="(selected_purchase_option == '' || selected_purchase_option == 'all') ? '' : uis[this.itmPurchasedOption].filter(option => option.id == selected_purchase_option)[0].name" />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handlePurchaseOptionFilter('all')">
                                    <ps-label class="text-gray-200 ms-2">{{$t('core__be_select_all')}}</ps-label>
                                </div>
                                <div v-for="item in uis[this.itmPurchasedOption]" :key="item.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handlePurchaseOptionFilter(item.id)">
                                    <ps-label class="ms-2" :class="item.id == selected_purchase_option ? ' font-bold' : ''">
                                        {{ item.name }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>
                </ps-dropdown> -->
                <ps-dropdown @on-click="purchaseOptionDropdownClick" align="" class=" h-10">
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__be_purchased_option')"
                            :border="(selected_purchase_option !== '' && selected_purchase_option !== 'all') ? 'border border-indigo-500/100' : 'border border-1 border-secondary-200'"
                            :selectedValue="(selected_purchase_option == '' || selected_purchase_option == 'all') ? '' : selectedPurchaseOption.name" />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handlePurchaseOptionFilter('all')">
                                    <ps-label class="text-gray-200 ms-2">{{ $t('core__be_select_all') }}</ps-label>
                                </div>
                                <div v-for="option in purchaseOptions" :key="option.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handlePurchaseOptionFilter(option.id)">
                                    <ps-label class="ms-2"
                                        :class="option.id == selected_purchase_option ? ' font-bold' : ''">
                                        {{ option.name }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #loadmore>
                        <div @click="purchaseOptionDropdownClick(true)" v-if="purchaseOptions_loadmore_visible"
                            class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center">
                            <div class="flex flex-row items-center justify-between">
                                <ps-label class="ms-2 ">
                                    {{ is_loading ? $t('core__be_loading') : $t('core__be_load_more') }}
                                </ps-label>
                                <ps-icon theme="text-black dark:text-primary-900" name="load" w="16" h="16" />
                            </div>
                        </div>
                    </template>

                </ps-dropdown>

                <!-- deal option -->
                <!-- <ps-dropdown align="" class=" h-10">
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__be_deal_option')"
                            :selectedValue="(selected_deal_option == '' || selected_deal_option == 'all') ? '' : uis[this.itmDealOption].filter(option => option.id == selected_deal_option)[0].name" />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleDealOptionFilter('all')">
                                    <ps-label class="text-gray-200 ms-2">{{$t('core__be_select_all')}}</ps-label>
                                </div>
                                <div v-for="item in uis[this.itmDealOption]" :key="item.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleDealOptionFilter(item.id)">
                                    <ps-label class="ms-2" :class="item.id == selected_deal_option ? ' font-bold' : ''">
                                        {{ item.name }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>
                </ps-dropdown> -->
                <ps-dropdown @on-click="dealOptionDropdownClick" align="" class=" h-10">
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__be_deal_option')"
                            :border="(selected_deal_option !== '' && selected_deal_option !== 'all') ? 'border border-indigo-500/100' : 'border border-1 border-secondary-200'"
                            :selectedValue="(selected_deal_option == '' || selected_deal_option == 'all') ? '' : selectedDealOption.name" />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleDealOptionFilter('all')">
                                    <ps-label class="text-gray-200 ms-2">{{ $t('core__be_select_all') }}</ps-label>
                                </div>
                                <div v-for="option in dealOptions" :key="option.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleDealOptionFilter(option.id)">
                                    <ps-label class="ms-2"
                                        :class="option.id == selected_purchase_option ? ' font-bold' : ''">
                                        {{ option.name }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #loadmore>
                        <div @click="dealOptionDropdownClick(true)" v-if="dealOptions_loadmore_visible"
                            class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center">
                            <div class="flex flex-row items-center justify-between">
                                <ps-label class="ms-2 ">
                                    {{ is_loading ? $t('core__be_loading') : $t('core__be_load_more') }}
                                </ps-label>
                                <ps-icon theme="text-black dark:text-primary-900" name="load" w="16" h="16" />
                            </div>
                        </div>
                    </template>
                </ps-dropdown>
            </template>

            <template #tableActionRow="rowProps">
                <div class="flex flex-row" v-if="rowProps.field == 'action'">
                    <ps-button :disabled="!rowProps.row.authorizations.update" @click="handleEdit(rowProps.row.id)"
                        class="me-2" rounded="rounded-lg" colors="bg-green-400 text-white" padding="p-1.5"
                        hover="hover:outline-none hover:ring hover:ring-green-100"
                        focus="focus:outline-none focus:ring focus:ring-green-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                    </ps-button>
                    <ps-button :disabled="!rowProps.row.authorizations.delete"
                        @click="confirmDeleteClicked(rowProps.row.id)" rounded="rounded-lg" colors="bg-red-400 text-white"
                        padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>
            </template>

            <template #tableRow="rowProps">
                <span v-if="rowProps.field == itmPurchasedOption + '@@name'">
                    <ps-badge class="" v-if="rowProps.row[itmPurchasedOption + '@@name']">{{ rowProps.row[itmPurchasedOption
                        + '@@name'] }}</ps-badge>
                </span>

                <span v-if="rowProps.field == itmItemType + '@@name'">
                    <ps-badge theme="text-red-500 bg-red-100" class="" v-if="rowProps.row[itmItemType + '@@name']">{{
                        rowProps.row[itmItemType + '@@name'] }}</ps-badge>
                </span>
                <div v-if="rowProps.field == 'price'">

                    {{ checkPriceFormat(rowProps.row.price) }}

                </div>
            </template>
        </ps-table2>
    </ps-layout>
</template>

<script>
import { ref, defineComponent, watch } from "vue";
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
import DatePicker from "@/Components/Core/DateTime/DatePicker.vue";
import PsBadge from "@/Components/Core/Badge/PsBadge.vue"
import { getCategories, getSubCat, getCustomFields, getCities, getTownships, getUsers, getPurchaseOption, getDealOption } from '@/Api/psApiService.js'
import PsInput from "@/Components/Core/Input/PsInput.vue";
import debounce from 'lodash/debounce';
import PsInputWithRightIcon from '@/Components/Core/Input/PsInputWithRightIcon.vue';
import { trans } from 'laravel-vue-i18n';
import PsConst from '@templateCore/object/constant/ps_constants';

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
        DatePicker,
        PsBadge,
        PsInput,
        PsInputWithRightIcon
    },
    layout: PsLayout,
    props: {
        status: Object,
        items: Object,
        categories: Object,
        customizeHeaders: Object,
        customizeDetails: Object,
        hideShowFieldForFilterArr: Object,
        showCoreAndCustomFieldArr: Object,
        selectedCategory: { type: String, default: '' },
        selectedDealOption: { type: String, default: '' },
        selectedPurchaseOption: { type: String, default: '' },
        selectedDate: { type: String, default: '' },
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
        itmPurchasedOption: String,
        itmDealOption: String,
        itmConditionOfItem: String,
        itmItemType: String,
        isSubCategoryOn: String,
        selected_price_type: String,

    },
    data() {
        return {
            location_city: "",
            category: "",
        };
    },
    setup(props) {
        const showFilter = props.selectedCategory || props.selectedDate || props.selectedDealOption || props.selectedPurchasedOption ? ref(true) : ref(false);
        const clearFilter = ref(false);

        let visible = ref(false)
        const reRenderDate = ref(true);

        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
        let selected_cat = props.selectedCategory ? ref(props.selectedCategory.id) : ref('');
        let selected_deal_option = props.selectedDealOption ? ref(props.selectedDealOption.id) : ref('');
        let selected_purchase_option = props.selectedPurchaseOption ? ref(props.selectedPurchaseOption.id) : ref('');
        let selected_date = props.selectedDate ? ref(props.selectedDate) : ref('');

        let categories = ref([]);
        let category_loadmore_visible = ref(false);
        let catSearch = ref();
        let is_loading = ref(false);

        let purchaseOptions = ref([]);
        let purchaseOptions_loadmore_visible = ref(false);
        let purchaseOptionsSearch = ref();

        let dealOptions = ref([]);
        let dealOptions_loadmore_visible = ref(false);
        let dealOptionsSearch = ref();


        // const colFilterOptions = ref();
        // let columns = ref();

        const ps_danger_dialog = ref();

        function confirmDeleteClicked(id) {
            ps_danger_dialog.value.openModal(
                trans('core__be_delete_item'),
                trans('core__be_delete_item_info'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    router.delete(route("slow_moving_item.destroy", id), {
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
            selected_purchase_option.value = 'all';
            selected_deal_option.value = 'all';
            selected_date.value = '';
            handleSearchingSorting();

            reRenderDate.value = false;
            setTimeout(() => {
                reRenderDate.value = true;
            }, 100);
        }

        function handleCategoryfilter(value) {
            selected_cat.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleDateFilter(value) {
            selected_date.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleDealOptionFilter(value) {
            selected_deal_option.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        function handlePurchaseOptionFilter(value) {
            selected_purchase_option.value = value
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
            router.get(route('slow_moving_item.index'),
                {
                    sort_field: sort_field.value,
                    sort_order: sort_order.value,
                    page: page ?? props.items.meta.current_page,
                    row: row ?? props.items.meta.per_page,
                    search: search.value,
                    category_filter: selected_cat.value,
                    deal_option_filter: selected_deal_option.value,
                    purchase_option_filter: selected_purchase_option.value,
                    date_filter: selected_date.value,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }

        let columns = [];
        if (props.isSubCategoryOn == '1') {
            columns = [
                {
                    label: trans('core__be_seller_name'),
                    field: "added_user_id@@name",
                    type: 'Integer',
                    action: 'Action'
                },
                {
                    label: trans('core__be_items'),
                    field: "title",
                    type: 'String',
                    action: 'Action'
                },
                {
                    label: trans('core__be_item_categories'),
                    field: "category_id@@name",
                    type: 'Integer',
                    action: 'Action'
                },
                {
                    label: trans('core__be_item_subcategories'),
                    field: "subcategory_id@@name",
                    type: 'Integer',
                    action: 'Action'
                },
                {
                    label: trans('core__be_purchased_option'),
                    field: props.itmPurchasedOption + '@@name',
                    type: "String",
                },
                {
                    label: trans('core__be_item_type'),
                    field: props.itmItemType + '@@name',
                    type: "String",
                },
                {
                    label: trans('core__be_deal_option'),
                    field: props.itmDealOption + '@@name',
                    type: 'String',
                    action: 'Action'
                },
                {
                    label: trans('core__be_engagement'),
                    field: "item_touch_count",
                    type: 'Integer',
                    action: 'Action'
                },
                {
                    label: 'core__be_post_date',
                    field: "added_date",
                    type: 'Timestamp',
                    action: 'Action'
                },
                {
                    label: trans('core__be_action_label'),
                    field: "action",
                    type: 'Action'
                },
            ]
        } else {
            columns = [
                {
                    label: trans('core__be_seller_name'),
                    field: "added_user_id@@name",
                    type: 'Integer',
                    action: 'Action'
                },
                {
                    label: trans('core__be_items'),
                    field: "title",
                    type: 'String',
                    action: 'Action'
                },
                {
                    label: trans('core__be_item_categories'),
                    field: "category_id@@name",
                    type: 'Integer',
                    action: 'Action'
                },
                {
                    label: trans('core__be_purchased_option'),
                    field: props.itmPurchasedOption + '@@name',
                    type: "String",
                },
                {
                    label: trans('core__be_item_type'),
                    field: props.itmItemType + '@@name',
                    type: "String",
                },
                {
                    label: trans('core__be_deal_option'),
                    field: props.itmDealOption + '@@name',
                    type: 'String',
                    action: 'Action'
                },
                {
                    label: trans('core__be_engagement'),
                    field: "item_touch_count",
                    type: 'Integer',
                    action: 'Action'
                },
                {
                    label: 'core__be_post_date',
                    field: "added_date",
                    type: 'Timestamp',
                    action: 'Action'
                },
                {
                    label: trans('core__be_action_label'),
                    field: "action",
                    type: 'Action'
                },
            ]
        }

        const colFilterOptions = ref();

        function checkPriceFormat(data) {
            // alert(data);
            if (props.selected_price_type == PsConst.PRICE_RANGE) {

                const floatValue = parseFloat(data);
                const intValue = parseInt(floatValue);
                if (intValue > 5) {
                    return '$'.repeat(5);
                }
                if (intValue < 1) {
                    return '$'.repeat(1);
                }
                return '$'.repeat(intValue);
            }
            if (props.selected_price_type == PsConst.NORMAL_PRICE) {
                return data;
            }
        }

        if (props.selected_price_type != PsConst.NO_PRICE) {

            const indexOfTitleColumn = columns.findIndex(column => column.field === "category_id@@name");
            const priceColumn = {
                label: trans('core__be_item_price'),
                field: "price",
                type: 'Integer',
                action: 'Action'
            }
            if (indexOfTitleColumn !== -1) {
                columns.splice(indexOfTitleColumn + 1, 0, priceColumn);
            }
        }

        // Category data
        function getCategoriesData(offset) {
            category_loadmore_visible.value = true;
            is_loading.value = true
            getCategories(offset, catSearch.value, props.authUser.id).then(response => {

                if (!response.data.length) {
                    category_loadmore_visible.value = false;
                }
                else {
                    response.data.forEach(element => {
                        categories.value.push(element);
                    });
                }
                is_loading.value = false;
            });
        }

        function dropdownClick(loadMore = null) {

            let offset = categories.value ? categories.value.length : 0;
            if (offset == 0 || loadMore == true) {

                getCategoriesData(offset);
            }
        }
        watch(catSearch, _.debounce((current, previous) => {
            let offset = 0;
            categories.value = [];
            getCategoriesData(offset);

        }, 500))

        // purchase option customfield
        function getPurchaseOptionsData(offset) {
            purchaseOptions_loadmore_visible.value = true;
            is_loading.value = true
            getPurchaseOption(offset, props.authUser.id).then(response => {

                if (!response.data.length) {
                    purchaseOptions_loadmore_visible.value = false;
                }
                else {
                    response.data.forEach(element => {
                        purchaseOptions.value.push(element);
                    });
                }
                is_loading.value = false;
            }).catch(function (error) {
                if (error) {
                    is_loading.value = false;
                    purchaseOptions_loadmore_visible.value = false;
                }
            });
        }
        function purchaseOptionDropdownClick(loadMore = null) {

            let offset = purchaseOptions.value ? purchaseOptions.value.length : 0;
            if (offset == 0 || loadMore == true) {

                getPurchaseOptionsData(offset);
            }
        }

        // deal option customfield
        function getDealOptionsData(offset) {
            dealOptions_loadmore_visible.value = true;
            is_loading.value = true
            getDealOption(offset, props.authUser.id).then(response => {

                if (!response.data.length) {
                    dealOptions_loadmore_visible.value = false;
                }
                else {
                    response.data.forEach(element => {
                        dealOptions.value.push(element);
                    });
                }
                is_loading.value = false;
            }).catch(function (error) {
                if (error) {
                    is_loading.value = false;
                    dealOptions_loadmore_visible.value = false;
                }
            });
        }
        function dealOptionDropdownClick(loadMore = null) {

            let offset = dealOptions.value ? dealOptions.value.length : 0;
            if (offset == 0 || loadMore == true) {

                getDealOptionsData(offset);
            }
        }
        return {
            reRenderDate,
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
            handleDealOptionFilter,
            handlePurchaseOptionFilter,
            handleDateFilter,
            handleClearFilter,
            handleRow,
            handleSearching,
            selected_cat,
            selected_deal_option,
            selected_purchase_option,
            selected_date,
            is_loading,
            dropdownClick,
            categories,
            category_loadmore_visible,
            catSearch,
            purchaseOptions,
            purchaseOptions_loadmore_visible,
            purchaseOptionsSearch,
            purchaseOptionDropdownClick,
            dealOptions,
            dealOptions_loadmore_visible,
            dealOptionsSearch,
            dealOptionDropdownClick,
            checkPriceFormat
        };
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('slow_moving_item_module'),
                    color: "text-primary-500"
                }
            ]
        }
    },
    created() {
        // this.columns = this.showCoreAndCustomFieldArr.map(column => {
        //     return {
        //         action: column.action,
        //         field: column.field,
        //         label: trans(column.label),
        //         type: column.type,
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
    methods: {
        handleDelete(id) {
            this.$inertia.delete(route("slow_moving_item.destroy", id));
        },
        handleEdit(id) {
            this.$inertia.get(route("slow_moving_item.edit", id));
        },
        FilterOptionshandle(value) {
            router.put(route('slow_moving_item.screenDisplayUiSetting.store'),
                {
                    value,
                    sort_field: this.sort_field,
                    sort_order: this.sort_order,
                    row: this.items.per_page,
                    search: this.search.value,
                    current_page: this.items.current_page
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                });

        },
    },
});
</script>
