
import { reactive,ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import UserUnReadMessageParameterHolder from '@templateCore/object/holder/UserUnReadMessageParameterHolder';
import UserUnReadMessage from '@templateCore/object/UserUnReadMessage';

import { defineStore } from 'pinia';
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const useUserUnReadMessageStore = makeSeparatedStore((key: string) =>
defineStore(`userUnReadMessageStore/${key}`,
 () => {
    const unreadCount = reactive<PsResource<UserUnReadMessage>>(new PsResource());

    const loading = reactive({
        value: false
    });



    async function postUserUnreadMessageCount(holder: UserUnReadMessageParameterHolder) {

        loading.value = true;

        const responseData = await PsApiService.postUserUnreadMessageCount<UserUnReadMessage>(new UserUnReadMessage(), holder.toMap());
        
        unreadCount.data = responseData.data;
        unreadCount.code = responseData.code;
        unreadCount.status = responseData.status;
        unreadCount.message = responseData.message;
        
        loading.value = false;

        return unreadCount;

    }
    return {
        unreadCount,
        loading,
        postUserUnreadMessageCount


    }


}),
);