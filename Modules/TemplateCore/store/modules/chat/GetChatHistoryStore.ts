
import { reactive, ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import ChatHistory from '@templateCore/object/ChatHistory';
import ProductParameterHolder from '@templateCore/object/holder/ProductParameterHolder';
import MarkAsSoldParameterHolder from '@templateCore/object/holder/MarkAsSoldParameterHolder';
import GetChatHistoryParameterHolder from '@templateCore/object/holder/GetChatHistoryParameterHolder';
import SyncChatHistoryParameterHolder from '@templateCore/object/holder/SyncChatHistoryParameterHolder';


import { defineStore } from 'pinia'
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const useGetChatHistoryStoreState = makeSeparatedStore((key: string) =>
    defineStore(`getChatHistoryStore/${key}`,
        () => {

            const chatHistory = reactive<PsResource<ChatHistory>>(new PsResource());

            const loading = reactive({
                value: false
            });

            let limit = ref(30);
            let offset: Number = 0;

    function updateChatHistory(responseData: PsResource<ChatHistory>) {

        chatHistory.data = responseData.data;

    }

    async function loadChatHistory(holder: GetChatHistoryParameterHolder, loginUserId: string) {
            loading.value = true;

            const returnData = await PsApiService.getChatHistory<ChatHistory>(new ChatHistory(), loginUserId, holder.toMap());

            updateChatHistory(returnData);

            loading.value = false;

        }

    async function postChatHistory(itemId, buyerUserId, sellerUserId, type, isOnline, message) {
            const holder = new SyncChatHistoryParameterHolder();
            holder.itemId = itemId;
            holder.buyerUserId = buyerUserId;
            holder.sellerUserId = sellerUserId;
            holder.type = type;
            holder.isUserOnline = isOnline;
            holder.message = message;

            const data = await PsApiService.syncChatHistory<ChatHistory>(new ChatHistory(), holder.toMap());

        }


    async function postAcceptedOffer(loginUserId: string, holder: ProductParameterHolder) {

            loading.value = true;

            await PsApiService.acceptedOffer<ChatHistory>(new ChatHistory(), loginUserId, holder.toMap());

            loading.value = false;

        }


    async function postRejectedOffer(loginUserId: string, holder: ProductParameterHolder) {

            loading.value = true;

            await PsApiService.rejectedOffer<ChatHistory>(new ChatHistory(), loginUserId, holder.toMap());

            loading.value = false;

        }

    async function makeMarkAsSold(loginUserId: string, itemId: string, buyerUserId: string, sellerUserId: string): Promise<PsResource<ChatHistory>> {

            const markAsSoldParameterHolder = new MarkAsSoldParameterHolder();
            markAsSoldParameterHolder.itemId = itemId.toString();
            markAsSoldParameterHolder.buyerUserId = buyerUserId.toString();
            markAsSoldParameterHolder.sellerUserId = sellerUserId.toString();

            loading.value = true;

            const returnData = await PsApiService.makeMarkAsSold<ChatHistory>(new ChatHistory(), loginUserId, markAsSoldParameterHolder.toMap());

            loading.value = false;

            return returnData;

        }

        return {
            chatHistory,
            loading,
            limit,
            offset,
            updateChatHistory,
            loadChatHistory,
            postChatHistory,
            postAcceptedOffer,
            postRejectedOffer,
            makeMarkAsSold,

        }

}),
);
