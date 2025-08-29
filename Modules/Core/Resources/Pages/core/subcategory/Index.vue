<template>

    <Head :title="$t('subcategory_module')" />
    
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        
        <!-- For Action Messages -->
        <ps-banner-icon  
            v-if="psBannerIconVisibleRef"
            :flag = "status.flag"
            class="mb-5 text-white sm:mb-6 lg:mb-8"
            ref="psBannerIconRef"
            >{{ status.msg }}
        </ps-banner-icon>
        
        <ps-table2 :row="row" :search="form.search" :object="subcategories" 
            :colFilterOptions="colFilterOptionsRef" :columns="columnsRef" 
            :sort_field="form.sort_field" :sort_order="form.sort_order"
            @FilterOptionshandle="filterOptionshandle" @handleSort="handleSorting"  
            @handleSearch="handleSearching" @handleRow="handleRowLimit"
            :globalSearchPlaceholder="$t('core__be_search_subcategory')">
    
            <!-- CSV file import -->
            <template #searchLeft>
                <ps-button v-if="can.createSubcategory" @click="handleSubCategoryImport" rounded="rounded" class="flex flex-wrap items-center ms-3 ">
                    <ps-icon name="plus" class="font-semibold me-2" />
                    <ps-label textColor="text-white dark:text-secondary-800">{{ $t('core__be_import_file') }}</ps-label>
                </ps-button>
                <ps-csv-modal 
                    v-if="psSubCategoryImportModalVisibleRef"    
                    ref="psSubCategoryImportModalRef"     
                    url="https://drive.google.com/file/d/1PtFk3RrBH5AhMZzo-6Ga_tP8yE3flwUm/view?usp=sharing"
                />
            </template>

            <!-- Create New Button -->
            <template #button>
                <ps-button v-if="can.createSubcategory" @click="subCategoryStore.navigateToCreateSubCategory()"  rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="font-semibold me-2" />{{$t('core__be_add_subcategory')}}</ps-button>
            </template>
            <template #responsive_button>
                <ps-button v-if="can.createSubcategory" @click="subCategoryStore.navigateToCreateSubCategory()"  rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="font-semibold me-2" />{{$t('core__be_add_subcategory')}}</ps-button>
            </template>
            

            <!-- Filter : Category -->
            <template #searchRight>               
                <ps-dropdown @on-click="dropdownClick" class="lg:w-56 md:40 h-10 sm:w-full" >
                    <template #select>

                        <!-- @todo category selection is not showing name 
                         (form.category_filter == '' || form.category_filter == 'all') ? '' : $t(form.category_filter ?? '') 
                        -->
                        <ps-dropdown-select :placeholder="$t('core__be_category')" :border="(selected_cat !== '' && selected_cat !== 'all') ?'border border-indigo-500/100':'border border-1 border-secondary-200'"
                            selectedValue=""                            
                        />
                        
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleCategoryFilter('all')">
                                    <ps-label class="text-gray-200 ms-2">{{$t('core__be_select_all')}}</ps-label>
                                </div>
                                <div v-for="category in categoryStore.itemList?.data" :key="category.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleCategoryFilter(category.catId)">
                                    <ps-label class="ms-2" :class="category.catId == selected_cat ? ' font-bold' : ''">
                                        {{ category.catName }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #loadmore>
                       <div  @click="dropdownClick(true)" :class="categoryStore.isNoMoreRecord.value ? 'hidden' : ''" class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center">
 			            <div class="flex flex-row items-center justify-between">
                                    <ps-label  class="ms-2 ">
                                        {{categoryStore.loading ? $t('core__be_load_more') : $t('core__be_loading') }}
                                    </ps-label>
                                    <ps-icon theme="text-black dark:text-primary-900" name="load" w="16" h="16" />
                        </div>
                       </div>
                    </template>
                     <template #filter>

                        <div class="mt-1 mx-1">
                            <ps-input-with-right-icon class="w-full h-10"  rounded="rounded-lg" v-model:value="form.search" :placeholder="$t('core__be_search')" >
                                <template #icon >
                                    <ps-icon name="search" class='cursor-pointer' />
                                </template>
                            </ps-input-with-right-icon>
                        </div>
                    </template>
                </ps-dropdown>
            </template>

            
            <!-- Action (edit/delete) -->
            <template #tableActionRow="rowProps">
                <ps-label v-if="rowProps.field == 'action'">
                    <div class="flex flex-row">
                        <ps-button :disabled="!rowProps.row.authorization.update" @click="subCategoryStore.navigateToEditSubCategory(rowProps.row.id)" class="me-2" colors="bg-green-400 text-white" padding="p-1.5"
                            hover="hover:outline-none hover:ring hover:ring-green-100"
                            focus="focus:outline-none focus:ring focus:ring-green-200">
                            <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                        </ps-button>
                        <ps-button :disabled="!rowProps.row.authorization.delete" @click="handleDeleteSubCategory(rowProps.row.id, rowProps.row.authorization.delete)" colors="bg-red-400 text-white" padding="p-1.5"
                            hover="hover:outline-none hover:ring hover:ring-red-100"
                            focus="focus:outline-none focus:ring focus:ring-red-200">
                            <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                        </ps-button>
                        <ps-danger-dialog 
                            ref="psDeleteConfirmDialogRef"
                            v-if="psDeleteConfirmDialogVisibleRef" 
                        />
                    </div>
                </ps-label>                
            </template>
            
            <!-- Publish Status -->
            <template #tableRow="rowProps">
            <div v-if="rowProps.field == 'status'">
                <ps-toggle :disabled="rowProps.row.authorization.update ? false : true"  v-if="rowProps.field == 'status'" :selectedValue="rowProps.row.status == 1 ? true : false"
                    @click="handlePublishStatus(rowProps.row.id,rowProps.row.authorization.update)"></ps-toggle>
            </div>
        </template>

        </ps-table2>        
</template>

<script>
import PsLayout from "@/Components/PsLayout.vue";
import { useSubCategoryStoreState } from '../../../../../TemplateCore/store/modules/category/SubCategoryStore';

export default {
   layout: PsLayout
};
</script>

<script setup>
import { trans } from 'laravel-vue-i18n';
import { router, useForm } from '@inertiajs/vue3';
import { ref, onMounted, defineAsyncComponent, computed, watch } from 'vue';
import PsUtils from '@templateCore/utils/PsUtils';

import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsInputWithRightIcon from '@/Components/Core/Input/PsInputWithRightIcon.vue';

import { useCategoryStoreState } from '@templateCore/store/modules/category/CategoryStore';
import { useTableColumnAndFilterStoreState } from '@templateCore/store/utilities/TableColumnAndFilterStore';
import { PsValueStore } from "@templateCore/store/modules/core/PsValueStore";

const PsCsvModal = defineAsyncComponent(() => import('@/Components/Core/Modals/PsCsvModal.vue'));
const PsBannerIcon = defineAsyncComponent(() => import('@/Components/Core/Banners/PsBannerIcon.vue'));
const PsDangerDialog = defineAsyncComponent(() => import('@/Components/Core/Dialog/PsDangerDialog.vue'));

const props = defineProps({
    can:Object,
    status:Object,
    // categories:Object,
    subcategories:Object,
    owners:Object,
    hideShowFieldForFilterArr:Object,
    showCoreAndCustomFieldArr:Object,
    selectedCategory:{type:String,default:''},
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
});

var breadcrumb = computed(() => {
    return [{
        label: trans('core__be_dashboard_label'),
        url: route('admin.index')
    },
    {
        label: trans('subcategory_module'),
        color: "text-primary-500"
    }];
});

// Init Stores & UIs
const psValueStore = PsValueStore();
const subCategoryStore = useSubCategoryStoreState();
const categoryStore = useCategoryStoreState('be-sub-category-list');
const tableColumnAndFilterStore = useTableColumnAndFilterStoreState();
const psBannerIconRef = ref();
const psBannerIconVisibleRef = ref(false);
const psDeleteConfirmDialogRef = ref();
const psDeleteConfirmDialogVisibleRef = ref(false);
const psSubCategoryImportModalRef = ref();
const psSubCategoryImportModalVisibleRef = ref(false);
// let categories = ref([]);
// let category_loadmore_visible= ref(false);
// let is_loading = ref(false);

const loginUserId = psValueStore.getLoginUserId();

const form = useForm({
    sort_field : props.sort_field, 
    sort_order: props.sort_order, 
    page: props.subcategories.meta.current_page,
    row: props.subcategories.meta.per_page,
    search: props.search ,
    category_filter: props.selectedCategory
});

// Init Page
onMounted(() => {
    
    columnsRef.value = tableColumnAndFilterStore.tableColumnsMapping(props.showCoreAndCustomFieldArr);
    
    colFilterOptionsRef.value = tableColumnAndFilterStore.tableFiltersMapping(props.hideShowFieldForFilterArr);
    
});

// Search
function handleSearching(keyword){
    form.page = 1; // reset
    form.search = keyword; 

    subCategoryStore.navigateToSubCategoryIndex(form);    
}

// Sorting
function handleSorting(value){

    form.sort_field = value.field;
    form.sort_order = value.sort_order;

    subCategoryStore.navigateToSubCategoryIndex(form);
   
}

// Filter
const colFilterOptionsRef  = ref();
const columnsRef  = ref();
function filterOptionshandle(value) {
  
    router.put(route('subcategory.screenDisplayUiSetting.store'),
        {
            value    
        },
        {
            preserveScroll: true,
            preserveState:true,        
        });

}

function handleCategoryFilter(catId){
    
    form.page = 1; // reset
    form.category_filter = catId;

    subCategoryStore.navigateToSubCategoryIndex(form);
}

// Delete
async function handleDeleteSubCategory(id, hasPermission) {
    
    await PsUtils.waitingComponent(psDeleteConfirmDialogRef, psDeleteConfirmDialogVisibleRef);        
    psDeleteConfirmDialogRef.value.openModal(
        trans('core__delete'),
        trans('delete_category_msg'),
        trans('core__be_btn_confirm'),
        trans('core__be_btn_cancel'),
        () => {
            subCategoryStore.deleteSubCategoryInertia(id, hasPermission, async (_) => {
                await PsUtils.waitingComponent(psBannerIconRef, psBannerIconVisibleRef);
                psBannerIconRef.value.show();
            });
        },
        () => {}
    );
}

// Toggle Publish Status
function handlePublishStatus(id, hasPermission){    
    subCategoryStore.toggleStatusInertia(id, hasPermission, async (_) => {
        await PsUtils.waitingComponent(psBannerIconRef, psBannerIconVisibleRef);
        psBannerIconRef.value.show();
    });
}

// Import Sub Categories
async function handleSubCategoryImport(){
    await PsUtils.waitingComponent(psSubCategoryImportModalRef, psSubCategoryImportModalVisibleRef);        
    psSubCategoryImportModalRef.value.openModal(
        (selectedFile) => {
            subCategoryStore.importCSVFileInertia(selectedFile);
        }
    );
}

// Row Limit Per Page
function handleRowLimit(value){

    form.page = 1; // reset
    form.row = value; // update the row limit

    subCategoryStore.navigateToSubCategoryIndex(form);

}

// Category List Loading

function dropdownClick() {

    categoryStore.loadItemList(loginUserId, categoryStore.paramHolder);
    
}

// watch(form.search,_.debounce((current,previous)=>{
//     let offset= 0;
//     subcategories.value = [];
//     getCategoriesData(offset);

// },500));


// function getCategoriesData(offset){
//     category_loadmore_visible.value = true;
//     is_loading.value = true
    
//     categoryStore.resetCategoryList(loginUserId, categoryStore.paramHolder);
//     is_loading.value=false;
// }


</script>
