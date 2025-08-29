<template>

    <Head :title="$t('category_module')" />
        
    <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
    
    <!-- For Action Messages -->
    <ps-banner-icon  
        v-if="psBannerIconVisibleRef"
        :flag = "status.flag"
        class="mb-5 text-white sm:mb-6 lg:mb-8"
        ref="psBannerIconRef"
        >{{ status.msg }}
    </ps-banner-icon>
    
    <ps-table2 :row="row" :search="searchKeyword" :object="categories" 
        :colFilterOptions="colFilterOptionsRef" :columns="columnsRef" 
        :sort_field="sortFieldRef" :sort_order="sortOrderRef"
        @FilterOptionshandle="filterOptionshandle" @handleSort="handleSorting"  
        @handleSearch="handleSearching" @handleRow="handleRowLimit"
        :globalSearchPlaceholder="$t('core__be_search_category')">
    
        <!-- CSV file import -->
        <template #searchLeft>
            <ps-button v-if="can.createCategory" @click="handleCategoryImport" rounded="rounded" class="flex flex-wrap items-center ms-3 ">
                <ps-icon name="plus" class="font-semibold me-2" />
                <ps-label textColor="text-white dark:text-secondary-800">{{ $t('core__be_import_file') }}</ps-label>
            </ps-button>
            <ps-csv-modal 
                v-if="psCategoryImportModalVisibleRef"    
                ref="psCategoryImportModalRef"     
                url="https://drive.google.com/file/d/1XRhURzcCkMb1UzUQMkFCBvTcOOrYFWRq/view?usp=sharing"
            />
        </template>

        <!-- Create New Button -->
        <template #button>
            <ps-button v-if="can.createCategory" @click="categoryStore.navigateToCreateCategory()"  rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="font-semibold me-2" />{{$t('core__add_category')}}</ps-button>
        </template>
        <template #responsive_button>
            <ps-button v-if="can.createCategory" @click="categoryStore.navigateToCreateCategory()"  rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="font-semibold me-2" />{{$t('core__add_category')}}</ps-button>
        </template>
        
        <!-- Action (edit/delete) -->
        <template #tableActionRow="rowProps">            
            <div class="flex flex-row " v-if="rowProps.field == 'action'">
                <ps-button :disabled="rowProps.row.authorization.update ? false : true" @click="categoryStore.navigateToEditCategory(rowProps.row.id)" class="me-2" colors="bg-green-400 text-white" padding="p-1.5"
                    hover="hover:outline-none hover:ring hover:ring-green-100"
                    focus="focus:outline-none focus:ring focus:ring-green-200">
                    <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                </ps-button>
                <ps-button :disabled="rowProps.row.authorization.delete ? false : true" @click="handleDeleteCategory(rowProps.row.id, rowProps.row.authorization.delete)" colors="bg-red-400 text-white" padding="p-1.5"
                    hover="hover:outline-none hover:ring hover:ring-red-100"
                    focus="focus:outline-none focus:ring focus:ring-red-200">
                    <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                </ps-button>
                <ps-danger-dialog 
                    ref="psDeleteConfirmDialogRef"
                    v-if="psDeleteConfirmDialogVisibleRef" 
                />
            </div>            
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

export default {
   layout: PsLayout
};
</script>

<script setup>
import { trans } from 'laravel-vue-i18n';
import { router } from '@inertiajs/vue3';
import { ref, onMounted, defineAsyncComponent, computed } from 'vue';
import PsUtils from '@templateCore/utils/PsUtils';

import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";

import { useCategoryStoreState } from '@templateCore/store/modules/category/CategoryStore';
import { useTableColumnAndFilterStoreState } from '@templateCore/store/utilities/TableColumnAndFilterStore';

const PsCsvModal = defineAsyncComponent(() => import('@/Components/Core/Modals/PsCsvModal.vue'));
const PsBannerIcon = defineAsyncComponent(() => import('@/Components/Core/Banners/PsBannerIcon.vue'));
const PsDangerDialog = defineAsyncComponent(() => import('@/Components/Core/Dialog/PsDangerDialog.vue'));

