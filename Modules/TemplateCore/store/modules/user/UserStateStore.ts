
import { reactive } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import UserState from '@templateCore/object/UserState';
import { defineStore  } from 'pinia'

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const useUserStateStore = makeSeparatedStore((key: string) =>
defineStore(`user/${key}`,
 () => {

    const isNoMoreRecord = reactive({
        value: false
    })
    const userStateList = reactive<PsResource<UserState[]>>(new PsResource());

    const loading = reactive({
        value: false
    });

    let limit: Number = 30;
    let offset: Number = 0;

    function updateUserStateList(responseData: PsResource<UserState[]>) {

        if (userStateList != null
            && userStateList.data != null
            && userStateList.data.length > 0
            && offset != 0) {

            if (responseData.data != null && responseData.data.length > 0) {
                if(responseData.data?.length < limit){
                    isNoMoreRecord.value = true;
                } else {
                    isNoMoreRecord.value = false;
                }
                userStateList.data.push(...responseData.data);
            }else {
                isNoMoreRecord.value = true;
            }

            userStateList.code = responseData.code;
            userStateList.status = responseData.status;
            userStateList.message = responseData.message;

        } else {
            if(responseData.data?.length < limit || responseData.data == null){
                isNoMoreRecord.value = true;
            } else {
                isNoMoreRecord.value = false;
            }
            userStateList.data = responseData.data;
            userStateList.code = responseData.code;
            userStateList.status = responseData.status;
            userStateList.message = responseData.message;
        }

        if (userStateList != null && userStateList.data != null) {
            offset = userStateList.data.length;
        }

    }

    async function loadUserStateList() {

        loading.value = true;
        const responseData = await PsApiService.getUserStateList<UserState>(new UserState(), limit, offset);

        updateUserStateList(responseData);

        loading.value = false;

    }

    async function resetUserStateList() {

        offset = 0;

        loading.value = true;

        const responseData = await PsApiService.getUserStateList<UserState>(new UserState(), limit, offset);

        updateUserStateList(responseData);

        loading.value = false;

    }

    return {
        isNoMoreRecord,
        userStateList,
        loading,
        limit,
        offset,
        loadUserStateList,
        resetUserStateList,
    }

}),
);
