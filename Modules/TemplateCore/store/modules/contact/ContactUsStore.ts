
import { reactive, ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import ContactUsParameterHolder from '@templateCore/object/holder/ContactUsParameterHolder';
import ApiStatus from '@templateCore/object/ApiStatus';

import { defineStore  } from 'pinia'

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';


export const useContactUsStoreState = makeSeparatedStore((key: string) =>
defineStore(`contactUsStore/${key}`,
 () => {

    const buyingitemList = reactive<PsResource<ApiStatus>>(new PsResource());

    const loading = reactive({
        value: false
    });

    let limit = ref(30);
    let offset: Number = 0;


    async function postContactUs(holder: ContactUsParameterHolder,loginUserId : String) {

        loading.value = true;

        await PsApiService.postContactUs<ApiStatus>(new ApiStatus(),loginUserId ,holder.toMap());

        loading.value = false;

    }

    return{
        buyingitemList,
        loading,
        limit,
        offset,
        postContactUs

    }

}),
);