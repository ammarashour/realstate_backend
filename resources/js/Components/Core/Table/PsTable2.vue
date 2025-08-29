<template>
    <div class="flex-wrap justify-end hidden md:flex">
       <slot name="button"/>
    </div>
    <div class="flex flex-col mt-4 md:justify-between lg:mt-10 md:flex-row">

        <!-- for per page options dropdown -->
        <div class="flex flex-row items-end justify-end ">
            <div class="flex items-center md:me-2">
                <ps-dropdown v-if="defaultRowFilter == true" align="right" class="w-full ">
                    <template #select>
                        <div class="flex flex-row">
                            <ps-label class="my-auto me-2" >{{$t('ps_table_2__show')}}:&nbsp;</ps-label>
                            <ps-dropdown-select :placeholder="$t('core__be_select_row')"
                                :selectedValue="(perpage == '')? '' : perPageOptions.filter(option=>option == perpage )[0] + ' rows'"
                                class="w-full h-10 light:font-bold md:w-29"/>
                        </div>
                    </template>
                    <template #list>
                        <div class="w-24 rounded-md shadow-xs ">
                            <div class="z-30 ">
                                    <div v-for="(option, index) in perPageOptions" :key="index"
                                        class="flex items-center w-32 px-2 py-4 cursor-pointer hover:bg-primary-000 dark:hover:bg-primary-900"
                                        @click="rowPage(option)">
                                        <ps-label class="ms-2"
                                            :class="perpage == option ? ' font-bold' : ''">
                                            {{ option }} {{ $t('core__be_table_rows') }} </ps-label>
                                    </div>
                                </div>
                        </div>
                    </template>
                </ps-dropdown>
            </div>

            <div class="flex-row hidden md:flex">
                <slot name="searchLeft" />
            </div>
        </div>
        <div class="flex justify-between">
            <div class="flex flex-row my-4 md:hidden">
                <slot name="searchLeft" />
            </div>
            <div class="flex flex-wrap justify-end visible my-4 md:hidden">
                <slot name="responsive_button"/>
            </div>
        </div>

        <!-- end per page options -->
        <div class="flex flex-col gap-2 md:flex-row">
            <!-- For global search start -->
            <div class="hidden md:flex">
                <slot name="searchRight" />
            </div>
            <div class="flex flex-row justify-end gap-2">
                <ps-input-with-right-icon v-if="defaultSearch == true" class="w-full h-10 rounded-lg" v-model:value="psSearch" :placeholder="$t('core__be_search')" >
                    <template #icon >
                        <ps-icon v-if="psSearch == ''" name="search" class='mt-1 cursor-pointer' />
                        <ps-icon v-else name="close" class='mt-1 cursor-pointer' @click="psSearch = ''"/>
                    </template>
                </ps-input-with-right-icon>
                <new-dropdown v-if="eye_filter == true" align="left" 
                v-model:colFilterOptions="psColFilterOptions" @changeFilter="changeFilter">
                    <template #select>
                        <div class="relative flex items-center justify-center w-10 h-10 rounded bg-primary-500">
                            <ps-icon name="eye-on" class='text-white cursor-pointer dark:text-secondaryDark-black' />
                        </div>
                    </template>
                </new-dropdown>
            </div>
            <div class="flex justify-between mt-4 md:hidden">
                <slot name="searchRight" />
            </div>
        </div>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-[22px] mb-6" v-show="searchable">
        <!-- For Dropdown Start -->
        <slot name="Filter"/>
    </div>
    <div>
        <slot name = "tags"/>
    </div>
    
    <!-- Table start -->
    <div class="overflow-x-auto">
        <table :class="['table-auto w-full', defaultMt, sm, lg]">
            <thead class="text-2xl text-white bg-primary-500 dark:text-black justify-content" >
                <tr>
                    <th class="w-[100px]" v-for="column in columns.filter(col => col.field=='action')" :key="column.field">
                        <ps-label textColor="flex text-secondary-50 dark:text-secondary-900 font-semibold my-2 ms-6">{{$t(actionColName)}}</ps-label>
                    </th>
                    <th class="w-[100px]"><ps-label textColor="flex text-secondary-50 dark:text-secondary-900 font-semibold my-2 ms-3">{{$t('ps_table_2__no')}}</ps-label></th>

                    <th v-for="column in columns.filter(col => col.field!='action')" :key="column.field"
                        v-show="psColFilterOptions.filter((item) => item.key == column.field).length == 0
                            ? true
                            : !psColFilterOptions.filter((item) => item.key == column.field)[0].hidden"
                        class="truncate" >

                        <ps-label textColor="flex text-secondary-50 dark:text-secondary-900 font-semibold my-2 ms-3 items-center  ">
                        {{ $t(column.label ) }}  
                            <slot :field="column.field" :row="row" name="sorting" >
                                <div>
                                    <ps-icon v-if="column.sort != false"  @click="handleSort(column.field,sort_order)"
                                        :name="sort_field == column.field ? sort_order == 'desc' ? 'downChervon': 'upChervon' : 'downChervon'" class="cursor-pointer ms-2 "  w="16" h="16" />
                                </div>
                            </slot>
                        </ps-label>
                    </th>
                </tr>
            </thead>

            <tbody class="border-t border-b divide-y bg-secondary-50 divide-secondary-200 dark:bg-secondaryDark-black dark:divide-secondary-100">
                <tr class="hover:bg-secondary-100 dark:hover:bg-gray-700 "
                    v-for="(row, index) in object.data" :key="index">
                    <td v-for="(column, index) in columns.filter(col => col.field == 'action')" :key="index" class="items-center justify-center h-11 ps-4" v-show="psColFilterOptions.filter((item) => item.key == column.field).length == 0
                    ? true : !psColFilterOptions.filter((item) => item.key == column.field)[0].hidden ">
                        <slot :field="column.field" :row="row" name="tableActionRow">
                            <ps-label class="font-normal">{{ $t(row[column.field]) }}</ps-label>
                        </slot>
                    </td>
                    <td class="items-center justify-center h-11 ps-4">
                        <ps-label >{{(object.meta.current_page * object.meta.per_page)-object.meta.per_page + index + 1}}</ps-label>
                    </td>

                    <td v-for="(column, index) in columns.filter(col => col.field!='action')" :key="index" class="items-center justify-center max-w-xs truncate h-11 ps-4"
                        v-show="psColFilterOptions.filter((item) => item.key == column.field).length == 0
                            ? true : !psColFilterOptions.filter((item) => item.key == column.field)[0].hidden ">
                        <slot :field="column.field" :type="column.type" :row="row" name="tableRow">
                            <ps-label v-if="'relation' in column">
                                {{ row[column.relation] ? row[column.relation][column.relationField] : ""}}
                            </ps-label>
                            <ps-label v-else-if="column.type == 'Timestamp'">
                                {{ row[column.field] ? moment(row[column.field]).format($page.props.dateFormat) : '' }}
                            </ps-label>
                            <ps-label v-else-if="column.type == 'Image'">
                                <img class="h-16 rounded w-28" width="50" height="50" :src="($page.props.uploadUrl + '/' + row[column.field])" alt="Image">
                            </ps-label>
                            <ps-label v-else-if="column.type == 'MultiSelect'">
                                <div v-if="row[column.field]" class="flex">
                                    <div v-for="(id, index) in row[column.field].split(',')" :key="index">
                                        <div v-for="attribute in customizeDetails[column.field]" :key=attribute.id>
                                            <p v-if="id == attribute.id" class="px-1 py-0.25 me-1 rounded bg-primary-500 text-white dark:text-textDark">
                                                {{ attribute.name }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </ps-label>
                            <ps-label v-else class="overflow-hidden font-normal text-ellipsis">{{ row[column.field] }}</ps-label>
                        </slot>
                    </td>
                </tr>

            
                <tr v-if="object.data.length == 0">
                    <td class="px-4 pt-4 pb-2 text-sm font-medium text-center border-t border-b whitespace-nowrap dark:text-slate-500 text-slate-400"
                        colspan="20">
                        <slot name="emptyRow">{{ $t('core__be_table_no_data') }}</slot>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
    <!-- Table end -->
    <div class="flex flex-col items-center justify-between mt-5 sm:flex-row sm:mt-6 lg:mt-8">
            <ps-label class="text-base font-normal text-secondary-600 " >
                {{ $t('core__be_table_showing') }}
                {{ " " }}
                <span class="font-medium">{{ object.meta.total > 0 ? ( object.meta.current_page - 1) *  object.meta.per_page + 1 : 0 }}</span>
                {{ " " }}
                {{ $t('core__be_table_to') }}
                {{ " " }}
                <span class="font-medium">{{
                        object.meta.current_page * object.meta.per_page > object.meta.total ? object.meta.total :  object.meta.current_page * object.meta.per_page
                }}</span>
                {{ " " }}
                {{ $t('core__be_table_of') }}
                {{ " " }}
                <span class="font-medium">{{ object.meta.total }}</span>
                {{ " " }}
                {{ $t('core__be_table_entries') }}
            </ps-label>

            <div class="flex justify-center space-x-1 align-middle sm:mt-4 rtl:space-x-reverse" v-if="object.meta.total > object.meta.per_page " >
                <Link v-for="(link,index) in object.meta.links" :key="index" :href="link.url ? link.url : ''" class="h-8 bg-white rounded dark:bg-secondaryDark-black">
                    <div class="flex">
                        <ps-button v-if="index == 0"  hover="" focus=""
                            colors="bg-background dark:bg-backgroundDark hover:bg-secondary-100" class="mt-0.5 "
                            padding="py-2 px-2"
                            border="border border-secodnary-200 dark:border-secodnary-100 ">
                            <ps-icon class="block rtl:hidden" name="doubleArrowLeft" w="16" h="16"  />
                            <ps-icon class="hidden rtl:block" name="doubleArrowRight" w="16" h="16"  />
                        </ps-button>

                        <ps-button v-else-if="index == Object.keys(object.meta.links).length -1"
                            colors="bg-background dark:bg-backgroundDark hover:bg-secondary-100" class="mt-0.5 "
                            padding="py-2 px-2"  hover="" focus=""
                            border="border border-secodnary-200  dark:border-secodnary-100">
                            <ps-icon class="block rtl:hidden" name="doubleArrowRight" w="16" h="16"  />
                            <ps-icon class="hidden rtl:block" name="doubleArrowLeft" w="16" h="16"  />
                        </ps-button>

                        <ps-button v-else rounded="rounded" :border="link.active ? 'border border-primary-500 rounded' : 'border border-secondary-200  dark:border-secodnary-100 rounded'"
                            :colors="link.active ? 'bg-primary-500 dark:bg-primary-500 text-white dark:text-textDark' : 'bg-background dark:bg-backgroundDark hover:bg-secondary-100 dark:hover-bg-primary-600'" hover=""
                            padding='py-2 px-4'
                            >
                            {{link.label}}
                        </ps-button>
                    </div>
                </Link>
            </div>
        </div>
</template>

<script>
import { defineComponent,reactive,ref,watch} from 'vue'
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsTextButton from "@/Components/Core/Buttons/PsTextButton.vue";
import PsTableSearch from "@/Components/Core/Table/PsTableSearch.vue";
import PsPagination from "@/Components/Core/Pagination/PsPagination.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsInputWithRightIcon from '@/Components/Core/Input/PsInputWithRightIcon.vue';
import moment from 'moment';
import { Head,Link, useForm  } from '@inertiajs/vue3';
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import Dropdown from "@/Components/Core/DropdownModal/Dropdown.vue";
import debounce from 'lodash/debounce';
import NewDropdown from "@/Components/Core/DropdownModal/NewDropdown.vue";
import { trans } from 'laravel-vue-i18n';

export default {
    name: "PsDataTable",
    components: {
        PsLabel,
        PsIcon,
        PsTextButton,
        PsTableSearch,
        PsPagination,
        PsDropdown,
        PsDropdownSelect,
        PsInputWithRightIcon,
        Link,
        PsButton,
        Dropdown,
        debounce,
        NewDropdown,
    },
    props: {
        'test' : {tye: Object, default: []},
        'columns': { type: Array, default: [] },
        'object': { type: Array, default: [] },
        'colFilterOptions': { type: Object, default: [] },
        'sort_field':{type:String,default:""},
        'sort_order':{type:String,default:""},
        'perPageOptions': { type: Array, default: [10, 20, 50, 100] },
        "row":{type:String},
        'search':{type:String,default:''},
        'globalSearchPlaceholder':{type:String,default: trans('core__be_search')},
        'eye_filter':{type:Boolean,default:true},
        'searchRightable': { type: Boolean, default: false },
        'searchable': { type: Boolean, default: false },
        'searchOptions': { type: Array, default: [] },
        'defaultSearch':{type:Boolean,default:true},
        'defaultRowFilter':{type:Boolean,default:true},
        'defaultMt': {type: String, default: 'mt-2'},
        'sm': {type: String, default: 'sm:mt-2'},
        'lg': {type: String, default: 'lg:mt-5'},
        'actionColName': {type: String, default: 'ps_table_2__action'},
        // 'customizeHeaders': { type: Array, default: [] },
        'customizeDetails': { type: Array, default: [] },
    },
    data(props) {
        return {
            moment: moment,
            perpage: props.object.meta.per_page ,
            psColFilterOptions: props.colFilterOptions ? props.colFilterOptions : '',
        }
    },
    setup(props){
        const psSearch = ref(props.search ? props.search : '')

        return {
            psSearch
        }
        
    },
    methods: {
       handleSort(field,sort_order){

       sort_order = sort_order == 'desc' ? 'asc' : 'desc';
        this.$emit('handleSort',{field,sort_order})
       },
       rowPage(value){
        this.perpage = value;
        this.$emit('handleRow',value)
       },
       changeFilter(){
        console.log(this.psColFilterOptions);
        this.$emit('FilterOptionshandle',this.psColFilterOptions)
       },
       filterSelect(option){
       },
        checkSearchStr: _.debounce(function(self,value) {
          this.$emit('handleSearch',value)
        }, 1000),
    },
    watch:{
        psSearch(value) {
            this.checkSearchStr(this, value)
        },
        colFilterOptions(value) {
            this.psColFilterOptions = value;
        }
    },
}
</script>

