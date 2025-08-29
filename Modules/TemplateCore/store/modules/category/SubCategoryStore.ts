import PsApiService from '@templateCore/api/PsApiService';
import SubCategory from '@templateCore/object/SubCategory';
import PsResource from '@templateCore/api/common/PsResource';
import SubCategoryListParameterHolder from '@templateCore/object/holder/SubCategoryListParameterHolder';
import { defineStore  } from 'pinia'
import { reactive,ref } from 'vue';
import PsApi from '@templateCore/api/common/PsApi';
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';


export const useSubCategoryStoreState = makeSeparatedStore((key: string) =>
defineStore(`subCategory/${key}`,
 () => {

    ////////////////////////////////////////////////////////////////////
    /// Axios Functions
    ////////////////////////////////////////////////////////////////////
    const isNoMoreRecord = reactive({
        value: false
    })
    const subCategoryList = reactive<PsResource<SubCategory[]>>(new PsResource());
    let catId = '';
    const loading = reactive({
        value : false
    });

    let limit = ref(10);
    let offset: Number = 0;

    const paramHolder = reactive<SubCategoryListParameterHolder>(new SubCategoryListParameterHolder().SubCategoryListParameterHolder());

    function setLimit(setting) {
        this.limit = setting?.default_loading_limit ?? 10
    }
    function hasData() {
        return subCategoryList?.data != null && subCategoryList!.data!.length > 0;
    }

    function updateSubCategoryList(responseData : PsResource<SubCategory[]>) {

        if(subCategoryList != null
            && subCategoryList.data != null
            && subCategoryList.data.length > 0
            && offset != 0) {

            if(responseData.data != null && responseData.data.length > 0) {
                if(responseData.data?.length < limit.value){
                    isNoMoreRecord.value = true;
                } else {
                    isNoMoreRecord.value = false;
                }
                subCategoryList.data.push(...responseData.data);
            }else {
                isNoMoreRecord.value = true;
            }

            subCategoryList.code = responseData.code;
            subCategoryList.status = responseData.status;
            subCategoryList.message = responseData.message;

        }else {
            if(responseData.data?.length < limit.value || responseData.data == null){
                isNoMoreRecord.value = true;
            } else {
                isNoMoreRecord.value = false;
            }
            subCategoryList.data = responseData.data;
            subCategoryList.code = responseData.code;
            subCategoryList.status = responseData.status;
            subCategoryList.message = responseData.message;

        }

        if(subCategoryList != null && subCategoryList.data != null ) {
            offset = subCategoryList.data.length;
        }

    }

    async function setSubCategoryList(categories: PsResource<SubCategory[]>) {
        
        offset = 0;

        loading.value = true;

        const responseData = await PsApi.wrapDataFromProps(new SubCategory(), categories);
        updateSubCategoryList(responseData);

        loading.value = false;

    }

    async function loadSubCategoryList(catId: string) {

        if(catId != catId) {
            const tempResoirce = new PsResource();
            subCategoryList.data = tempResoirce.data;
            subCategoryList.code = tempResoirce.code;
            subCategoryList.status = tempResoirce.status;
            subCategoryList.message = tempResoirce.message;
            offset = 0;
        }

        catId = catId.toString();

        loading.value = true;

        const holder = new SubCategoryListParameterHolder();
        holder.catId = catId;

        const responseData = await PsApiService.searchSubCategoryList<SubCategory>(new SubCategory(), '1', limit.value, offset, holder.toMap());

        loading.value = false;

        updateSubCategoryList(responseData);


    }

    async function resetSubCategoryList(catId: string) {

        if(catId != catId) {
            const tempResoirce = new PsResource();
            subCategoryList.data = tempResoirce.data;
            subCategoryList.code = tempResoirce.code;
            subCategoryList.status = tempResoirce.status;
            subCategoryList.message = tempResoirce.message;
        }

        offset = 0;

        catId = catId.toString();

        loading.value = true;

        const holder = new SubCategoryListParameterHolder();
        holder.catId = catId;

        const responseData = await PsApiService.searchSubCategoryList<SubCategory>(new SubCategory(), '1', limit.value, offset, holder.toMap());

        updateSubCategoryList(responseData);

        loading.value = false;

    }

    function filtersubCatUpdate(loginUserId:string,holder: SubCategoryListParameterHolder) {
        resetSearchSubCategoryList(loginUserId,holder);
    }

    async function resetSearchSubCategoryList(loginUserId:string,holder: SubCategoryListParameterHolder) {

        offset = 0;

        loading.value = true;

        const responseData = await PsApiService.searchSubCategoryList<SubCategory>(new SubCategory(), loginUserId, limit.value, offset, holder.toMap());
        updateSubCategoryList(responseData);

        loading.value = false;

    }

    ////////////////////////////////////////////////////////////////////
    /// Inertia Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Navigation Functions
    //-------------------------------------------------------------------
    
    function navigateToSubCategoryIndex(form){
        form.get(route('subcategory.index'),
        {
            preserveScroll: true,
            preserveState:true,
        });
    }

    function navigateToCreateSubCategory() {
        router.get(route("subcategory.create"));
    }

    function navigateToEditSubCategory(id) {
        router.get(route('subcategory.edit',id));
    }

    //-------------------------------------------------------------------
    // Actions
    //-------------------------------------------------------------------
    function importCSVFileInertia(selectedFile) {
        const formData = new FormData();
        formData.append('csvFile', selectedFile);
        formData.append('_method', 'put');
        
        router.post(route('subcategory.import.csv'), formData);
    }

    function saveSubCategoryInertia(form, callback?:(status, message) => void) {
        router.post(route("subcategory.store"), form, {
            forceFormData: true,
            onBefore: () => callback?.('before', ''),
            onSuccess: (data) => {
                
                const status = (data.props as any).status;
                callback?.(status.flag === 'danger' ? 'error' : 'success', status.msg);
                
            },
            onError: (err) => { console.log(err); callback?.('error', err) }
            
        });
    }

    function updateSubCategoryInertia(id, form, callback?:(status, message) => void) {
        router.post(route("subcategory.update", id), form, {
            forceFormData: true,
            onBefore: () => callback?.('before', ''),
            onSuccess: (data) => {
                const status = (data.props as any).status;
                callback?.(status.flag === 'danger' ? 'error' : 'success', status.msg);
                
            },
            onError: () => callback?.('error', '')
        });
    }

    function deleteSubCategoryInertia(id, hasPermission, callback?: (status, message) => void){
        alert(hasPermission);
        if(hasPermission){
            router.delete(route("subcategory.destroy", id),{
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
            router.put(route('subcategory.statusChange',id),
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
        subCategoryList,
        catId,
        loading,
        limit,
        offset,
        paramHolder,
        loadSubCategoryList,
        resetSubCategoryList,
        setSubCategoryList,
        filtersubCatUpdate,
        resetSearchSubCategoryList,
        navigateToSubCategoryIndex,
        navigateToCreateSubCategory,
        navigateToEditSubCategory,
        importCSVFileInertia,
        saveSubCategoryInertia,
        updateSubCategoryInertia,
        deleteSubCategoryInertia,
        toggleStatusInertia,
        setLimit,
        hasData
    }


}),
);
