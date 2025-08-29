import { reactive, ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import ApiStatus from '@templateCore/object/ApiStatus';


import { defineStore  } from 'pinia'
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const useTokenStoreState = makeSeparatedStore((key: string) =>
defineStore(`TokenStore/${key}`,
 () => {

    const token = reactive<PsResource<ApiStatus>>(new PsResource());

    const loading = reactive({
        value: false
    });

    let limit = ref(10);
    let offset: Number = 0;

    let id: string = "";



    async function loadToken(loginUserId) {
        loading.value = true;

        const status = await PsApiService.getToken<ApiStatus>(new ApiStatus(), loginUserId);

        loading.value = false;

        return status;
    }

    return  {
        token,
        loading,
        limit,
        offset,
        id,
        loadToken
    }

}),
);
