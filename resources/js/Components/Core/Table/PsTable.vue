<template>
  <ps-paginator
    v-show="rows.length > perPageOptions[0]"
    :totalRecords="totalRecords"
    :perPageOptions="perPageOptions"
    v-model:value="pagination"
    class="bg-gray-100 dark:bg-gray-700"
  >
    <!-- import csv -->
    <template #searchLeft>
      <slot name="import" />
    </template>

    <!-- search -->
    <template #search>
      <div class="flex flex-row gap-2">
        <ps-button
          v-if="searchable && Object.keys(searchedData).length > 0"
          @click="clearFilter = true"
          type="button"
          colors="text-red-400"
          focus=""
          hover="hover:text-red-500"
          padding="px-2 py-1.5"
          class="mb-0.5 items-center"
        >
          <ps-icon name="cross" class="me-1  font-semibold" /> {{ $t('core__be_clear_filter') }}</ps-button>

        <slot name="searchOptionFromStart">
          <template v-for="(searchOption, index) in searchOptions" :key="index">
            <!-- For Dropdown Start -->
            <div v-if="searchOption.filterType == 'dropdown'">
              <ps-dropdown align="left" class="w-56">
                <template #select>
                  <ps-dropdown-select
                    :placeholder="searchOption.placeholder"
                    :showCenter="true"
                    :selectedValue="
                      searchOption.checkFieldMain in searchedData
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
                                ? (subOptions[
                                    searchOption.sub_options.options
                                  ] = {})
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
                          <ps-label> {{ $t('core__be_all') }} </ps-label>
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
                                ? (subOptions[
                                    searchOption.sub_options.options
                                  ] = option[searchOption.sub_options.options])
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
            <!-- For Dropdown End -->

            <!-- For Dropdown Sub Option Start -->
            <div
              v-if="
                searchOption.filterType == 'dropdown' &&
                searchOption.sub_options
              "
            >
              <ps-dropdown
                v-if="searchOption['sub_options'] !== 'undefined'"
                align="left"
                class="w-full lg:mt-2 mt-1"
              >
                <template #select>
                  <ps-dropdown-select
                    :placeholder="searchOption.sub_options.placeholder"
                    :showCenter="true"
                    :selectedValue="
                      searchOption.sub_options.checkFieldMain in searchedData &&
                      searchedData[searchOption.sub_options.checkFieldMain] !=
                        '' &&
                      searchedData[searchOption.sub_options.checkFieldMain] != 0
                        ? subOptions[searchOption.sub_options.options].filter(
                            (item) =>
                              item[searchOption.sub_options.checkFieldOption] ==
                              searchedData[
                                searchOption.sub_options.checkFieldMain
                              ]
                          )[0][searchOption.sub_options.displayField]
                        : ''
                    "
                  />
                </template>
                <template #list>
                  <div class="rounded-md shadow-xs w-56">
                    <div class="pt-2 z-30">
                      <div
                        v-if="!(searchOption.sub_options.options in subOptions)"
                      >
                        <ps-label class="p-2 flex"
                          >Please filter category first</ps-label
                        >
                      </div>
                      <div
                        v-else-if="
                          subOptions[searchOption.sub_options.options].length ==
                          0
                        "
                      >
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
                                ? (subOptions[
                                    searchOption.sub_options.options
                                  ] = {})
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
                          v-for="(sub_option, index) in subOptions[
                            searchOption.sub_options.options
                          ]"
                          :key="index"
                          @click="
                            (clearFilter = false),
                              (searchedData[
                                searchOption.sub_options.checkFieldMain
                              ] =
                                sub_option[
                                  searchOption.sub_options.checkFieldOption
                                ])
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
                            {{
                              sub_option[searchOption.sub_options.displayField]
                            }}
                          </ps-label>
                        </div>
                      </div>
                    </div>
                  </div>
                </template>
              </ps-dropdown>
            </div>
            <!-- For Dropdown Sub Option End -->

            <!-- For Date Range Start -->
            <div v-if="searchOption.filterType == 'date_range'">
              <datepicker
                v-model="searchedData[searchOption.field]"
                range
                :enableTimePicker="false"
                :placeholder="searchOption.placeholder"
                @click="clearFilter = false"
              />
            </div>
            <!-- For Date Range End -->
          </template>
        </slot>

        <!-- For global search start -->
        <ps-input-with-right-icon
          theme="bg-secondary-100 text-secondary-800 border-0"
          v-model:value="searchterm"
          class="pb-0.5"
          :placeholder="globalSearchPlaceholder"
        >
          <template #icon>
            <ps-icon name="search" />
          </template>
        </ps-input-with-right-icon>
        <!-- For global search end -->

        <slot name="searchOptionFromEnd" />
      </div>
    </template>

    <!-- search option -->
    <template #searchOption>
      <div class="grid grid-cols-4 gap-2" v-show="searchable">
        <template v-for="(searchOption, index) in searchOptions" :key="index">
          <!-- For Dropdown Start -->
          <div v-if="searchOption.filterType == 'dropdown'">
            <ps-dropdown align="left" class="w-full lg:mt-2 mt-1">
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
          <!-- For Dropdown End -->

          <!-- For Dropdown Sub Option Start -->
          <div
            v-if="
              searchOption.filterType == 'dropdown' && searchOption.sub_options
            "
          >
            <ps-dropdown
              v-if="searchOption['sub_options'] !== 'undefined'"
              align="left"
              class="w-full lg:mt-2 mt-1"
            >
              <template #select>
                <ps-dropdown-select
                  :placeholder="searchOption.sub_options.placeholder"
                  :showCenter="true"
                  :selectedValue="
                    searchOption.sub_options.checkFieldMain in searchedData &&
                    searchedData[searchOption.sub_options.checkFieldMain] !=
                      '' &&
                    searchedData[searchOption.sub_options.checkFieldMain] != 0
                      ? subOptions[searchOption.sub_options.options].filter(
                          (item) =>
                            item[searchOption.sub_options.checkFieldOption] ==
                            searchedData[
                              searchOption.sub_options.checkFieldMain
                            ]
                        )[0][searchOption.sub_options.displayField]
                      : ''
                  "
                />
              </template>
              <template #list>
                <div class="rounded-md shadow-xs w-56">
                  <div class="pt-2 z-30">
                    <div
                      v-if="!(searchOption.sub_options.options in subOptions)"
                    >
                      <ps-label class="p-2 flex"
                        >Please filter category first</ps-label
                      >
                    </div>
                    <div
                      v-else-if="
                        subOptions[searchOption.sub_options.options].length == 0
                      "
                    >
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
                        v-for="(sub_option, index) in subOptions[
                          searchOption.sub_options.options
                        ]"
                        :key="index"
                        @click="
                          (clearFilter = false),
                            (searchedData[
                              searchOption.sub_options.checkFieldMain
                            ] =
                              sub_option[
                                searchOption.sub_options.checkFieldOption
                              ])
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
                          {{
                            sub_option[searchOption.sub_options.displayField]
                          }}
                        </ps-label>
                      </div>
                    </div>
                  </div>
                </div>
              </template>
            </ps-dropdown>
          </div>
          <!-- For Dropdown Sub Option End -->

          <!-- For Date Range Start -->
          <div v-if="searchOption.filterType == 'date_range'">
            <datepicker
              v-model="searchedData[searchOption.field]"
              range
              :enableTimePicker="false"
              :placeholder="searchOption.placeholder"
              class="mt-2 bg-green-300"
            />
          </div>
          <!-- For Date Range End -->
        </template>
      </div>
    </template>

    <!-- Table start -->
    <template #table>
      <table class="table-auto w-full">
        <thead
          class="
            bg-primary-500
            dark:bg-primary-100
            text-white
            dark:text-black
            text-2xl
          "
        >
          <tr>

            <!-- for checkbox select all -->
            <th scope="col" v-show="checkable">
              <div class="flex items-center">
                <input
                  id="checkbox-all"
                  type="checkbox"
                  :value="filteredList"
                  :checked="isCheckedAll"
                  @change="(isCheckedAll = !isCheckedAll), onSelectedRows"
                  class="
                    w-4
                    h-4
                    py-2 px-4
                    text-blue-600
                    bg-gray-100
                    rounded
                    border-gray-300
                    focus:ring-blue-500
                    dark:focus:ring-blue-600 dark:ring-offset-gray-800
                    focus:ring-2
                    dark:bg-gray-700 dark:border-gray-600
                  "
                />
                <label for="checkbox-all" class="sr-only">checkbox</label>
              </div>
            </th>

            <!-- for number col -->
            <th
              scope="col"
              class="py-2 px-4 text-sm font-medium flex w-auto"
              v-show="showLineNumber"
            >
              <slot name="numberCol">No</slot>
            </th>

            <!-- for col header  -->
            <th
              scope="col"
              v-for="(column, index) in columns"
              :key="index"
              class="pt-2 px-4 text-sm font-medium whitespace-nowrap"
              v-show="
                colFilterOptions.filter((item) => item.key == column.field)
                  .length == 0
                  ? true
                  : !colFilterOptions.filter(
                      (item) => item.key == column.field
                    )[0].hidden
              "
              :class="[column.width, column.align==null ? column.type == 'Action'? 'justify-center text-center': 'justify-left text-left': column.align]"
            >
              <div class="flex gap-2"
              :class="[column.align==null ? column.type == 'Action'? 'justify-center text-center': 'justify-left text-left': column.align]">
                <ps-label textColor="text-white" class="mb-2">{{
                  column.label
                }}</ps-label>
                <ps-icon
                  @click="handleSorting(column)"
                  class="cursor-pointer mt-2"
                  v-if="sortable && column.field != 'action'"
                  :name="
                    column.field in sortedData &&
                    sortedData[column.field].orderBy == 'asc'
                      ? 'upChervon'
                      : 'downChervon'
                  "
                />
              </div>
            </th>
          </tr>
        </thead>
        <tbody
          class="
            bg-white
            divide-y divide-gray-200
            dark:bg-gray-800 dark:divide-gray-700
            border-b
          "
        >
          <tr
            class="hover:bg-gray-100 dark:hover:bg-gray-700"
            v-for="(row, index) in filteredList"
            :key="index"
          >
            <!-- for checkable -->
            <td class="py-2 px-4 w-4" v-show="checkable">
              <div class="flex items-center">
                <input
                  type="checkbox"
                  :value="row"
                  v-model="selectedItems"
                  :checked="isChecked"
                  class="
                    w-4
                    h-4
                    text-blue-600
                    bg-gray-100
                    rounded
                    border-gray-300
                    focus:ring-blue-500
                    dark:focus:ring-blue-600 dark:ring-offset-gray-800
                    focus:ring-2
                    dark:bg-gray-700 dark:border-gray-600
                  "
                />
                <label for="checkbox-table-1" class="sr-only">checkbox</label>
              </div>
            </td>

            <!-- for line number -->
            <td
              class="
                pb-2 pt-4 px-4
                text-gray-900
                whitespace-nowrap
                dark:text-white
                text-left
                w-auto
              "
              v-show="showLineNumber"
            >
              {{ index + 1 + pagination.perPage * (pagination.page - 1) }}
            </td>

            <!-- for row data -->
            <td
              v-for="(column, index) in columns"
              :key="index"
              class="
                pb-2 pt-4 px-4
                text-gray-900
                whitespace-nowrap
                dark:text-white
              "
              v-show="
                colFilterOptions.filter((item) => item.key == column.field)
                  .length == 0
                  ? true
                  : !colFilterOptions.filter(
                      (item) => item.key == column.field
                    )[0].hidden
              "
              :class="[ column.width, column.align==null ? column.type == 'Action'? 'justify-center text-center': 'justify-left text-left': column.align]"
            >
              <slot :field="column.field" :row="row" name="tableRow">
                <span v-if="'relation' in column">{{
                  row[column.relation]
                    ? row[column.relation][column.relationField]
                    : ""
                }}</span>
                <span v-else-if="column.type == 'Date'">{{
                  row[column.field] ? moment(row[column.field]).format($page.props.dateFormat) : ''
                }}</span>
                <span v-else>{{ row[column.field] }}</span>
              </slot>
            </td>
          </tr>

          <!-- if empty data, show this row -->
          <tr v-if="filteredList.length == 0">
            <td
              class="
                pb-2 pt-4 px-4
                text-sm
                font-medium
                whitespace-nowrap
                dark:text-slate-500
                text-slate-400 text-center
              "
              colspan="20"
            >
              <slot name="emptyRow">{{ $t('core__be_table_no_data') }}</slot>
            </td>
          </tr>
        </tbody>
      </table>
    </template>
    <!-- Table end -->
  </ps-paginator>
