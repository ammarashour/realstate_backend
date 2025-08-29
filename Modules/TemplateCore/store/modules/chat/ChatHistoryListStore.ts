
import { reactive, ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import ChatHistory from '@templateCore/object/ChatHistory';
import ResetUnReadMessageHolder from '@templateCore/object/holder/ResetUnReadMessageHolder';
import SyncChatHistoryParameterHolder from '@templateCore/object/holder/SyncChatHistoryParameterHolder';
import ChatHistoryParameterHolder from '@templateCore/object/holder/ChatHistoryParameterHolder';
import { defineStore } from 'pinia';
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const useChatHistoryListStoreState = makeSeparatedStore((key: string) =>
defineStore(`chatHistoryListStore/${key}`,
 () => {


    const isNoMoreRecord = reactive({
        value: false
    })
    const chatHistoryList = reactive<PsResource<ChatHistory[]>>(new PsResource());

    const loading = reactive({
        value: false
    });

    let limit = ref(6);
    let offset: Number = 0;

    async function postChatHistory( itemId, buyerUserId, sellerUserId, type ) {
        const holder = new SyncChatHistoryParameterHolder();
        holder.itemId = itemId;
        holder.buyerUserId = buyerUserId;
        holder.sellerUserId = sellerUserId;
        holder.type = type;

        const data = await PsApiService.syncChatHistory<ChatHistory>(new ChatHistory(), holder.toMap());

    }

    function updateChatHistoryList(responseData: PsResource<ChatHistory[]>) {

        if (chatHistoryList != null
            && chatHistoryList.data != null
            && chatHistoryList.data.length > 0
            && offset != 0) {

            if (responseData.data != null && responseData.data.length > 0) {

                if(responseData.data?.length < limit.value){
                    isNoMoreRecord.value = true;
                } else {
                    isNoMoreRecord.value = false;
                }

                chatHistoryList.data.push(...responseData.data);
            }else {
                isNoMoreRecord.value = true;
            }

            chatHistoryList.code = responseData.code;
            chatHistoryList.status = responseData.status;
            chatHistoryList.message = responseData.message;

        } else {

            if(responseData.data?.length < limit.value || responseData.data == null){
                isNoMoreRecord.value = true;
            } else {
                isNoMoreRecord.value = false;
            }

            chatHistoryList.data = responseData.data;
            chatHistoryList.code = responseData.code;
            chatHistoryList.status = responseData.status;
            chatHistoryList.message = responseData.message;


        }

        if (chatHistoryList != null && chatHistoryList.data != null) {
            offset = chatHistoryList.data.length;
        }

    }

    async function loadChatHistoryList(loginUserId, holder: ChatHistoryParameterHolder) {

        loading.value = true;

        const responseData = await PsApiService.getChatHistoryList<ChatHistory>(new ChatHistory(),limit.value, offset, loginUserId, holder.toMap());
        updateChatHistoryList(responseData)

        loading.value = false;

    }

    async function resetChatHistoryList(loginUserId, holder: ChatHistoryParameterHolder) {

        offset = 0;
        isNoMoreRecord.value = false;

        loading.value = true;

        const responseData = await PsApiService.getChatHistoryList<ChatHistory>(new ChatHistory(),limit.value, offset, loginUserId, holder.toMap());

        updateChatHistoryList(responseData)

        loading.value = false;

    }
    // async function refreshChatHistoryList(loginUserId, holder: ChatHistoryParameterHolder) {

    //     isNoMoreRecord.value = false;
    //     limit.value = offset;
    //     offset = 0;

    //     loading.value = true;

    //     const responseData = await PsApiService.getChatHistoryList<ChatHistory>(new ChatHistory(),limit.value, offset, loginUserId, holder.toMap());

    //     updateChatHistoryList(responseData)

    //     loading.value = false;

    // }

    async function resetUnreadMessageCount(loginUserId : String, holder: ResetUnReadMessageHolder) {

        loading.value = true;

        await PsApiService.resetUnreadMessageCount<ChatHistory>(new ChatHistory(), loginUserId,holder.toMap());


        loading.value = false;

    }

    return {
        isNoMoreRecord,
        chatHistoryList,
        loading,
        limit,
        offset,
        postChatHistory,
        loadChatHistoryList,
        resetChatHistoryList,
        resetUnreadMessageCount,
        // refreshChatHistoryList
    }

}),
);

