import { reactive,ref } from 'vue';
import { defineStore  } from 'pinia';

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';
import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import PsStatus from '@templateCore/api/common/PsStatus';
import ApiStatus from '@templateCore/object/ApiStatus';
import OrderHistoryList from '@templateCore/object/OrderHistoryList';
import OrderHistoryParameterHolder from '@templateCore/object/holder/OrderHistoryParameterHolder';

export const useOrderHistoryStore = makeSeparatedStore((key: string) =>
    defineStore(`orderHistoryStore/${key}`, () => {

        const orderHistoryList = reactive<PsResource<OrderHistoryList[]>>(new PsResource());
        const holder = reactive<OrderHistoryParameterHolder>(new OrderHistoryParameterHolder());

        const loading = reactive({
            value: false
        });

        let limit = ref(10);
        let offset: Number = 0;
        const isNoMoreRecord = reactive({
            value: false
        })

        function updateOrderHistoryList(responseData: PsResource<OrderHistoryList[]>) {

            if (orderHistoryList != null
                && orderHistoryList.data != null
                && orderHistoryList.data.length > 0
                && offset != 0) {


                if (responseData.data != null && responseData.data.length > 0) {
                    if(responseData.data?.length < limit.value){
                        isNoMoreRecord.value = true;
                    } else {
                        isNoMoreRecord.value = false;
                    }
                    orderHistoryList.data.push(...responseData.data);
                } else {
                    isNoMoreRecord.value = true;
                }
                orderHistoryList.code = responseData.code;
                orderHistoryList.status = responseData.status;
                orderHistoryList.message = responseData.message;

            } else {
                if(responseData.data?.length < limit.value || responseData.data == null){
                    isNoMoreRecord.value = true;
                } else {
                    isNoMoreRecord.value = false;
                }
                orderHistoryList.data = responseData.data;
                orderHistoryList.code = responseData.code;
                orderHistoryList.status = responseData.status;
                orderHistoryList.message = responseData.message;


            }

            if (orderHistoryList != null && orderHistoryList.data != null) {
                offset = orderHistoryList.data.length;
            }

        }

        // async function resetVendorList(loginUserId: string, ownerUserId: string) {

        //     offset = 0;
        //     loading.value = true;

        //     const responseData = await PsApiService.getVendorListByOwnerId<Vendor>(new Vendor(), loginUserId, ownerUserId, limit.value, offset);

        //     updateOrderHistoryList(responseData);
        //     loading.value = false;
        // }

        // async function loadVendorList(loginUserId: string, ownerUserId: string) {

        //     loading.value = true;

        //     const responseData = await PsApiService.getVendorListByOwnerId<Vendor>(new Vendor(), loginUserId, ownerUserId, limit.value, offset);

        //     updateOrderHistoryList(responseData);
        //     loading.value = false;
        // }

        async function resetOrderHistoryList(loginUserId: string, holder: OrderHistoryParameterHolder) {

            loading.value = true;

            const responseData = await PsApiService.postOrderHistoryList<OrderHistoryList>(new OrderHistoryList(), loginUserId, limit.value, offset, holder.toMap());
            orderHistoryList.data = responseData.data;
            orderHistoryList.code = responseData.code;
            orderHistoryList.status = responseData.status;
            orderHistoryList.message = responseData.message;
            loading.value = false;

            return orderHistoryList;

        }

        async function downloadOrderHistory(loginUserId: string, orderId: string){
            loading.value = true;

            const status = await PsApiService.downloadOrderHistory<ApiStatus>(new ApiStatus(), loginUserId, orderId);

            loading.value = false;

            return status;
        }

        return {
            orderHistoryList,
            loading,
            limit,
            offset,
            isNoMoreRecord,
            holder,
            updateOrderHistoryList,
            resetOrderHistoryList,
            downloadOrderHistory,
        }
    }),
);
