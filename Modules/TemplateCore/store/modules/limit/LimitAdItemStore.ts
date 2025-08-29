import { reactive, ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import LimitAdTransaction from '@templateCore/object/LimitAdTransaction';
import PsUtils from '@templateCore/utils/PsUtils';
import ItemLimitParameterHolder from '@templateCore/object/holder/ItemLimitParameterHolder';
import { defineStore } from 'pinia'
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const useLimitAdItemStoreState = makeSeparatedStore((key: string) =>
    defineStore(`limitAdItemStore/${key}`,
        () => {

    const isNoMoreRecord = reactive({
        value: false
    })
    const buyadList = reactive<PsResource<LimitAdTransaction[]>>(new PsResource());
    const buyad = reactive<PsResource<LimitAdTransaction>>(new PsResource());
    const loading = reactive({
        value: false
    });

    let limit = ref(10);
    let offset: Number = 0;

    let id: string = "";

    function updateLimitAdItemList(responseData: PsResource<LimitAdTransaction[]>) {

        if (buyadList != null
            && buyadList.data != null
            && buyadList.data.length > 0
            && offset != 0) {

            if (responseData.data != null && responseData.data.length > 0) {
                if(responseData.data?.length < limit.value){
                    isNoMoreRecord.value = true;
                } else {
                    isNoMoreRecord.value = false;
                }
                buyadList.data.push(...responseData.data);
            }else {
                isNoMoreRecord.value = true;
            }
            buyadList.code = responseData.code;
            buyadList.status = responseData.status;
            buyadList.message = responseData.message;

        } else {
            if(responseData.data?.length < limit.value || responseData.data == null){
                isNoMoreRecord.value = true;
            } else {
                isNoMoreRecord.value = false;
            }
            buyadList.data = responseData.data;
            buyadList.code = responseData.code;
            buyadList.status = responseData.status;
            buyadList.message = responseData.message;
        }

        if (buyadList != null && buyadList.data != null) {
            offset = buyadList.data.length;
        }

    }

    async function loadPaidAdItemList(loginUserId: string) {

        const parmaHolder = new ItemLimitParameterHolder;

        parmaHolder.userId = loginUserId;

        loading.value = true;

        // const responseData = await PsApiService.getLimitAdItemList<LimitAdTransaction>(new LimitAdTransaction(), loginUserId, limit.value, offset, parmaHolder.toMap());
        const responseData = await PsApiService.getLimitAdItemList<LimitAdTransaction>(new LimitAdTransaction(), loginUserId, limit.value, offset, parmaHolder.toMap());

        updateLimitAdItemList(responseData);

        loading.value = false;


    }


    async function resetPaidAdItemList(loginUserId: string) {
        const parmaHolder = new ItemLimitParameterHolder;

        parmaHolder.userId = loginUserId;
        offset = 0;

        loading.value = true;

        const responseData = await PsApiService.getLimitAdItemList<LimitAdTransaction>(new LimitAdTransaction(), loginUserId, limit.value, offset, parmaHolder.toMap());

        updateLimitAdItemList(responseData);

        loading.value = false;

    }

    return {
        isNoMoreRecord,
        buyadList,
        buyad,
        loading,
        limit,
        offset,
        id,
        loadPaidAdItemList,
        resetPaidAdItemList,
    }

}),
);

