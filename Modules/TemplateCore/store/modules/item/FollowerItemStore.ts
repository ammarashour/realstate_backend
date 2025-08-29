import { reactive,ref } from 'vue';

import FollowItemParameterHolder from '@templateCore/object/holder/FollowItemParameterHolder';
import PsApiService from '@templateCore/api/PsApiService';
import Product from '@templateCore/object/Product';
import PsResource from '@templateCore/api/common/PsResource';
import PsUtils from '@templateCore/utils/PsUtils';
import { defineStore } from 'pinia'
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const useFollowerItemStoreState = makeSeparatedStore((key: string) =>
    defineStore(`followerItemStore/${key}`,
        () => {

        const isNoMoreRecord = reactive({
            value: false
        })
        const itemList = reactive<PsResource<Product[]>>(new PsResource());
        const item = reactive<PsResource<Product>>(new PsResource());
        const loading = reactive({
            value: false
        });

        let limit = ref(10);
        let offset: Number = 0;

        let id: string = "";

        function updateProductList(responseData: PsResource<Product[]>) {

            if (itemList != null
                && itemList.data != null
                && itemList.data.length > 0
                && offset != 0) {

                if (responseData.data != null  && responseData.data.length > 0) {
                    if(responseData.data?.length < limit.value){
                        isNoMoreRecord.value = true;
                    } else {
                        isNoMoreRecord.value = false;
                    }
                    itemList.data.push(...responseData.data);
                }else {
                    isNoMoreRecord.value = true;
                }
                itemList.code = responseData.code;
                itemList.status = responseData.status;
                itemList.message = responseData.message;

            } else {
                if(responseData.data?.length < limit.value || responseData.data == null){
                    isNoMoreRecord.value = true;
                } else {
                    isNoMoreRecord.value = false;
                }
                itemList.data = responseData.data;
                itemList.code = responseData.code;
                itemList.status = responseData.status;
                itemList.message = responseData.message;
            }

            if (itemList != null && itemList.data != null) {
                offset = itemList.data.length;
            }

        }

        async function loadItemList(loginUserId:string,holder: FollowItemParameterHolder) {

            loading.value = true;

            const responseData = await PsApiService.getFollowerItemListByUserId<Product>(new Product(), limit.value, offset, loginUserId, holder.toMap());

            updateProductList(responseData);

            loading.value = false;


        }


        async function resetProductList(loginUserId:string,holder: FollowItemParameterHolder) {
            PsUtils.log(holder);
            offset = 0;

            loading.value = true;

            const responseData = await PsApiService.getFollowerItemListByUserId<Product>(new Product(), limit.value, offset, loginUserId, holder.toMap());

            updateProductList(responseData);

            loading.value = false;

        }

        async function refleshFollowerItemList(loginUserId: string, holder: FollowItemParameterHolder) {

            loading.value = true;
            const tempOffset = 0;
            const templimit = itemList.data.length;
            const responseData = await PsApiService.getFollowerItemListByUserId<Product>(new Product(), templimit, tempOffset, loginUserId, holder.toMap());
            itemList.data = responseData.data;
            itemList.code = responseData.code;
            itemList.status = responseData.status;
            itemList.message = responseData.message;
            loading.value = false;
        }

        return {
            isNoMoreRecord,
            itemList,
            item,
            loading,
            limit,
            offset,
            id,
            loadItemList,
            resetProductList,
            refleshFollowerItemList
        }

    }),
);
