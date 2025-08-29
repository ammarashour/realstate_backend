import { reactive,ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import OfflinePayment from '@templateCore/object/OfflinePayment';
import { defineStore  } from 'pinia'

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const useOfflinePaymentStoreState = makeSeparatedStore((key: string) =>
defineStore(`offlinePaymentStore/${key}`,
 () => {

    const offlinePayment = reactive<PsResource<OfflinePayment[]>>(new PsResource());
    const item = reactive<PsResource<OfflinePayment>>(new PsResource());

    const loading = reactive({
        value: false
    });

    let limit = ref(30);
    let offset: Number = 0;

    function updateOfflinePayment(responseData: PsResource<OfflinePayment[]>) {

        if (offlinePayment != null
            && offlinePayment.data != null
            && offlinePayment.data.length > 0
            && offset != 0) {

            if (responseData.data != null && responseData.data.length > 0) {
                offlinePayment.data.push(...responseData.data);
            }

            offlinePayment.code = responseData.code;
            offlinePayment.status = responseData.status;
            offlinePayment.message = responseData.message;

        } else {

            offlinePayment.data = responseData.data;
            offlinePayment.code = responseData.code;
            offlinePayment.status = responseData.status;
            offlinePayment.message = responseData.message;

        }

        if (offlinePayment != null && offlinePayment.data != null) {
            offset = offlinePayment.data.length;
        }

    }

    async function loadOfflinePaymentMethodList() {

        loading.value = true;

        const responseData = await PsApiService.getOfflinePaymentList<OfflinePayment>(new OfflinePayment(), limit.value, offset);

        updateOfflinePayment(responseData);
        loading.value = false;

        return responseData;

    }

    async function resetOfflinePaymentMethodList() {

        offset = 0;

        loading.value = true;

        await PsApiService.getOfflinePaymentList<OfflinePayment>(new OfflinePayment(), limit.value, offset);


        loading.value = false;

    }

    return {
        offlinePayment,
        item,
        loading,
        limit,
        offset,
        loadOfflinePaymentMethodList,
        resetOfflinePaymentMethodList
    }

}),
);

