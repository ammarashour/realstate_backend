import { reactive,ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import Product from '@templateCore/object/Product';
import ReportItemHolder from '@templateCore/object/holder/ReportItemHolder';
import { defineStore  } from 'pinia'
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';
import ApiStatus from '@templateCore/object/ApiStatus';

export const useReportedItemStoreState = makeSeparatedStore((key: string) =>
defineStore(`reportedItemStore/${key}`,
 () => {

    const isNoMoreRecord = reactive({
        value: false
    })
    const reportedReportedItemList = reactive<PsResource<Product[]>>(new PsResource());
    const item = reactive<PsResource<Product>>(new PsResource());
    const loading = reactive({
        value: false
    });

    let limit = ref(10);
    let offset: Number = 0;

    let id: string = "";

    function updateReportedItemList(responseData: PsResource<Product[]>) {

        if (reportedReportedItemList != null
            && reportedReportedItemList.data != null
            && reportedReportedItemList.data.length > 0
            && offset != 0) {

            if (responseData.data != null && responseData.data.length > 0) {
                if(responseData.data?.length < limit.value){
                    isNoMoreRecord.value = true;
                } else {
                    isNoMoreRecord.value = false;
                }
                reportedReportedItemList.data.push(...responseData.data);
            }else {
                isNoMoreRecord.value = true;
            }
            reportedReportedItemList.code = responseData.code;
            reportedReportedItemList.status = responseData.status;
            reportedReportedItemList.message = responseData.message;

        } else {
            if(responseData.data?.length < limit.value || responseData.data == null){
                isNoMoreRecord.value = true;
            } else {
                isNoMoreRecord.value = false;
            }
            reportedReportedItemList.data = responseData.data;
            reportedReportedItemList.code = responseData.code;
            reportedReportedItemList.status = responseData.status;
            reportedReportedItemList.message = responseData.message;
        }

        if (reportedReportedItemList != null && reportedReportedItemList.data != null) {
            offset = reportedReportedItemList.data.length;
        }

    }

    async function loadReportedItemList(loginUserId: string) {

        loading.value = true;

        const responseData = await PsApiService.getReportedItemList<Product>(new Product(), loginUserId, limit.value, offset);

        updateReportedItemList(responseData);

        loading.value = false;


    }

    async function resetReportedItemList(loginUserId: string) {

        offset = 0;

        loading.value = true;

        const responseData = await PsApiService.getReportedItemList<Product>(new Product(), loginUserId, limit.value, offset);

        updateReportedItemList(responseData);

        loading.value = false;

    }

    async function addReportItem(loginUserId: string, holder: ReportItemHolder): Promise<PsResource<ApiStatus>> {

        loading.value = true;

        const responseData = await PsApiService.postReportItem<ApiStatus>(new ApiStatus(), loginUserId, holder.toMap());
        item.data = responseData.data;
        item.code = responseData.code;
        item.status = responseData.status;
        item.message = responseData.message;

        loading.value = false;

        return item;

    }

    return{
        isNoMoreRecord,
        reportedReportedItemList,
        item,
        loading,
        limit,
        offset,
        id,
        loadReportedItemList,
        resetReportedItemList,
        addReportItem
    }

}),
);
