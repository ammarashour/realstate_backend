import { reactive, ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import VendorSubScriptionPlan from '@templateCore/object/VendorSubscriptionPlan';
import ItemLimitParameterHolder from '@templateCore/object/holder/ItemLimitParameterHolder';
import { defineStore } from 'pinia'
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const useVendorSubScriptionStoreState = makeSeparatedStore((key: string) =>
    defineStore(`vendorSubScriptionStore/${key}`,
        () => {

    const isNoMoreRecord = reactive({
        value: false
    })
    const subScriptionList = reactive<PsResource<VendorSubScriptionPlan[]>>(new PsResource());
    const loading = reactive({
        value: false
    });

    let limit = ref(10);
    let offset: Number = 0;

    let id: string = "";

    function updateVendorSubscriptionPlanList(responseData: PsResource<VendorSubScriptionPlan[]>) {

        if (subScriptionList != null
            && subScriptionList.data != null
            && subScriptionList.data.length > 0
            && offset != 0) {

            if (responseData.data != null && responseData.data.length > 0) {
                if(responseData.data?.length < limit.value){
                    isNoMoreRecord.value = true;
                } else {
                    isNoMoreRecord.value = false;
                }
                subScriptionList.data.push(...responseData.data);
            }else {
                isNoMoreRecord.value = true;
            }
            subScriptionList.code = responseData.code;
            subScriptionList.status = responseData.status;
            subScriptionList.message = responseData.message;

        } else {
            if(responseData.data?.length < limit.value || responseData.data == null){
                isNoMoreRecord.value = true;
            } else {
                isNoMoreRecord.value = false;
            }
            subScriptionList.data = responseData.data;
            subScriptionList.code = responseData.code;
            subScriptionList.status = responseData.status;
            subScriptionList.message = responseData.message;
        }

        if (subScriptionList != null && subScriptionList.data != null) {
            offset = subScriptionList.data.length;
        }

    }

    async function loadVendorSubscriptionPlanList(loginUserId: string) {

        const parmaHolder = new ItemLimitParameterHolder;

        parmaHolder.userId = loginUserId;

        loading.value = true;

        const responseData = await PsApiService.getVendorSubScriptionPlanList<VendorSubScriptionPlan>(new VendorSubScriptionPlan(), loginUserId, limit.value, offset);

        updateVendorSubscriptionPlanList(responseData);

        loading.value = false;


    }


    async function resetVendorSubscriptionPlanList(loginUserId: string) {

        offset = 0;

        loading.value = true;

        const responseData = await PsApiService.getVendorSubScriptionPlanList<VendorSubScriptionPlan>(new VendorSubScriptionPlan(), loginUserId, limit.value, offset);

        updateVendorSubscriptionPlanList(responseData);

        loading.value = false;

    }

    return {
        isNoMoreRecord,
        subScriptionList,
        loading,
        limit,
        offset,
        id,
        loadVendorSubscriptionPlanList,
        resetVendorSubscriptionPlanList,
    }

}),
);