</template>

<script>
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsPaginator from "@/Components/Core/Pagination/PsPaginator.vue";
import PsSelect from "@/Components/Core/Select/PsSelect.vue";

import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsInputWithRightIcon from '@/Components/Core/Input/PsInputWithRightIcon.vue';
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsIcon1 from "@/Components/Core/Icons/PsIcon1.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsIconButton from "@/Components/Core/Buttons/PsIconButton.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import moment from "moment";

export default {
  name: "PsTable",
  components: {
    PsInput,
    PsSelect,
    PsPaginator,
    Datepicker,
    PsDropdown,
    PsDropdownSelect,
    PsInputWithRightIcon,
    PsIcon,
    PsIcon1,
    PsLabel,
    PsIconButton,
    PsButton,
  },
  props: {
    columns: { type: Array, default: [] },
    rows: { type: Array, default: [] },
    showLineNumber: { type: Boolean, default: true },
    perPageOptions: { type: Array, default: [10, 20, 50, 100] },

    checkable: { type: Boolean, default: false },
    selectedTableData: { type: Array, default: [] },

    searchable: { type: Boolean, default: false },
    globalSearchFields: { type: Array, default: [] },
    globalSearchPlaceholder: { type: String, default: "Search" },
    searchOptions: { type: Array, default: [] },

    colFilterOptions: { type: Array, default: [] },

    sortable: { type: Boolean, default: true },
  },
  data() {
    return {
      searchterm: "",
      selectedValue: 0,
      date: "",
      tableData: this.rows,
      globalSearch: this.globalSearchFields,
      totalRecords: this.rows.length,
      pagination: { page: 1, perPage: this.perPageOptions[0] },
      sortMethod: "",
      sortField: "",
      filterOptions: this.searchOptions,
      sortType: "desc",
      isCheckedAll: false,
      clearFilter: false,
      selectedItems: this.selectedTableData,
      searchedData: {},
      subOptions: {},
      sortedData: {},
      moment: moment,
    };
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

          // filter by dropdown for sub_options
          if (
            searchOption.filterType == "dropdown" &&
            "sub_options" in searchOption
          ) {
            if (searchOption.sub_options.checkFieldMain in this.searchedData) {
              if (
                this.searchedData[searchOption.sub_options.checkFieldMain] !=
                  "" &&
                this.searchedData[searchOption.sub_options.checkFieldMain] !==
                  0 &&
                this.searchedData[searchOption.checkFieldMain] != "" &&
                this.searchedData[searchOption.checkFieldMain] != 0
              ) {
                this.tableData = this.tableData.filter((row) => {
                  return (
                    row[searchOption.sub_options.checkFieldMain] ==
                    this.searchedData[searchOption.sub_options.checkFieldMain]
                  );
                });
              }
            }
          }

          // filter by date range
          if (searchOption.filterType == "date_range") {
            if (searchOption.field in this.searchedData) {
              if (
                searchOption.checkFieldMain != "" &&
                searchOption.checkFieldMain != 0 &&
                this.searchedData[searchOption.checkFieldMain] != "" &&
                this.searchedData[searchOption.checkFieldMain] != 0
              ) {
                const startDate = this.searchedData[searchOption.field][0];
                const endDate = this.searchedData[searchOption.field][1];

                this.tableData = this.tableData.filter((row) => {
                  const itemDate = new Date(row[searchOption.field]);

                  if (startDate != null && endDate != null) {
                    return startDate <= itemDate && itemDate <= endDate;
                  } else if (startDate != null && endDate == null) {
                    return startDate <= itemDate;
                  } else if (startDate == null && endDate != null) {
                    return itemDate <= endDate;
                  }

                  return true;
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
                    let fa =
                        a[column.relation][column.relationField].toLowerCase(),
                      fb =
                        b[column.relation][column.relationField].toLowerCase();

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

              if (
                this.sortedData[column.field].type == "Integer" ||
                this.sortedData[column.field].type == "Boolean"
              ) {
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

      return this.tableData.slice(firstIndex, lastIndex);
    },

    // used for checkbox option
    isChecked(v) {
      let isSelected = false;

      // to control select all checkbox flag
      let filteredData = this.filteredList;
      let checkedAll = false;

      if (this.selectedItems != null) {
        if (filteredData.length == this.selectedItems.length) {
          for (let j = 0; j < filteredData.length; j++) {
            for (let i = 0; i < this.selectedItems.length; i++) {
              if (this.selectedItems[i].id == filteredData[j].id) {
                checkedAll = true;
                continue;
              }
            }
            if (checkedAll == false) {
              break;
            }
          }
        }
      }

      this.isCheckedAll = checkedAll;

      if (this.selectedItems != null) {
        for (let i = 0; i < this.selectedItems.length; i++) {
          if (this.selectedItems[i].id == v.id) {
            isSelected = true;
            break;
          }
        }
      }
      this.$emit("update:selectedTableData", this.selectedItems);

      return isSelected;
    },

    // used for checkbox option
    onSelectedRows() {
      if (this.isCheckedAll) {
        this.selectedItems = this.filteredList;
      } else {
        this.selectedItems = [];
      }

      this.$emit("update:selectedTableData", {
        selectedRows: this.selectedItems,
      });
    },
  },
  methods: {
    handleSorting(column) {
      let data = {};
      let field = column.field;
      let type = column.type;
      if (this.sortedData == undefined) {
        data = { orderBy: "asc", type: type };
      } else if (field in this.sortedData) {
        if (this.sortedData[field]["orderBy"] == "asc") {
          data = { orderBy: "desc", type: type };
        } else {
          data = { orderBy: "asc", type: type };
        }
      } else {
        data = { orderBy: "asc", type: type };
      }
      this.sortedData = {}; // if use multi sorting, this is commented
      this.sortedData[field] = data;
    },
  },
};
</script>
