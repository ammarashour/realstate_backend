import { reactive,ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import PaidAdItem from '@templateCore/object/PaidAdItem';

import { defineStore  } from 'pinia'

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const usePaidAdItemStoreState = makeSeparatedStore((key: string) =>
defineStore(`aidAdItemStore/${key}`,
 () => {

    const paidAdItemList = reactive<PsResource<PaidAdItem[]>>(new PsResource());
    const loading = reactive({
        value: false
    });

    let limit = ref(4);
    let offset: Number = 0;
    const isNoMoreRecord = reactive({
        value: false
    })

    let id: string = "";

    function updatePaidAdItemList(responseData: PsResource<PaidAdItem[]>) {

        if (paidAdItemList != null
            && paidAdItemList.data != null
            && paidAdItemList.data.length > 0
            && offset != 0) {

            if (responseData.data != null && responseData.data.length > 0) {
                if(responseData.data?.length < limit.value){
                    isNoMoreRecord.value = true;
                } else {
                    isNoMoreRecord.value = false;
                }
                paidAdItemList.data.push(...responseData.data);
            }else {
                isNoMoreRecord.value = true;
            }
            paidAdItemList.code = responseData.code;
            paidAdItemList.status = responseData.status;
            paidAdItemList.message = responseData.message;

        } else {
            if(responseData.data?.length < limit.value ){
                isNoMoreRecord.value = true;
            } else {
                isNoMoreRecord.value = false;
            }
            paidAdItemList.data = responseData.data;
            paidAdItemList.code = responseData.code;
            paidAdItemList.status = responseData.status;
            paidAdItemList.message = responseData.message;


        }

        if (paidAdItemList != null && paidAdItemList.data != null) {
            offset = paidAdItemList.data.length;
        }

    }

    async function loadPaidAdItemList(loginUserId: string) {

        loading.value = true;

        const responseData = await PsApiService.getPaidAdItemList<PaidAdItem>(new PaidAdItem(), loginUserId, limit.value, offset);
        // PsUtils.log("paid ad");
        // PsUtils.log(responseData);
        updatePaidAdItemList(responseData);

        loading.value = false;


    }


    async function resetPaidAdItemList(loginUserId: string) {

        offset = 0;

        loading.value = true;

        const responseData = await PsApiService.getPaidAdItemList<PaidAdItem>(new PaidAdItem(), loginUserId, limit.value, offset);

        updatePaidAdItemList(responseData);

        loading.value = false;

    }

    async function refleshPaidAdItemList(loginUserId: string) {

        loading.value = true;
        const tempOffset = 0;
        const templimit = paidAdItemList.data.length;
        const responseData = await PsApiService.getPaidAdItemList<PaidAdItem>(new PaidAdItem(),loginUserId, templimit, tempOffset );
        paidAdItemList.data = responseData.data;
        paidAdItemList.code = responseData.code;
        paidAdItemList.status = responseData.status;
        paidAdItemList.message = responseData.message;
        loading.value = false;
    }

    return {
        isNoMoreRecord,
        paidAdItemList,
        loading,
        limit,
        offset,
        id,
        loadPaidAdItemList,
        resetPaidAdItemList,
        updatePaidAdItemList,
        refleshPaidAdItemList
    }

}),
);
