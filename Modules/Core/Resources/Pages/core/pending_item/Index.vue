<template>

    <Head :title="$t('pending_item_module')" />
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
        <ps-table2 ::row="row" :search="search" :object="this.items" :colFilterOptions="colFilterOptions"
            :columns="columns" :sort_field="sort_field" :sort_order="sort_order"
            :globalSearchPlaceholder="$t('core__be_search_pending_item')"
            @FilterOptionshandle="FilterOptionshandle" @handleSort="handleSorting" @handleSearch="handleSearching"
            @handleRow="handleRow" :searchable="showFilter"
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
                <!-- <ps-dropdown align="" class=" h-10">
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__be_category')"
                            :selectedValue="(selected_cat == '' || selected_cat == 'all') ? '' : categories.filter(option => option.id == selected_cat)[0].name"
                        />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleCategoryfilter('all')">
                                    <ps-label class="text-gray-200">{{$t('core__be_select_all')}}</ps-label>
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
                </ps-dropdown> -->
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
                                    <ps-label class="ms-2 text-gray-200">{{$t('core__be_select_all')}}</ps-label>
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
                            <ps-input-with-right-icon  class="w-full h-10"  rounded="rounded-lg" v-model:value="catSearch" :placeholder="$t('core__be_search')" >
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
                                    <ps-label class="ms-2 text-gray-200">{{$t('core__be_select_all')}}</ps-label>
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
                            <!-- <ps-label v-if="is_loading"  class="ms-2 ">
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
                            <ps-input-with-right-icon  class="w-full h-10"  rounded="rounded-lg" v-model:value="subCatSearch" :placeholder="$t('core__be_search')" >
                                <template #icon >
                                    <ps-icon name="search" class='cursor-pointer' />
                                </template>
                            </ps-input-with-right-icon>
                        </div>
                    </template>
                </ps-dropdown>

                <!-- location city filter -->
                <ps-dropdown  @on-click="cityDropdownClick" align="" class=" h-10" >
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__be_city')" :border="(selected_city !== '' && selected_city !== 'all') ?'border border-indigo-500/100':'border border-1 border-secondary-200'"
                            :selectedValue="(selected_city == '' || selected_city == 'all') ? '' :selectedCity.name"

                            />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleCityfilter('all')">
                                    <ps-label class="ms-2 text-gray-200">{{$t('core__be_select_all')}}</ps-label>
                                </div>
                                <div v-for="city in cities" :key="city.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleCityfilter(city.id)">
                                    <ps-label class="ms-2" :class="city.id == selected_city ? ' font-bold' : ''">
                                        {{ city.name }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>
                     <template #loadmore>
                       <div  @click="cityDropdownClick(true)" v-if="city_loadmore_visible" class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center">
                            <!-- <ps-label v-if="is_loading"  class="ms-2 ">
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
                            <ps-input-with-right-icon  class="w-full h-10"  rounded="rounded-lg" v-model:value="citySearch" :placeholder="$t('core__be_search')" >
                                <template #icon >
                                    <ps-icon name="search" class='cursor-pointer' />
                                </template>
                            </ps-input-with-right-icon>
                        </div>
                    </template>
                </ps-dropdown>

                <!-- location township filter -->
                <ps-dropdown @on-click="townshipDropdownClick" class=" h-10" >
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__be_township')" :border="(selected_township !== '' && selected_township !== 'all') ?'border border-indigo-500/100':'border border-1 border-secondary-200'"
                            :selectedValue="(selected_township == '' || selected_township == 'all') ? '' :selectedTownship.name"
                        />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleTownshipfilter('all')">
                                    <ps-label class="ms-2 text-gray-200">{{$t('core__be_select_all')}}</ps-label>
                                </div>
                                <div>
                                    <div v-for="township in townships" :key="township.id"
                                        class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                        @click="handleTownshipfilter(township.id)"
                                        >
                                        <ps-label class="ms-2"
                                            :class="township.id == selected_township ? ' font-bold' : ''">
                                            {{ township.name }} </ps-label>
                                    </div>
                                </div>
                                <!-- <div v-else>
                                    <div v-for="township in townships.filter((t) => t.location_city_id == selected_city)" :key="township.id"
                                        class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                        @click="handleTownshipfilter(township.id)"
                                        >
                                        <ps-label class="ms-2"
                                            :class="township.id == selected_township ? ' font-bold' : ''">
                                            {{ township.name }} </ps-label>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </template>
                     <template #loadmore>
                       <div  @click="townshipDropdownClick(true)" v-if="townships_loadmore_visible" class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center">
                            <!-- <ps-label v-if="is_loading"  class="ms-2 ">
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
                            <ps-input-with-right-icon  class="w-full h-10"  rounded="rounded-lg" v-model:value="townshipsSearch" :placeholder="$t('core__be_search')" >
                                <template #icon >
                                    <ps-icon name="search" class='cursor-pointer' />
                                </template>
                            </ps-input-with-right-icon>
                        </div>
                    </template>
                </ps-dropdown>

            </template>

            <template #tableRow="rowProps">
                <div class="flex flex-row" v-if="rowProps.field == 'accept'">
                    <ps-button :disabled="!rowProps.row.authorizations.update" @click="[form.status='accept',handleStatusChange(rowProps.row.id)]"
                        colors="bg-green-500 text-white" hover="hover:outline-none hover:ring hover:ring-green-100"
                        focus="focus:outline-none focus:ring focus:ring-green-200">{{ $t('core__be_accept_lbl') }}</ps-button>
                </div>
                <div class="flex flex-row" v-if="rowProps.field == 'reject'">
                    <ps-button :disabled="!rowProps.row.authorizations.update" @click="[form.status='reject',handleStatusChange(rowProps.row.id)]" colors="bg-red-500 text-white"
                        hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">{{ $t('core__be_reject_lbl') }}</ps-button>
                </div>
                <!-- <div class="flex flex-row" v-if="rowProps.field == 'disable'">
                    <ps-button :disabled="!rowProps.row.authorizations.update" @click="[form.status='disable',handleStatusChange(rowProps.row.id)]" colors="bg-yellow-500 text-white"
                        hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">{{ $t('core__be_disable_lbl') }}</ps-button>
                </div> -->
                <div class="flex flex-row" v-if="rowProps.field == 'detail'">
                    <ps-text-button @click="handleDetail(rowProps.row.id)">{{ $t('core__be_btn_detail') }}</ps-text-button>
                </div>

                <span v-if="rowProps.field == itmPurchasedOption + '@@name'">
                    <ps-badge class="" v-if="rowProps.row[itmPurchasedOption + '@@name']">{{ rowProps.row[itmPurchasedOption + '@@name'] }}</ps-badge>
                </span>

                <span v-if="rowProps.field == itmItemType + '@@name'">
                    <ps-badge theme="text-red-500 bg-red-100" class="" v-if="rowProps.row[itmItemType + '@@name']">{{ rowProps.row[itmItemType + '@@name'] }}</ps-badge>
                </span>

                <span v-if="rowProps.field == itmConditionOfItem + '@@name'">
                    <ps-badge theme="text-red-500 bg-red-100" class="" v-if="rowProps.row[itmConditionOfItem + '@@name']">{{ rowProps.row[itmConditionOfItem + '@@name'] }}</ps-badge>
                </span>

            </template>

            <template #tableActionRow="rowProps">
                <div class="flex flex-row" v-if="rowProps.field == 'action'">
                    <ps-button :disabled="!rowProps.row.authorizations.delete" @click="confirmDeleteClicked(rowProps.row.id)" rounded="rounded-lg" colors="bg-red-400 text-white"
                        padding="p-1.5" hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>
            </template>
        </ps-table2>
        <!-- data table end -->
    </ps-layout>
</template>


<script>
import { ref, defineComponent, watch } from "vue";
import PsLayout from "@/Components/PsLayout.vue";
import { Head,useForm } from "@inertiajs/vue3";
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
import PsBadge from "@/Components/Core/Badge/PsBadge.vue"
import { getCategories, getSubCat, getCustomFields, getCities, getTownships, getUsers } from '@/Api/psApiService.js'
import PsInputWithRightIcon from '@/Components/Core/Input/PsInputWithRightIcon.vue';
import PsInput from "@/Components/Core/Input/PsInput.vue";
import debounce from 'lodash/debounce';
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
        PsBadge,
        PsInput,
        PsInputWithRightIcon
    },
    layout : PsLayout,
    props: {
        can: Object,
        status: Object,
        items: Object,
        categories: Object,
        subcategories: Object,
        customizeHeaders: Object,
        customizeDetails: Object,
        cities: Object,
        townships: Object,
        prices: Object,
        owners: Object,
        availables: Object,
        hideShowFieldForFilterArr: Object,
        showCoreAndCustomFieldArr: Object,
        selectedCategory: { type: String, default: '' },
        selectedSubcategory: { type: String, default: '' },
        selectedCity: { type: String, default: '' },
        selectedTownship: { type: String, default: '' },
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
    },
    setup(props) {
        let visible = ref(false)
        const ps_danger_dialog = ref();

        // For data table
        const showFilter = props.selectedCategory || props.selectedSubcategory || props.selectedCity || props.selectedTownship || props.selectedPrice || props.selectedAvailable || props.selectedOwner ||
        props.selectedAddedDate || props.selectedUpdatedDate? ref(true): ref(false);
        const clearFilter = ref(false);

        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
        let selected_cat = props.selectedCategory ? ref(props.selectedCategory.id) : ref('');
        let selected_sub_cat = props.selectedSubcategory ? ref(props.selectedSubcategory.id) : ref('');
        let selected_city = props.selectedCity ? ref(props.selectedCity.id) : ref('');
        let selected_township = props.selectedTownship ? ref(props.selectedTownship.id) : ref('');

        let categories = ref([]);
        let category_loadmore_visible= ref(false);
        let catSearch = ref();
        let is_loading = ref(false);

        let subCategories = ref([]);
        let subCategory_loadmore_visible= ref(false);
        let subCatSearch = ref();

        let cities = ref([]);
        let city_loadmore_visible= ref(false);
        let citySearch = ref();

        let townships = ref([]);
        let townships_loadmore_visible= ref(false);
        let townshipsSearch = ref();

        const colFilterOptions = ref();

        const columns = [
            {
                label: trans('core__be_action_label'),
                field: "action",
                type: "String"
            },
            {
                label: trans('core__be_item'),
                field: "title",
                type: "String"
            },
            {
                label: trans('core__be_posted_by'),
                field: "added_user_id@@name",
                type: 'String',
            },
            {
                label: trans('core__be_description'),
                field: "description",
                type: "String",
                sort: false,
            },
            {
                label: trans('core__be_accept_lbl'),
                field: "accept",
                type: "String",
                sort: false,
            },
            {
                label: trans('core__be_reject_lbl'),
                field: "reject",
                type: "String",
                sort: false,
            },

            {
                label: trans('core__be_date'),
                field: "added_date",
                type: "Timestamp",
                sort: false,
            },
            {
                label: trans('detail_label'),
                field: "detail",
                type: 'Action',
                sort: false,
            },
        ]

        function handleSorting(value) {
            sort_field.value = value.field
            sort_order.value = value.sort_order
            handleSearchingSorting()
        }

        function handleClearFilter() {
            selected_cat.value = 'all';
            selected_sub_cat.value = 'all';
            selected_city.value = 'all';
            selected_township.value = 'all';
            search = '';
            handleSearchingSorting();
        }

        function handleCategoryfilter(value) {
            selected_cat.value = value
            selected_sub_cat.value = "all"
            subCategories.value = [];
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleSubcategoryfilter(value) {
            selected_sub_cat.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleCityfilter(value) {
            selected_city.value = value
            selected_township.value = "all"
            townships.value=[]
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleTownshipfilter(value) {
            selected_township.value = value
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
            router.get(route('pending_item.index'),
                {
                    sort_field: sort_field.value,
                    sort_order: sort_order.value,
                    page: page ?? props.items.meta.current_page,
                    row: row ?? props.items.meta.per_page,
                    search: search.value,
                    category_filter: selected_cat.value,
                    sub_category_filter: selected_sub_cat.value,
                    city_filter: selected_city.value,
                    township_filter: selected_township.value,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }

        function confirmDeleteClicked(id) {
            ps_danger_dialog.value.openModal(
                trans('core__be_delete_pending_item'),
                trans('core__be_delete_pending_item_info'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    router.delete(route("pending_item.destroy", id), {
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

        let form = useForm(
            {
                status: '',
                "_method": "put"
            }
        )

        function handleStatusChange(id) {
            this.$inertia.put(route('pending_item.statusChange', id), form);
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

        // cities dropdown

        function getCitiesData(offset){

            city_loadmore_visible.value = true;
             is_loading.value = true
            getCities(offset,citySearch.value,props.authUser.id).then(response => {

                if(!response.data.length){
                    city_loadmore_visible.value = false;
                }
                else{
                    response.data.forEach(element =>{
                        cities.value.push(element);
                    });
                }
                    is_loading.value=false;
            });
        }

        function cityDropdownClick(loadMore = null) {
            let offset = cities.value ? cities.value.length : 0 ;
            if(offset == 0 || loadMore == true){
                getCitiesData(offset);
            }
        }
        watch(citySearch,_.debounce((current,previous)=>{
            let offset= 0;
            cities.value = [];
            getCitiesData(offset);

        },500))

        // Township
        function getTownshipData(offset){

            townships_loadmore_visible.value = true;
             is_loading.value = true
            getTownships(offset,townshipsSearch.value,props.authUser.id,selected_city.value).then(response => {

                if(!response.data.length){
                    townships_loadmore_visible.value = false;
                }
                else{
                     response.data.forEach(element =>{
                        townships.value.push(element);
                    });
                }
                    is_loading.value=false;

            });
        }

        function townshipDropdownClick(loadMore = null) {
            let offset = townships.value ? townships.value.length : 0 ;
            if(offset == 0 || loadMore == true){
                getTownshipData(offset);
            }
        }
        watch(townshipsSearch,_.debounce((current,previous)=>{
            let offset= 0;
            townships.value = [];
            getTownshipData(offset);

        },500))


        return {
            handleStatusChange,
            form,
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
            handleCityfilter,
            handleTownshipfilter,
            handleClearFilter,
            handleRow,
            handleSearching,
            selected_cat,
            selected_sub_cat,
            selected_city,
            selected_township,
            dropdownClick,
            categories,
            category_loadmore_visible,
            catSearch,
            subCategoryDropdownClick,
            subCategories,
            subCategory_loadmore_visible,
            subCatSearch,
            is_loading,
            cityDropdownClick,
            cities,
            city_loadmore_visible,
            citySearch,
            townshipDropdownClick,
            townships,
            townships_loadmore_visible,
            townshipsSearch,

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
                    label: trans('pending_item_module'),
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
        handleDetail(id) {
            this.$inertia.get(route('pending_item.edit', id));
        },
        FilterOptionshandle(value) {
            router.put(route('pending_item.screenDisplayUiSetting.store'),
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
})
</script>
