import { reactive,ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import SubCategorySubscribeParameterHolder from '@templateCore/object/holder/SubCategorySubscribeParameterHolder';
import ApiStatus from '@templateCore/object/ApiStatus';
import { defineStore  } from 'pinia'

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';


export const useSubCategoryScribeStoreState = makeSeparatedStore((key: string) =>
defineStore(`subCategoryScribeStore/${key}`,
 () => {

    const loading = reactive({
        value : false
    });

    async function subCategoryScription(loginUserId: String, holder: SubCategorySubscribeParameterHolder) {

        loading.value = true;

        const status = await PsApiService.postSubCategoryScribe<ApiStatus>(new ApiStatus(), loginUserId, holder.toMap());


        loading.value = false;

        return status;
    }

    async function isUserSubscribed(loginUserId: String, userId: String){

        loading.value = true;

        const status = await PsApiService.isUserSubscribed<ApiStatus>(new ApiStatus(), loginUserId, userId);

        loading.value = false;

        return status;
    }

    return{
        subCategoryScription,
        isUserSubscribed,
        loading,
    }
}),
);
