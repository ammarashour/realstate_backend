
import { reactive, ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import ItemLocation from '@templateCore/object/ItemLocation';
import LocationParameterHolder from '@templateCore/object/holder/LocationParameterHolder';
import { defineStore } from 'pinia'

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const useItemLocationStoreState = makeSeparatedStore((key: string) =>
defineStore(`ItemLocationStore/${key}`,
 () => {

    const itemLocationBackupList = reactive<PsResource<ItemLocation[]>>(new PsResource());
    const itemLocationList = reactive<PsResource<ItemLocation[]>>(new PsResource());
    let filterKeyword = ref('') ;
    const loading = reactive({
        value: false
    });

    let limit = ref(15);
    let offset: Number = 0;
    const isNoMoreRecord = reactive({
        value: false
    })

    function filterKeywordUpate(keyword: string, loginUserId : string , holder : LocationParameterHolder){
        filterKeyword.value = keyword;
        resetItemLocationList(loginUserId,holder)
    }

    function updateItemLocationList(responseData: PsResource<ItemLocation[]>) {

        if (itemLocationList != null
            && itemLocationList.data != null
            && itemLocationList.data.length > 0
            && offset != 0) {

            if (responseData.data != null && responseData.data.length > 0) {
                if(responseData.data?.length < limit.value){
                    isNoMoreRecord.value = true;
                } else {
                    isNoMoreRecord.value = false;
                }
                itemLocationList.data.push(...responseData.data);
            }else {
                isNoMoreRecord.value = true;
            }

            itemLocationList.code = responseData.code;
            itemLocationList.status = responseData.status;
            itemLocationList.message = responseData.message;

        } else {
            if(responseData.data?.length < limit.value || responseData.data == null){
                isNoMoreRecord.value = true;
            } else {
                isNoMoreRecord.value = false;
            }
            itemLocationList.data = responseData.data;
            itemLocationList.code = responseData.code;
            itemLocationList.status = responseData.status;
            itemLocationList.message = responseData.message;
        }

        if (itemLocationList != null && itemLocationList.data != null) {
            offset = itemLocationList.data.length;



        }

    }

    async function loadItemLocationList(loginUserId: string, holder: LocationParameterHolder) {
        loading.value = true;
        holder.keyword = filterKeyword.value;

        const responseData = await PsApiService.getItemLocationList<ItemLocation>(new ItemLocation(), limit.value, offset, loginUserId, holder.toMap());

        updateItemLocationList(responseData);

        loading.value = false;

    }

    async function resetItemLocationList(loginUserId: string, holder: LocationParameterHolder) {

        offset = 0;
        holder.keyword = filterKeyword.value;

        loading.value = true;

        const responseData = await PsApiService.getItemLocationList<ItemLocation>(new ItemLocation(), limit.value, offset, loginUserId, holder.toMap());

        updateItemLocationList(responseData);

        loading.value = false;

    }

    return{
        itemLocationBackupList,
        itemLocationList,
        filterKeyword,
        loading,
        limit,
        offset,
        isNoMoreRecord,
        filterKeywordUpate,
        updateItemLocationList,
        loadItemLocationList,
        resetItemLocationList
    }

}),
);
