
import { reactive, ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import Noti from '@templateCore/object/Noti';
import NotiParameterHolder from '@templateCore/object/holder/NotiParameterHolder';
import NotiActionParamHolder from '@templateCore/object/holder/NotiActionParamHolder';
import NotiRegisterHolder from '@templateCore/object/holder/NotiRegisterHolder';
import NotiUnRegisterHolder from '@templateCore/object/holder/NotiUnRegisterHolder';
import ChatNotiPrameterHolder from '@templateCore/object/holder/ChatNotiParameterHolder';
import ApiStatus from '@templateCore/object/ApiStatus';

import { defineStore  } from 'pinia'
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const useNotiStoreState= makeSeparatedStore((key: string) =>
defineStore(`notiStore/${key}`,
 () => {

    const isNoMoreRecord = reactive({
        value: false
    })
    const notiList = reactive<PsResource<Noti[]>>(new PsResource());
    const noti = reactive<PsResource<Noti>>(new PsResource());
    const loading = reactive({
        value: false
    });

    let limit = ref(30);
    let offset: Number = 0;

    function updateNotiList(responseData: PsResource<Noti[]>) {

        if (notiList != null
            && notiList.data != null
            && notiList.data.length > 0
            && offset != 0) {

            if (responseData.data != null && responseData.data.length > 0) {
                if(responseData.data?.length < limit.value){
                    isNoMoreRecord.value = true;
                } else {
                    isNoMoreRecord.value = false;
                }
                notiList.data.push(...responseData.data);
            }else {
                isNoMoreRecord.value = true;
            }

            notiList.code = responseData.code;
            notiList.status = responseData.status;
            notiList.message = responseData.message;

        } else {
            if(responseData.data?.length < limit.value || responseData.data == null){
                isNoMoreRecord.value = true;
            } else {
                isNoMoreRecord.value = false;
            }
            notiList.data = responseData.data;
            notiList.code = responseData.code;
            notiList.status = responseData.status;
            notiList.message = responseData.message;


        }

        if (notiList != null && notiList.data != null) {
            offset = notiList.data.length;
        }

    }

    async function loadNotiList(holder: NotiParameterHolder) {
        loading.value = true;

        const responseData = await PsApiService.getNotificationList<Noti>(new Noti(), limit.value, offset, holder.toMap());

        updateNotiList(responseData);

        loading.value = false;

    }

    async function resetNotiList(holder: NotiParameterHolder) {

        offset = 0;

        loading.value = true;

        const responseData = await PsApiService.getNotificationList<Noti>(new Noti(), limit.value, offset, holder.toMap());

        updateNotiList(responseData);

        loading.value = false;

    }

    async function postNoti(holder: NotiParameterHolder) {

        offset = 0;

        loading.value = true;

        const responseData = await PsApiService.postNoti<Noti>(new Noti(), holder.toMap());

        loading.value = false;

    }

    async function loadNoti(notificationId: string, loginUserId: string) {

        loading.value = true;

        const responseData = await PsApiService.getNotiDetail<Noti>(new Noti(), notificationId, loginUserId);
        noti.data = responseData.data;
        noti.code = responseData.code;
        noti.status = responseData.status;
        noti.message = responseData.message;

        loading.value = false;


    }

    async function postChatNoti(itemId, sellerUserId, buyerUserId, message, type) {
        const holder = new ChatNotiPrameterHolder();
        holder.itemId = itemId;
        holder.sellerUserId = sellerUserId;
        holder.buyerUserId = buyerUserId;
        holder.message = message;
        holder.type = type;

        const returnData = await PsApiService.postChatNoti<ApiStatus>(new ApiStatus(), holder.toMap());
    }

    async function registerNotiToken(holder: NotiRegisterHolder) {

        loading.value = true;

        const status = await PsApiService.rawRegisterNotiToken<ApiStatus>( new ApiStatus(), holder.toMap());

        loading.value = false;

        return status;


    }

    async function unRegisterNotiToken(holder: NotiUnRegisterHolder) {

        loading.value = true;

        const status = await PsApiService.rawUnRegisterNotiToken<ApiStatus>( new ApiStatus(), holder.toMap());

        loading.value = false;

        return status;


    }

    async function markAsRead(holder: NotiActionParamHolder, loginUserId : string) {
        loading.value = true;

        const responseData = await PsApiService.notiMarkAsRead<Noti>(new Noti(), loginUserId, holder.toMap());

        loading.value = false;

    }

    async function markAsUnRead(holder: NotiActionParamHolder, loginUserId : string) {
        loading.value = true;

        const responseData = await PsApiService.notiMarkAsUnRead<Noti>(new Noti(), loginUserId, holder.toMap());

        loading.value = false;

    }

    async function deleteNoti(holder: NotiActionParamHolder, loginUserId : string) {
        loading.value = true;

        const responseData = await PsApiService.deleteNoti<Noti>(new Noti(), loginUserId, holder.toMap());

        loading.value = false;

    }


    return{
        isNoMoreRecord,
        notiList,
        noti,
        loading,
        limit,
        offset,
        loadNotiList,
        resetNotiList,
        postNoti,
        loadNoti,
        postChatNoti,
        registerNotiToken,
        unRegisterNotiToken,
        markAsRead,
        markAsUnRead,
        deleteNoti

    }

}),
);
