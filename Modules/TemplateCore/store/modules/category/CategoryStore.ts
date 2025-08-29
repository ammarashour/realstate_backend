import { reactive,ref } from 'vue';
import CategoryListParameterHolder from '@templateCore/object/holder/CategoryListParameterHolder';
import PsApiService from '@templateCore/api/PsApiService';
import PsApi from '@templateCore/api/common/PsApi';
import Category from '@templateCore/object/Category';
import PsResource from '@templateCore/api/common/PsResource';
import { defineStore } from 'pinia'
import { store } from '@templateCore/store/modules/core/PsStore';
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

export const useCategoryStoreState = makeSeparatedStore((key: string) =>
defineStore(`categoryStore/${key}`,
 () => {

    ////////////////////////////////////////////////////////////////////
    /// Axios Functions
    ////////////////////////////////////////////////////////////////////
    const isNoMoreRecord = reactive({
        value: false
    })
    const itemList = reactive<PsResource<Category[]>>(new PsResource());
    const item = reactive<PsResource<Category>>(new PsResource());
    const loading = reactive({
        value: false
    });

    let limit = ref(10);
    let offset: Number = 0;

    let id: string = "";
    let paramHolder = reactive<CategoryListParameterHolder>(new CategoryListParameterHolder().CategoryListParameterHolder());

    function setLimit(setting) {
        this.limit = setting?.default_loading_limit ?? 10
    }
    function hasData() {
        return itemList?.data != null && itemList!.data!.length > 0;
    }

    function updateCategoryList(responseData: PsResource<Category[]>) {
        if (itemList != null
            && itemList.data != null
            && itemList.data.length > 0
            && offset != 0) {

            if (responseData.data != null && responseData.data.length > 0) {

                if(responseData.data?.length < limit.value){
                    isNoMoreRecord.value = true;
                } else {
                    isNoMoreRecord.value = false;
                }

                itemList.data.push(...responseData.data);
            }else {
                isNoMoreRecord.value = true;
            }
            itemList.code = responseData.code;
            itemList.status = responseData.status;
            itemList.message = responseData.message;

        } else {

            if(responseData.data?.length < limit.value || responseData.data == null){
                isNoMoreRecord.value = true;
            } else {
                isNoMoreRecord.value = false;
            }

            itemList.code = responseData.code;
            itemList.status = responseData.status;
            itemList.message = responseData.message;
            itemList.data = responseData.data;

        }

        if (itemList != null && itemList.data != null) {
            offset = itemList.data.length;
        }

    }

    async function setCategoryList(categories: PsResource<Category[]>) {
        
        offset = 0;

        loading.value = true;

        const responseData = await PsApi.wrapDataFromProps(new Category(), categories);
        
        updateCategoryList(responseData);

        loading.value = false;

    }

    async function loadItemList(loginUserId:string, holder: CategoryListParameterHolder) {

        loading.value = true;

        const responseData = await PsApiService.getCategoryList<Category>(new Category(), limit.value, offset, loginUserId, holder.toMap());

        updateCategoryList(responseData);

        loading.value = false;


    }

    async function resetCategoryList(loginUserId:string,holder: CategoryListParameterHolder) {

        offset = 0;

        loading.value = true;

        const responseData = await PsApiService.getCategoryList<Category>(new Category(), limit.value, offset, loginUserId, holder.toMap());

        updateCategoryList(responseData);

        loading.value = false;

    }

    ////////////////////////////////////////////////////////////////////
    /// Inertia Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Navigation Functions
    //-------------------------------------------------------------------
    function navigateToCategoryIndex( sortField = null, sortOrder = null, page = null,row=null, keyword =  null){
        router.get(route('category.index'),
        {
            sort_field : sortField,
            sort_order: sortOrder,
            page: page,
            row: row,
            search: keyword
        },
        {
            preserveScroll: true,
            preserveState:true,
        })
    }

    function navigateToCreateCategory() {
        router.get(route("category.create"));
    }

    function navigateToEditCategory(id) {
        router.get(route('category.edit',id));
    }

    //-------------------------------------------------------------------
    // Actions
    //-------------------------------------------------------------------
    function importCSVFileInertia(selectedFile) {
        const formData = new FormData();
        formData.append('csvFile', selectedFile);
        formData.append('_method', 'put');
        
        router.post(route('category.import.csv'), formData);
    }

    function saveCategoryInertia(form, callback?:(status, message) => void) {
        router.post(route("category.store"), form, {
            forceFormData: true,
            onBefore: () => callback?.('before', ''),
            onSuccess: (data) => {
                const status = (data.props as any).status;
                callback?.(status.flag === 'danger' ? 'error' : 'success', status.msg);
                
            },
            onError: () => callback?.('error', '')
            
        });
    }

    function updateCategoryInertia(id, form, callback?:(status, message) => void) {
        router.post(route("category.update", id), form, {
            forceFormData: true,
            onBefore: () => callback?.('before', ''),
            onSuccess: (data) => {
                const status = (data.props as any).status;
                callback?.(status.flag === 'danger' ? 'error' : 'success', status.msg);
                
            },
            onError: () => callback?.('error', '')
        });
    }

    function deleteCategoryInertia(id, hasPermission, callback?: (status, message) => void){
        if(hasPermission){
            router.delete(route("category.destroy", id),{
                onBefore: () => callback?.('before', ''),
                onSuccess: (data) => {
                    const status = (data.props as any).status;
                    callback?.(status.flag === 'danger' ? 'error' : 'success', status.msg);
                    
                },
                onError: () => callback?.('error', '')
            });
        }
    }

    function toggleStatusInertia(id, hasPermission, callback?: (status, message) => void){
        if(hasPermission){
            router.put(route('category.statusChange',id),
            {}, {
                onBefore: () => callback?.('before', ''),
                onSuccess: (data) => {
                    const status = (data.props as any).status;
                    callback?.(status.flag === 'danger' ? 'error' : 'success', status.msg);
                    
                },
                onError: () => callback?.('error', '')
            });

        }


    }

    return{
        isNoMoreRecord,
        itemList,
        item,
        loading,
        limit,
        offset,
        id,
        paramHolder,
        setLimit,
        updateCategoryList,
        loadItemList,
        resetCategoryList,
        hasData,
        setCategoryList,
        navigateToCreateCategory,
        toggleStatusInertia,
        deleteCategoryInertia,
        importCSVFileInertia,
        navigateToEditCategory,
        saveCategoryInertia,
        updateCategoryInertia,
        navigateToCategoryIndex
    }

}),
);

// Need to be used outside the setup
export function useCategoryStoreStateWithOut() {
    return useCategoryStoreState(store);
}


