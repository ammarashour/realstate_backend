<template>
    <!-- Global Search start -->
    <new-ps-table-search v-if="!searchHide" :perPageOptions="perPageOptions" :colFilterOptions="colFilterOptions">
        <!-- for search left -->
        <template #searchLeft>
            <slot name="searchLeft" />
        </template>
        <!-- search Right -->
        <template #searchRight>
            <slot name="searchRight" />
            <div  v-if="searchRightable" v-for="(searchOption, index) in searchOptions" :key="index">
                <!-- For Dropdown Start -->
                <ps-dropdown v-if="searchOption.filterType == 'dropdown'"
                    align="left" class="w-56 h-10">
                    <template #select>
                        <ps-dropdown-select
                            :placeholder="searchOption.placeholder"
                            :showCenter="true"
                            :selectedValue="
                                searchOption.checkFieldMain in searchedData &&
                                searchedData[searchOption.checkFieldMain] != ''
                                ? searchOption.options.filter(
                                    (item) =>
                                        item[searchOption.checkFieldOption] ==
                                        searchedData[searchOption.checkFieldMain]
                                    )[0][searchOption.displayField]
                                : ''
                            "
                            />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56">
                            <div class="pt-2 z-30">
                                <div v-if="searchOption.options.length == null">
                                <ps-label class="p-2 flex">No data yet!</ps-label>
                                </div>
                                <div v-else>
                                <div
                                    @click="
                                    [
                                        (clearFilter = false),
                                        (searchedData[searchOption.checkFieldMain] = ''),
                                        'sub_options' in searchOption
                                        ? (searchedData[
                                            searchOption.sub_options.checkFieldMain
                                            ] = '')
                                        : '',
                                        'sub_options' in searchOption
                                        ? (subOptions[searchOption.sub_options.options] =
                                            {})
                                        : '',
                                    ]
                                    "
                                    class="
                                    w-56
                                    flex
                                    py-4
                                    px-2
                                    hover:bg-primary-000
                                    dark:hover:bg-primary-900
                                    cursor-pointer
                                    items-center
                                    "
                                >
                                    <ps-label> {{$t('core__be_all')}} </ps-label>
                                </div>
                                <div
                                    v-for="(option, index) in searchOption.options"
                                    :key="index"
                                    @click="
                                    [
                                        (clearFilter = false),
                                        (searchedData[searchOption.checkFieldMain] =
                                        option[searchOption.checkFieldOption]),
                                        'sub_options' in searchOption
                                        ? (searchedData[
                                            searchOption.sub_options.checkFieldMain
                                            ] = '')
                                        : '',
                                        'sub_options' in searchOption
                                        ? (subOptions[searchOption.sub_options.options] =
                                            option[searchOption.sub_options.options])
                                        : '',
                                    ]
                                    "
                                    class="
                                    w-56
                                    flex
                                    py-4
                                    px-2
                                    hover:bg-primary-000
                                    dark:hover:bg-primary-900
                                    cursor-pointer
                                    items-center
                                    "
                                >
                                    <ps-label>
                                    {{ option[searchOption.displayField] }}
                                    </ps-label>
                                </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </ps-dropdown>
            </div>
            <ps-input-with-right-icon placeholderColor="placeholder-secondary-700 dark:placeholder-white" theme="bg-secondary-100 dark:bg-backgroundDark " class="w-60 h-10 rounded-full flex " v-model:value="searchterm" :placeholder="globalSearchPlaceholder" >
                <template #icon >
                    <ps-icon name="search" class='cursor-pointer' />
                </template>
            </ps-input-with-right-icon>
        </template>
        <!-- search option -->
        <template #searchOption>
            <div class="grid grid-cols-4 gap-2 mt-4" v-show="searchable">
                <div v-for="(searchOption, index) in searchOptions" :key="index">
                    <!-- For Dropdown Start -->
                    <ps-dropdown v-if="searchOption.filterType == 'dropdown'"
                        align="left" class="w-full">
                        <template #select>
                            <ps-dropdown-select
                                :placeholder="searchOption.placeholder"
                                :showCenter="true"
                                :selectedValue="
                                    searchOption.checkFieldMain in searchedData &&
                                    searchedData[searchOption.checkFieldMain] != ''
                                    ? searchOption.options.filter(
                                        (item) =>
                                            item[searchOption.checkFieldOption] ==
                                            searchedData[searchOption.checkFieldMain]
                                        )[0][searchOption.displayField]
                                    : ''
                                "
                                />
                        </template>
                        <template #list>
                            <div class="rounded-md shadow-xs w-56">
                                <div class="pt-2 z-30">
                                    <div v-if="searchOption.options.length == null">
                                    <ps-label class="p-2 flex">No data yet!</ps-label>
                                    </div>
                                    <div v-else>
                                    <div
                                        @click="
                                        [
                                            (clearFilter = false),
                                            (searchedData[searchOption.checkFieldMain] = ''),
                                            'sub_options' in searchOption
                                            ? (searchedData[
                                                searchOption.sub_options.checkFieldMain
                                                ] = '')
                                            : '',
                                            'sub_options' in searchOption
                                            ? (subOptions[searchOption.sub_options.options] =
                                                {})
                                            : '',
                                        ]
                                        "
                                        class="
                                        w-56
                                        flex
                                        py-4
                                        px-2
                                        hover:bg-primary-000
                                        dark:hover:bg-primary-900
                                        cursor-pointer
                                        items-center
                                        "
                                    >
                                        <ps-label> {{$t('core__be_all')}} </ps-label>
                                    </div>
                                    <div
                                        v-for="(option, index) in searchOption.options"
                                        :key="index"
                                        @click="
                                        [
                                            (clearFilter = false),
                                            (searchedData[searchOption.checkFieldMain] =
                                            option[searchOption.checkFieldOption]),
                                            'sub_options' in searchOption
                                            ? (searchedData[
                                                searchOption.sub_options.checkFieldMain
                                                ] = '')
                                            : '',
                                            'sub_options' in searchOption
                                            ? (subOptions[searchOption.sub_options.options] =
                                                option[searchOption.sub_options.options])
                                            : '',
                                        ]
                                        "
                                        class="
                                        w-56
                                        flex
                                        py-4
                                        px-2
                                        hover:bg-primary-000
                                        dark:hover:bg-primary-900
                                        cursor-pointer
                                        items-center
                                        "
                                    >
                                        <ps-label>
                                        {{ option[searchOption.displayField] }}
                                        </ps-label>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </ps-dropdown>
                </div>
            </div>
        </template>
    </new-ps-table-search>
    <!-- Global Search end -->

    <!-- Table start -->
    <div class="overflow-x-auto">
        <table class="table-auto w-full mt-10">
            <thead class="bg-primary-500 text-white dark:text-black text-2xl justify-content" >
                <tr>
                    <th>
                        <ps-label textColor="flex text-secondary-50 dark:text-secondary-900 font-semibold my-2 ms-6">Action</ps-label>
                    </th>
                    <th><ps-label textColor="flex text-secondary-50 dark:text-secondary-900 font-semibold my-2 ms-3">No</ps-label></th>

                    <th v-for="column in columns.filter(col => col.field!='action')" :key="column.field"
                        v-show="colFilterOptions.filter((item) => item.key == column.field).length == 0
                        ? true
                        : !colFilterOptions.filter((item) => item.key == column.field)[0].hidden"
                    >
                        <ps-label textColor="flex text-secondary-50 dark:text-secondary-900 font-semibold my-2 ms-3 items-center">
                            {{ column.label }}
                            <ps-icon v-if="column.action == 'Action'" @click="handleSorting(column)"
                            :name="column.field in sortedData && sortedData[column.field].orderBy == 'asc' ? 'upChervon' : 'downChervon'"
                            class="ms-2 "  w="16" h="16" />
                        </ps-label>
                    </th>

                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-secondaryDark-black dark:divide-white border-b border-t">

                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700"
                    v-for="(row, index) in filteredList" :key="index">
                    <td v-for="(column, index) in columns.filter(col => col.field == 'action')" :key="index" class="py-2 ps-4" v-show="colFilterOptions.filter((item) => item.key == column.field).length == 0
                    ? true
                    : !colFilterOptions.filter((item) => item.key == column.field)[0].hidden ">
                        <slot :field="column.field" :row="row" name="tableActionRow">
                            <ps-label class="font-normal">{{ $t(row[column.field]) }}</ps-label>
                        </slot>
                    </td>

                    <td class="py-2 ps-4">
                        <ps-label>{{ index + 1 + pagination.perPage * (pagination.page - 1) }}</ps-label>
                    </td>

                    <td v-for="(column, index) in columns.filter(col => col.field!='action')" :key="index" class="py-2 ps-4"
                        v-show="colFilterOptions.filter((item) => item.key == column.field).length == 0
                        ? true
                        : !colFilterOptions.filter((item) => item.key == column.field)[0].hidden "
                    >
                        <slot :field="column.field" :row="row" name="tableRow">

                            <ps-label v-if="'relation' in column">
                                {{ row[column.relation] ? row[column.relation][column.relationField] : ""}}
                            </ps-label>
                            <ps-label v-else-if="column.type == 'Date'">
                                {{ row[column.field] ? moment(row[column.field]).format($page.props.dateFormat) : '' }}
                            </ps-label>
                            <ps-label v-else class="font-normal">{{ row[column.field] }}</ps-label>
                        </slot>
                    </td>
                </tr>

                <!-- if empty data, show this row -->
                <tr v-if="filteredList.length == 0">
                    <td class="pb-2 pt-4 px-4 text-sm font-medium whitespace-nowrap dark:text-slate-500 text-slate-400 text-center border-b border-t"
                        colspan="20">
                        <slot name="emptyRow">{{ $t('core__be_table_no_data') }}</slot>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
    <!-- Table end -->
    <ps-pagination v-show="rows.length > perPageOptions[0]"  v-model:value="pagination" :totalRecords="totalRecords" :perPageOptions="perPageOptions" />
