import { reactive,ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import ItemCurrency from '@templateCore/object/ItemCurrency';
import PsResource from '@templateCore/api/common/PsResource';

import { defineStore  } from 'pinia'

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';


export const useItemCurrencyStoreState = makeSeparatedStore((key: string) =>
defineStore(`itemCurrency/${key}`,
 () => {

    const itemCurrencyList = reactive<PsResource<ItemCurrency[]>>(new PsResource());
    const item = reactive<PsResource<ItemCurrency>>(new PsResource());
    const loading = reactive({
        value: false
    });

    let limit = ref(10);
    let offset: Number = 0;

    let id: string = "";
    const isNoMoreRecord = reactive({
        value: false
    })

    function updateItemCurrencyList(responseData: PsResource<ItemCurrency[]>) {

        if (itemCurrencyList != null
            && itemCurrencyList.data != null
            && itemCurrencyList.data.length > 0
            && offset != 0) {

            if (responseData.data != null && responseData.data.length > 0) {
                if(responseData.data?.length < limit.value){
                    isNoMoreRecord.value = true;
                } else {
                    isNoMoreRecord.value = false;
                }
                itemCurrencyList.data.push(...responseData.data);
            }else {
                isNoMoreRecord.value = true;
            }
            itemCurrencyList.code = responseData.code;
            itemCurrencyList.status = responseData.status;
            itemCurrencyList.message = responseData.message;

        } else {
            if(responseData.data?.length < limit.value || responseData.data == null){
                isNoMoreRecord.value = true;
            } else {
                isNoMoreRecord.value = false;
            }
            itemCurrencyList.data = responseData.data;
            itemCurrencyList.code = responseData.code;
            itemCurrencyList.status = responseData.status;
            itemCurrencyList.message = responseData.message;


        }

        if (itemCurrencyList != null && itemCurrencyList.data != null) {
            offset = itemCurrencyList.data.length;
        }

    }

    async function  loadItemCurrencyList(loginUserId : String) {

        loading.value = true;

        const responseData = await PsApiService.getItemCurrencyList<ItemCurrency>(new ItemCurrency(),  limit.value, offset,loginUserId);
        updateItemCurrencyList(responseData);

        loading.value = false;


    }


    async function resetItemCurrencyList(loginUserId : String) {

        offset = 0;

        loading.value = true;

        const responseData = await PsApiService.getItemCurrencyList<ItemCurrency>(new ItemCurrency(), limit.value, offset,loginUserId);

        updateItemCurrencyList(responseData);

        loading.value = false;

    }

    return{
        itemCurrencyList,
        item,
        loading,
        limit,
        offset,
        id,
        isNoMoreRecord,
        loadItemCurrencyList,
        resetItemCurrencyList,


    }

}),
);


