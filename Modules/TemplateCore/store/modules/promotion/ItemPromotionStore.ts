import { reactive, ref } from 'vue';
import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import ItemPaidHistoryParameterHolder from '@templateCore/object/holder/ItemPaidHistoryParameterHolder';
import ItemPaidHistory from '@templateCore/object/ItemPaidHistory';
import VerifyTransaction from '@templateCore/object/VerifyTransaction';

import { defineStore  } from 'pinia'
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const useItemPromotionStoreState = makeSeparatedStore((key: string) =>
defineStore(`itemPromotionStore/${key}`,
 () => {

    const reportedReportedItemList = reactive<PsResource<ItemPaidHistory>>(new PsResource());
    const paiditem = reactive<PsResource<ItemPaidHistory>>(new PsResource());
    const transaction = reactive<PsResource<VerifyTransaction>>(new PsResource());
    const loading = reactive({
        value: false
    });

    let limit = ref(10);
    let offset: Number = 0;

    let id: string = "";

    async function postItemPromotion(holder:ItemPaidHistoryParameterHolder, loginUserId) : Promise<PsResource<ItemPaidHistory>>  {

        loading.value = true;

        paiditem.data = await PsApiService.postItemPromotion<ItemPaidHistory>(new ItemPaidHistory(), loginUserId, holder.toMap());

        loading.value = false;

        return paiditem;

    }

    async function verifyTransaction(loginUserId:String, transactionId: String,vendorId:String) : Promise<PsResource<VerifyTransaction>> {
        loading.value = true;

        const response = await PsApiService.verifyTransaction<VerifyTransaction>(new VerifyTransaction(), loginUserId, transactionId,vendorId);
        transaction.code = response.code;
        transaction.status = response.status;
        transaction.message = response.message;
        transaction.data = response.data;

        loading.value = false;
    }

    return {
        reportedReportedItemList,
        paiditem,
        transaction,
        loading,
        limit,
        offset,
        id,
        postItemPromotion,
        verifyTransaction
    }

}),
);
