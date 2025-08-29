import { reactive } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import ApiStatus from '@templateCore/object/ApiStatus';
import TouchCountParameterHolder from '@templateCore/object/holder/TouchCountParameterHolder';
import { defineStore  } from 'pinia'
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const useTouchCountStore = makeSeparatedStore((key: string) =>
defineStore(`touchCountStore/${key}`,
 () => {

    const touchcount = reactive<PsResource<ApiStatus>>(new PsResource());
    const loading = reactive({
        value: false
    });

    let limit: Number = 10;
    let offset: Number = 0;

    let id: string = "";

    async function postTouchCount(loginUserId: string, holder: TouchCountParameterHolder) {

        loading.value = true;

        await PsApiService.postTouchCount<ApiStatus>(new ApiStatus(), loginUserId, holder.toMap());

        loading.value = false;

    }

    return {
        touchcount,
        loading,
        limit,
        offset,
        id,
        postTouchCount
    }

}),
);
