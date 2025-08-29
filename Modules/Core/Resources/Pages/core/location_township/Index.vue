<template>
    <Head :title="$t('township_module')" />
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
        <ps-table2  :row="row" :search="search" :object="this.townships" :colFilterOptions="colFilterOptions" :columns="columns" :sort_field="sort_field" :sort_order="sort_order" @FilterOptionshandle="FilterOptionshandle"
        @handleSort="handleSorting"  @handleSearch="handleSearching" @handleRow="handleRow"
            :searchable="showFilter" :globalSearchPlaceholder="$t('core__be_search_townships')">
            <!-- add new field start -->
            <template #button>
                <ps-button v-if="can.createLocationTownship"  @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-2 font-semibold" />{{$t('core__be_add_township')}}</ps-button>
            </template>
            <template #responsive_button>
                <ps-button v-if="can.createLocationTownship" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-2 font-semibold" />{{$t('core__be_add_township')}}</ps-button>
            </template>
            <!-- add new field end -->
            <!-- <template #searchRight>
                <ps-text-button v-if="showFilter" @click="handleClearFilter()" class="flex text-sm items-center text-red-400" padding="py-1 px-4">
                    <ps-icon name="cross" viewBox="0 0 14 14" w="14" h="14" class="me-3" />
                    {{ $t('core__be_clear_filter') }}
                </ps-text-button>
                <ps-icon-button :colors="!showFilter? '' : 'bg-primary-500 text-white'" focus="" padding="py-1 px-4"
                    hover="hover:bg-primary-500 hover:text-white" border="border border-grey-200"
                    leftIcon="filter"  @click="showFilter = !showFilter" >  Filter</ps-icon-button>
            </template> -->
             <template #searchRight>
                <ps-dropdown  @on-click="cityDropdownClick" align="" class="lg:w-56 md:40 h-10 sm:w-full" >
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
                                    <ps-label class="text-gray-200 ms-2">{{$t('core__be_select_all')}}</ps-label>
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
                            <ps-input-with-right-icon  class="w-full h-10" rounded="rounded-lg" v-model:value="citySearch" :placeholder="$t('core__be_search')" >
                                <template #icon >
                                    <ps-icon name="search" class='cursor-pointer' />
                                </template>
                            </ps-input-with-right-icon>
                        </div>
                    </template>
                </ps-dropdown>

                </template>
            <template #searchLeft>

                <ps-button v-if="can.createLocationTownship" @click="csvUploadClicked" rounded="rounded" class="flex flex-wrap items-center ms-3 ">
                    <ps-icon name="plus" class="me-2 font-semibold" />
                    {{ $t('core__be_import_file') }}
                </ps-button>
                <ps-csv-modal ref="ps_csv_modal" url="https://drive.google.com/file/d/1IVyq9-kXRnCU2DCaI2onYH4EOGjzdS0E/view?usp=sharing" isCityTownship />

            </template>
            <!-- for csv file import start -->
            <template #tableActionRow="rowProps">
                <!-- For action (edit/delete) start -->
                <div class="flex flex-row" v-if="rowProps.field == 'action'">
                    <ps-button  :disabled="rowProps.row.authorization.update ? false : true" @click="handleEdit(rowProps.row.id)" class="me-2" colors="bg-green-400 text-white" padding="p-1.5"
                        hover="hover:outline-none hover:ring hover:ring-green-100"
                        focus="focus:outline-none focus:ring focus:ring-green-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                    </ps-button>
                    <ps-button :disabled="rowProps.row.authorization.delete ? false : true"  @click="confirmDeleteClicked(rowProps.row.id)" colors="bg-red-400 text-white" padding="p-1.5"
                        hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>
                <!-- For action (edit/delete) end -->
            </template>
            <template #tableRow="rowProps">
                <ps-toggle  :disabled="rowProps.row.authorization.update ? false : true" v-if="rowProps.field == 'status'" :selectedValue="rowProps.row.status == 1 ? true : false" @click="handlePublish(rowProps.row.id,rowProps.row.authorization.update)"></ps-toggle>
            </template>

        </ps-table2>
        <!-- data table end -->
    </ps-layout>
</template>

