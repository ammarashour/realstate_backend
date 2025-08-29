import { reactive, ref } from 'vue';
import PsApiService from '@templateCore/api/PsApiService';
import Product from '@templateCore/object/Product';
import PsResource from '@templateCore/api/common/PsResource';
import FavouriteParameterHolder from '@templateCore/object/holder/FavouriteParameterHolder';
import { defineStore } from 'pinia'

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const useFavouriteItemStoreState = makeSeparatedStore((key: string) =>
    defineStore(`FavouriteItemStore/${key}`,
        () => {

            const favouriteItemList = reactive<PsResource<Product[]>>(new PsResource());
            const item = reactive<PsResource<Product>>(new PsResource());
            const loading = reactive({
                value: false
            });

            let limit = ref(12);
            let offset: Number = 0;
            const isNoMoreRecord = reactive({
                value: false
            })

            let id: string = "";

            function updateItemList(responseData: PsResource<Product[]>) {

                if (favouriteItemList != null
                    && favouriteItemList.data != null
                    && favouriteItemList.data.length > 0
                    && offset != 0) {

                    if (responseData.data != null && responseData.data.length > 0) {
                        if(responseData.data?.length < limit.value){
                            isNoMoreRecord.value = true;
                        } else {
                            isNoMoreRecord.value = false;
                        }
                        favouriteItemList.data.push(...responseData.data);
                    } else {
                        isNoMoreRecord.value = true;
                    }
                    favouriteItemList.code = responseData.code;
                    favouriteItemList.status = responseData.status;
                    favouriteItemList.message = responseData.message;

                } else {
                    if(responseData.data?.length < limit.value || responseData.data == null){
                        isNoMoreRecord.value = true;
                    } else {
                        isNoMoreRecord.value = false;
                    }

                    favouriteItemList.data = responseData.data;
                    favouriteItemList.code = responseData.code;
                    favouriteItemList.status = responseData.status;
                    favouriteItemList.message = responseData.message;

                }

                if (favouriteItemList != null && favouriteItemList.data != null) {
                    offset = favouriteItemList.data.length;
                }

            }

            async function loadFavouriteItemList(loginUserId: string) {

                loading.value = true;

                const responseData = await PsApiService.getFavouritesList<Product>(new Product(), loginUserId, limit.value, offset);

                updateItemList(responseData);

                loading.value = false;

            }


            async function resetFavouriteItemList(loginUserId: string) {

                offset = 0;

                loading.value = true;

                const responseData = await PsApiService.getFavouritesList<Product>(new Product(), loginUserId, limit.value, offset);

                updateItemList(responseData);

                loading.value = false;

            }

            async function postFavourite(itemId, userId) {
                const parmaHolder = new FavouriteParameterHolder;
                parmaHolder.itemId = itemId;
                parmaHolder.userId = userId;

                const responseData = await PsApiService.postFavourite<Product>(new Product(), userId, parmaHolder.toMap());

                return responseData;
            }

            async function refleshFavouriteItemList(loginUserId: string) {

                loading.value = true;
                const tempOffset = 0;
                const templimit = favouriteItemList.data.length;
                const responseData = await PsApiService.getFavouritesList<Product>(new Product(),loginUserId, templimit, tempOffset );
                favouriteItemList.data = responseData.data;
                favouriteItemList.code = responseData.code;
                favouriteItemList.status = responseData.status;
                favouriteItemList.message = responseData.message;
                loading.value = false;
            }

            return {
                favouriteItemList,
                item,
                loading,
                limit,
                offset,
                isNoMoreRecord,
                id,
                updateItemList,
                loadFavouriteItemList,
                resetFavouriteItemList,
                postFavourite,
                refleshFavouriteItemList
            }

        }),
);