</template>

<script>
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsTextButton from "@/Components/Core/Buttons/PsTextButton.vue";
import NewPsTableSearch from "@/Components/Core/Table/NewPsTableSearch.vue";
import PsPagination from "@/Components/Core/Pagination/PsPagination.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsInputWithRightIcon from '@/Components/Core/Input/PsInputWithRightIcon.vue';
import moment from 'moment';

export default {
    name: "NewPsDataTable",
    components: {
        PsLabel,
        PsIcon,
        PsTextButton,
        NewPsTableSearch,
        PsPagination,
        PsDropdown,
        PsDropdownSelect,
        PsInputWithRightIcon
    },
    props: {
        'columns': { type: Array, default: [] },
        'rows': { type: Array, default: [] },
        'perPageOptions': { type: Array, default: [10, 25, 50, 100] },
        'sortable': { type: Boolean, default: true },
        'colFilterOptions': { type: Array, default: [] },
        'searchable': { type: Boolean, default: false },
        'searchRightable': { type: Boolean, default: false },
        'searchOptions': { type: Array, default: [] },
        'globalSearchFields': { type: Array, default: [] },
        'globalSearchPlaceholder': { type: String, default: "Search" },
        'searchHide': { type: Boolean, default: false },
        'searchRightHide': { type: Boolean, default: false },
    },
    data() {
        return {
            searchterm: "",
            selectedValue: 0,
            searchedData: {},
            filterOptions: this.searchOptions,
            sortedData: {},
            tableData: this.rows,
            globalSearch: this.globalSearchFields,
            pagination: { page: 1, perPage: this.perPageOptions[0] },
            totalRecords: this.rows.length,
            moment: moment,
            clearFilter: false,
            sortType: "desc",
        }
    },
    computed: {
        // used for table data binding
        filteredList() {
            if (this.clearFilter == true) {
                this.searchedData = {};
            }
            this.tableData = this.rows;

            // filter by global search
            if (this.searchterm) {
                this.tableData = this.tableData.filter((row) => {
                    let matched = false;
                    for (let i = 0; i < this.globalSearch.length; i++) {
                        if (
                        row[this.globalSearch[i]]
                            .toLowerCase()
                            .replace(/\s+/g, " ")
                            .trim()
                            .includes(
                            this.searchterm.toLowerCase().replace(/\s+/g, " ").trim()
                            )
                        ) {
                        matched = true;
                        break;
                        } else {
                        matched = false;
                        }
                    }

                    if (matched) {
                        return true;
                    } else {
                        return false;
                    }
                });
            }

            // filter by custom search
            if (this.searchedData) {
                for (let searchOption of this.searchOptions) {
                    // filter by dropdown
                    if (searchOption.filterType == "dropdown") {
                        if (searchOption.checkFieldMain in this.searchedData) {
                            if (
                                searchOption.checkFieldMain != "" &&
                                searchOption.checkFieldMain != 0 &&
                                this.searchedData[searchOption.checkFieldMain] != "" &&
                                this.searchedData[searchOption.checkFieldMain] != 0
                            ) {
                                this.tableData = this.tableData.filter((row) => {
                                    return (
                                        row[searchOption.checkFieldMain] ==
                                        this.searchedData[searchOption.checkFieldMain]
                                    );
                                });
                            }
                        }
                    }
                }
            }

            // sorting
            if (this.sortable == true) {
                if (this.sortedData == undefined) {
                    // for default sorting
                } else {
                    let length = this.columns.length;
                    for (let i = length - 1; i >= 0; i--) {
                        let column = this.columns[i];

                        if (column.field in this.sortedData) {
                            if (this.sortedData[column.field].type == "String") {

                                if ("relation" in column) {
                                    this.tableData = this.tableData.sort((a, b) => {
                                        let fa = a[column.relation][column.relationField].toLowerCase(),
                                        fb = b[column.relation][column.relationField].toLowerCase();

                                        if (fa < fb) return -1;
                                        if (fa > fb) return 1;
                                        return 0;
                                    });
                                } else {
                                    this.tableData = this.tableData.sort((a, b) => {
                                        let fa = a[column.field].toLowerCase(),
                                        fb = b[column.field].toLowerCase();

                                        if (fa < fb) return -1;
                                        if (fa > fb) return 1;
                                        return 0;
                                    });
                                }

                                if (this.sortedData[column.field].orderBy == "desc") {
                                    this.tableData = this.tableData.reverse();
                                }
                            }

                            if (this.sortedData[column.field].type == "Integer" || this.sortedData[column.field].type == "Boolean") {
                                this.tableData = this.tableData.sort((a, b) => {
                                return a[column.field] - b[column.field];
                                });

                                if (this.sortedData[column.field].orderBy == "desc") {
                                this.tableData = this.tableData.reverse();
                                }
                            }

                            if (this.sortedData[column.field].type == "Date") {
                                this.tableData = this.tableData.sort((a, b) => {
                                    return (
                                        new Date(a[column.field]).getTime() -
                                        new Date(b[column.field]).getTime()
                                    );
                                });

                                if (this.sortType == "desc") {
                                    this.tableData = this.tableData.reverse();
                                }
                            }
                        }
                    }
                }
            }

            // update total records
            this.totalRecords = this.tableData.length;
            // for paginate table data
            const firstIndex = (this.pagination.page - 1) * this.pagination.perPage;
            const lastIndex = this.pagination.page * this.pagination.perPage;
            // for paginate table data
            return this.tableData.slice(firstIndex, lastIndex);

        }
    },
    methods: {
        handleSorting(column){
            let data = {};
            let field = column.field;
            let type = column.type;
            if(this.sortedData == undefined){
                data = {orderBy: 'asc', type: type}
            }else if(field in this.sortedData){
                if(this.sortedData[field]['orderBy'] == 'asc'){
                    data = {orderBy: 'desc', type: type}
                }else{
                    data = {orderBy: 'asc', type: type}
                }
            }else{
                data = {orderBy: 'asc', type: type}
            }
            this.sortedData = {};  // if use multi sorting, this is commented
            this.sortedData[field] = data;
        },
    }
}
</script>