const props = defineProps({
    can: Object,
    status: Object,
    categories: Object,
    hideShowFieldForFilterArr: Object,
    showCoreAndCustomFieldArr: Object,
    sort_field: {
        type: String,
        default: ""
    },
    sort_order: {
        type: String,
        default: 'desc'
    },
    search: String
});

var breadcrumb = computed(() => {
    return [{
        label: trans('core__be_dashboard_label'),
        url: route('admin.index')
    },
    {
        label: trans('category_module'),
        color: "text-primary-500"
    }];
});

// Init Stores & UIs
const categoryStore = useCategoryStoreState();
const tableColumnAndFilterStore = useTableColumnAndFilterStoreState();
const psBannerIconRef = ref();
const psBannerIconVisibleRef = ref(false);
const psDeleteConfirmDialogRef = ref();
const psDeleteConfirmDialogVisibleRef = ref(false);
const psCategoryImportModalRef = ref();
const psCategoryImportModalVisibleRef = ref(false);

// Init Page
onMounted(() => {
    
    columnsRef.value = tableColumnAndFilterStore.tableColumnsMapping(props.showCoreAndCustomFieldArr);
    
    colFilterOptionsRef.value = tableColumnAndFilterStore.tableFiltersMapping(props.hideShowFieldForFilterArr);
    
});

// Search 
const searchKeyword = computed(() => props.search);

function handleSearching(keyword){
    let page=1;
    categoryStore.navigateToCategoryIndex(
        sortFieldRef.value,
        sortOrderRef.value,
        page,
        props.categories.meta.per_page,
        keyword
    );
}

// Sorting
const sortFieldRef = computed(() => props.sort_field);
const sortOrderRef = computed(() => props.sort_order);

function handleSorting(value){
    categoryStore.navigateToCategoryIndex(
        value.field,
        value.sort_order,
        props.categories.meta.current_page,
        props.categories.meta.per_page,
        searchKeyword.value
    );
}

// Filter
const colFilterOptionsRef  = ref();
const columnsRef  = ref();
function filterOptionshandle(value) {
  
    router.put(route('category.screenDisplayUiSetting.store'),
        {
            value    
        },
        {
            preserveScroll: true,
            preserveState:true,        
        });

}

// Delete
async function handleDeleteCategory(id, hasPermission) {
    
    await PsUtils.waitingComponent(psDeleteConfirmDialogRef, psDeleteConfirmDialogVisibleRef);        
    psDeleteConfirmDialogRef.value.openModal(
        trans('core__delete'),
        trans('delete_category_msg'),
        trans('core__be_btn_confirm'),
        trans('core__be_btn_cancel'),
        () => {
            categoryStore.deleteCategoryInertia(id, hasPermission, async (_) => {
                await PsUtils.waitingComponent(psBannerIconRef, psBannerIconVisibleRef);
                psBannerIconRef.value.show();
            });
        },
        () => {}
    );
}

// Toggle Publish Status
function handlePublishStatus(id, hasPermission){    
    categoryStore.toggleStatusInertia(id, hasPermission, async (_) => {
        await PsUtils.waitingComponent(psBannerIconRef, psBannerIconVisibleRef);
        psBannerIconRef.value.show();
    });
}

// Import Categories
async function handleCategoryImport(){
    await PsUtils.waitingComponent(psCategoryImportModalRef, psCategoryImportModalVisibleRef);        
    psCategoryImportModalRef.value.openModal(
        (selectedFile) => {
            categoryStore.importCSVFileInertia(selectedFile);
        }
    );
}

// Row Limit Per Page
function handleRowLimit(value){
    categoryStore.navigateToCategoryIndex(
        sortFieldRef.value,
        sortOrderRef.value,
        1,
        value,
        searchKeyword.value
    );
}

</script>