<script>
import { defineComponent, ref, reactive ,watch } from 'vue'
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
import PsCsvModal from '@/Components/Core/Modals/PsCsvModal.vue';
import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";
import Dropdown from "@/Components/Core/DropdownModal/Dropdown.vue";
import PsIconButton from "@/Components/Core/Buttons/PsIconButton.vue";
import { trans } from 'laravel-vue-i18n';
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsTextButton from "@/Components/Core/Buttons/PsTextButton.vue";
import { router } from '@inertiajs/vue3';
import { getCategories, getSubCat, getCustomFields, getCities, getTownships, getUsers } from '@/Api/psApiService.js'
import PsInput from "@/Components/Core/Input/PsInput.vue";
import debounce from 'lodash/debounce';

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
        PsCsvModal,
        PsBannerIcon,
        Dropdown,
        PsIconButton,
        PsDropdown,
        PsDropdownSelect,
        PsTextButton,
        PsInput,
        PsInputWithRightIcon
    },
    layout: PsLayout,
   props:{
            can:Object,
            status:Object,
            townships:Object,
            owners:Object,
            hideShowFieldForFilterArr:Object,
            showCoreAndCustomFieldArr:Object,
            selectedCity:{type:String,default:''},
            authUser:Object,
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
    data() {
        return {
            form: useForm(
                {
                    csvFile: ""
                }
            )
        }
    },
    setup(props) {
        // For data table
        const showFilter = ref(false);
        const clearFilter = ref(false);
        let sorting = "";
        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
        let selected_city = props.selectedCity ? ref(props.selectedCity.id) : ref('');

        let cities = ref([]);
        let city_loadmore_visible= ref(false);
        let citySearch = ref();
        let is_loading = ref(false);


        const ps_danger_dialog = ref();
        const ps_csv_modal = ref();
        const showSearchRight = ref(false);
        let visible = ref(false)

        const colFilterOptions = ref();

        const columns = ref();

        function confirmDeleteClicked(id) {
            ps_danger_dialog.value.openModal(
                trans('core__delete'),
                trans('core__comfirm_to_delete_row_township'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    this.$inertia.delete(route("township.destroy", id),{
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

        function csvUploadClicked() {
            ps_csv_modal.value.openModal(
                (selectedFile) => {
                    let form = useForm({
                        csvFile: selectedFile,
                        "_method": "put"
                    })
                    router.post(route('township.import.csv'), form)
                }
            );
        }

         function handleSorting(value){
            sort_field.value = value.field
            sort_order.value = value.sort_order
            handleSearchingSorting()
        }

        function handleClearFilter(){
             selected_city.value = 'all';

             handleSearchingSorting();
        }

         function handleCityfilter(value) {
            selected_city.value = value
            // selected_township.value = selected_township.value ?(props.cities.filter((city) => city.category_id == value).filter((city) => city.id == selected_township.value)[0]?selected_township.value:''):''
            let page = 1;
            handleSearchingSorting(page);
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
            router.get(route('township.index'),
            {
                sort_field : sort_field.value,
                sort_order: sort_order.value,
                page:page ?? props.townships.meta.current_page,
                row:row ?? props.townships.meta.per_page,
                search:search.value,
                city_filter:selected_city.value,
            },
            {
                preserveScroll: true,
                preserveState:true,
            })
        }

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

        function loadEyeFilter(value) {
            colFilterOptions.value = value;
        }

       return {
            loadEyeFilter,
            columns,
            ps_danger_dialog,
            confirmDeleteClicked,
            ps_csv_modal,
            csvUploadClicked,
            colFilterOptions,
            visible,

            showFilter,
            clearFilter,
            handleSorting,
            handleSearchingSorting,
            handleCityfilter,
            handleClearFilter,
            handleRow,
            handleSearching,
            selected_city,
            is_loading,
            cityDropdownClick,
            cities,
            city_loadmore_visible,
            citySearch,
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
                    label: trans('township_module'),
                    color: "text-primary-500"
                }
            ]
        }
    },
    methods: {
        handleCreate() {
            this.$inertia.get(route("township.create"));
        },
        handleEdit(id) {
            this.$inertia.get(route('township.edit', id));
        },
        handlePublish(id,hasPermission) {
            if(hasPermission){
                this.$inertia.put(route('township.statusChange', id));
            }

        },
        FilterOptionshandle(value) {
        router.put(route('township.screenDisplayUiSetting.store'),
            {
                value,
                sort_field :this.sort_field ,
                sort_order:this.sort_order,
                row:this.cities.per_page,
                search:this.search.value,
                current_page:this.cities.current_page
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
        console.log(this.colFilterOptions);
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
