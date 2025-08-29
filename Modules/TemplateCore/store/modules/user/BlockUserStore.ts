import { reactive,ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import BlockedUser from '@templateCore/object/BlockedUser';
import { defineStore  } from 'pinia'

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const blockUserStoreState = makeSeparatedStore((key: string) =>
defineStore(`blockUserStore/${key}`,
 () => {

    const isNoMoreRecord = reactive({
        value: false
    })
    const blockUserList = reactive<PsResource<BlockedUser[]>>(new PsResource());

    const loading = reactive({
        value: false
    });

    let limit = ref(4);
    let offset: Number = 0;

    function updateBlockedUserList(responseData: PsResource<BlockedUser[]>) {

        if (blockUserList != null
            && blockUserList.data != null
            && blockUserList.data.length > 0
            && offset != 0) {

            if (responseData.data != null  && responseData.data.length > 0) {
                if(responseData.data?.length < limit.value){
                    isNoMoreRecord.value = true;
                } else {
                    isNoMoreRecord.value = false;
                }
                blockUserList.data.push(...responseData.data);
            }else {
                isNoMoreRecord.value = true;
            }

            blockUserList.code = responseData.code;
            blockUserList.status = responseData.status;
            blockUserList.message = responseData.message;

        } else {
            if(responseData.data?.length < limit.value || responseData.data == null){
                isNoMoreRecord.value = true;
            } else {
                isNoMoreRecord.value = false;
            }
            blockUserList.data = responseData.data;
            blockUserList.code = responseData.code;
            blockUserList.status = responseData.status;
            blockUserList.message = responseData.message;

        }

        if (blockUserList != null && blockUserList.data != null) {
            offset = blockUserList.data.length;
        }

    }

    async function loadBlockedUserList(loginUserId: string) {

        loading.value = true;

        const responseData = await PsApiService.getBlockedUserList<BlockedUser>(new BlockedUser(),loginUserId, limit.value, offset);

        updateBlockedUserList(responseData);

        loading.value = false;

    }

    async function resetBlockedUserList(loginUserId: string) {

        offset = 0;

        loading.value = true;

        const responseData = await PsApiService.getBlockedUserList<BlockedUser>(new BlockedUser(),loginUserId, limit.value, offset);

        updateBlockedUserList(responseData);

        loading.value = false;

    }

    async function refreshBlockedUserList(loginUserId: string) {

        loading.value = true;
        const tempOffset = 0;
        const templimit = blockUserList.data.length;

        const responseData = await PsApiService.getBlockedUserList<BlockedUser>(new BlockedUser(), loginUserId, templimit, tempOffset);

        blockUserList.data = responseData.data;
        blockUserList.code = responseData.code;
        blockUserList.status = responseData.status;
        blockUserList.message = responseData.message;

        loading.value = false;

    }

    return{
        isNoMoreRecord,
        blockUserList,
        loading,
        limit,
        offset,
        updateBlockedUserList,
        loadBlockedUserList,
        resetBlockedUserList,
        refreshBlockedUserList
    }
}),
);
