import { reactive } from 'vue';

import ProductParameterHolder from '@templateCore/object/holder/ProductParameterHolder';
import PhoneParameterHolder from '@templateCore/object/holder/PhoneParameterHolder';
import PsApiService from '@templateCore/api/PsApiService';
import Product from '@templateCore/object/Product';
import Phone from '@templateCore/object/Phone';
import PsResource from '@templateCore/api/common/PsResource';
import { defineStore } from 'pinia'
import DefaultPhoto from '@templateCore/object/DefaultPhoto';
import LocationParameterHolder from '@templateCore/object/holder/LocationParameterHolder';
import ApiStatus from '@templateCore/object/ApiStatus';
import ItemEntryParameterHolder from '@templateCore/object/holder/ItemEntryParameterHolder';
import UserDeleteItemParameterHolder from '@templateCore/object/holder/UserDeleteItemParameterHolder';
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const usePhoneStore = makeSeparatedStore((key: string) =>
    defineStore(`phoneStore/${key}`,
        () => {

            const itemList = reactive<PsResource<Product[]>>(new PsResource());
            const phoneList = reactive<PsResource<Phone[]>>(new PsResource());
            const item = reactive<PsResource<Product>>(new PsResource());
            const loading = reactive({
                value: false
            });

            let limit: Number = 10;
            let offset: Number = 0;
            const isNoMoreRecord = reactive({
                value: false
            })

            let id: string = "";
            let paramHolder = reactive<ProductParameterHolder>(new ProductParameterHolder().getRecentParameterHolder());

            let phoneparamHolder = reactive<PhoneParameterHolder>(new PhoneParameterHolder().getRecentParameterHolder());


            function updateProductList(responseData: PsResource<Product[]>) {

                if (itemList != null
                    && itemList.data != null
                    && itemList.data.length > 0
                    && offset != 0) {


                    if (responseData.data != null && responseData.data.length > 0) {
                        if (responseData.data?.length < limit) {
                            isNoMoreRecord.value = true;
                        } else {
                            isNoMoreRecord.value = false;
                        }
                        itemList.data.push(...responseData.data);
                    } else {
                        isNoMoreRecord.value = true;
                    }
                    itemList.code = responseData.code;
                    itemList.status = responseData.status;
                    itemList.message = responseData.message;

                } else {
                    if (responseData.data?.length < limit || responseData.data == null) {
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

            function updatePhoneList(responseData: PsResource<Phone[]>) {

                if (phoneList != null
                    && phoneList.data != null
                    && phoneList.data.length > 0
                    && offset != 0) {


                    if (responseData.data != null && responseData.data.length > 0) {
                        if (responseData.data?.length < limit) {
                            isNoMoreRecord.value = true;
                        } else {
                            isNoMoreRecord.value = false;
                        }
                        phoneList.data.push(...responseData.data);
                    } else {
                        isNoMoreRecord.value = true;
                    }
                    phoneList.code = responseData.code;
                    phoneList.status = responseData.status;
                    phoneList.message = responseData.message;

                } else {
                    if (responseData.data?.length < limit || responseData.data == null) {
                        isNoMoreRecord.value = true;
                    } else {
                        isNoMoreRecord.value = false;
                    }
                    phoneList.data = responseData.data;
                    phoneList.code = responseData.code;
                    phoneList.status = responseData.status;
                    phoneList.message = responseData.message;


                }

                if (phoneList != null && phoneList.data != null) {
                    offset = phoneList.data.length;
                }

            }


            function filterPhoneUpdate(loginUserId: string, holder: PhoneParameterHolder) {
                resetSearchSubCategoryList(loginUserId, holder);
            }

            async function resetSearchSubCategoryList(loginUserId: string, holder: PhoneParameterHolder) {

                offset = 0;

                loading.value = true;
                // alert("here");

                const responseData = await PsApiService.getPhoneList<Phone>(new Phone(), loginUserId, limit, offset, holder.toMap());

                updatePhoneList(responseData);

                loading.value = false;

            }

            async function loadPhoneCountryCode(loginUserId: string, holder: PhoneParameterHolder) {

                loading.value = true;

                const responseData = await PsApiService.getPhoneList<Phone>(new Phone(), loginUserId, limit, offset, holder.toMap());

                updatePhoneList(responseData);
                loading.value = false;


            }

            async function loadItemList(loginUserId: string, holder: ProductParameterHolder) {

                loading.value = true;

                const responseData = await PsApiService.getItemListByUserId<Product>(new Product(), limit, offset, loginUserId, holder.toMap());

                updateProductList(responseData);
                loading.value = false;


            }


            async function resetProductList(loginUserId: string, holder: ProductParameterHolder) {

                offset = 0;

                loading.value = true;

                const responseData = await PsApiService.getItemListByUserId<Product>(new Product(), limit, offset, loginUserId, holder.toMap());
                updateProductList(responseData);

                loading.value = false;

            }

            async function loadItem(itemId: string, loginUserId: string) {

                loading.value = true;

                const responseData = await PsApiService.getItemDetail<Product>(new Product(), itemId, loginUserId);
                item.data = responseData.data;
                item.code = responseData.code;
                item.status = responseData.status;
                item.message = responseData.message;
                loading.value = false;

                return item;

            }

            async function submitItemEntry(holder: ItemEntryParameterHolder, loginUserId: string): Promise<PsResource<Product>> {
                loading.value = true;
                const responseData = await PsApiService.postItemEntry<Product>(new Product(), holder.toMap(), loginUserId);
                item.data = responseData.data;
                item.code = responseData.code;
                item.status = responseData.status;
                item.message = responseData.message;
                loading.value = false;

                return responseData;

            }

            async function postItemImageUpload(loginUserId: string, imgParentId: string, imgType: string, ordering: Number, imgDesc: string, imageFile: any, imgId: string): Promise<PsResource<DefaultPhoto>> {
                loading.value = true;

                const returnData = await PsApiService.postItemImageUpload<DefaultPhoto>(new DefaultPhoto(), imgParentId, imgType, ordering, imgDesc, imageFile, imgId, loginUserId);

                loading.value = false;

                return returnData;
            }

            async function postReorderItemImage(loginUserId: string, reorderImages: any[]): Promise<PsResource<ApiStatus>> {
                loading.value = true;

                const status = await PsApiService.postReorderItemImage<ApiStatus>(new ApiStatus(), loginUserId, reorderImages);
                loading.value = false;

                return status;
            }

            async function postItemVideoUpload(img_id: string, imgParentId: string, videoFile: File, loginUserId: String): Promise<PsResource<DefaultPhoto>> {
                loading.value = true;

                const returnData = await PsApiService.postItemVideoUpload<DefaultPhoto>(new DefaultPhoto(), img_id, imgParentId, videoFile, loginUserId);

                loading.value = false;

                return returnData;
            }

            async function postItemVideoThumbUpload(imgParentId: string, videoThumbFile: File, loginUserId: String): Promise<PsResource<DefaultPhoto>> {
                loading.value = true;

                const returnData = await PsApiService.postItemVideoThumbUpload<DefaultPhoto>(new DefaultPhoto(), imgParentId, videoThumbFile, loginUserId);
                loading.value = false;

                return returnData;
            }

            function getURLforList() {
                let url = '';
                if (url == '' && paramHolder.searchTerm != '') {
                    url = '?searchterm=' + paramHolder.searchTerm;
                } else if (paramHolder.searchTerm != '') {
                    url = url + '&searchterm=' + paramHolder.searchTerm;
                }

                if (url == '' && paramHolder.catId != '') {
                    url = '?cat_id=' + paramHolder.catId;
                } else if (paramHolder.catId != '') {
                    url = url + '&cat_id=' + paramHolder.catId;
                }

                if (url == '' && paramHolder.catName != '') {
                    url = '?cat_name=' + paramHolder.catName;
                } else if (paramHolder.catName != '') {
                    url = url + '&cat_name=' + paramHolder.catName;
                }

                if (url == '' && paramHolder.subCatId != '') {
                    url = '?sub_cat_id=' + paramHolder.subCatId;
                } else if (paramHolder.subCatId != '') {
                    url = url + '&sub_cat_id=' + paramHolder.subCatId;
                }

                if (url == '' && paramHolder.subCatName != '') {
                    url = '?sub_cat_name=' + paramHolder.subCatName;
                } else if (paramHolder.subCatName != '') {
                    url = url + '&sub_cat_name=' + paramHolder.subCatName;
                }


                if (url == '' && paramHolder.itemCurrencyId != '') {
                    url = '?item_currency_id=' + paramHolder.itemCurrencyId;
                } else if (paramHolder.itemCurrencyId != '') {
                    url = url + '&item_currency_id=' + paramHolder.itemCurrencyId;
                }

                if (url == '' && paramHolder.itemLocationId != '') {
                    url = '?item_location_id=' + paramHolder.itemLocationId;
                } else if (paramHolder.itemLocationId != '') {
                    url = url + '&item_location_id=' + paramHolder.itemLocationId;
                }

                if (url == '' && paramHolder.itemLocationTownship != '') {
                    url = '?item_location_township_id=' + paramHolder.itemLocationTownship;
                } else if (paramHolder.itemLocationTownship != '') {
                    url = url + '&item_location_township_id=' + paramHolder.itemLocationTownship;
                }

                if (url == '' && paramHolder.maxPrice != '') {
                    url = '?max_price=' + paramHolder.maxPrice;
                } else if (paramHolder.maxPrice != '') {
                    url = url + '&max_price=' + paramHolder.maxPrice;
                }

                if (url == '' && paramHolder.minPrice != '') {
                    url = '?min_price=' + paramHolder.minPrice;
                } else if (paramHolder.minPrice != '') {
                    url = url + '&min_price=' + paramHolder.minPrice;
                }

                if (url == '' && paramHolder.lat != '') {
                    url = '?lat=' + paramHolder.lat;
                } else if (paramHolder.lat != '') {
                    url = url + '&lat=' + paramHolder.lat;
                }

                if (url == '' && paramHolder.lng != '') {
                    url = '?lng=' + paramHolder.lng;
                } else if (paramHolder.lng != '') {
                    url = url + '&lng=' + paramHolder.lng;
                }

                if (url == '' && paramHolder.mile != '') {
                    url = '?mile=' + paramHolder.mile;
                } else if (paramHolder.mile != '') {
                    url = url + '&mile=' + paramHolder.mile;
                }

                if (url == '' && paramHolder.addedUserId != '') {
                    url = '?added_user_id=' + paramHolder.addedUserId;
                } else if (paramHolder.addedUserId != '') {
                    url = url + '&added_user_id=' + paramHolder.addedUserId;
                }

                if (url == '' && paramHolder.isPaid != '') {
                    url = '?ad_post_type=' + paramHolder.isPaid;
                } else if (paramHolder.isPaid != '') {
                    url = url + '&ad_post_type=' + paramHolder.isPaid;
                }

                if (url == '' && paramHolder.orderBy != '') {
                    url = '?order_by=' + paramHolder.orderBy;
                } else if (paramHolder.orderBy != '') {
                    url = url + '&order_by=' + paramHolder.orderBy;
                }

                if (url == '' && paramHolder.orderType != '') {
                    url = '?order_type=' + paramHolder.orderType;
                } else if (paramHolder.orderType != '') {
                    url = url + '&order_type=' + paramHolder.orderType;
                }

                if (url == '' && paramHolder.status != '') {
                    url = '?status=' + paramHolder.status;
                } else if (paramHolder.status != '') {
                    url = url + '&status=' + paramHolder.status;
                }

                if (url == '' && paramHolder.isSoldOut != '') {
                    url = '?is_sold_out=' + paramHolder.isSoldOut;
                } else if (paramHolder.isSoldOut != '') {
                    url = url + '&is_sold_out=' + paramHolder.isSoldOut;
                }

                if (url == '' && paramHolder.isDiscount != '') {
                    url = '?is_discount=' + paramHolder.isDiscount;
                } else if (paramHolder.isDiscount != '') {
                    url = url + '&is_discount=' + paramHolder.isDiscount;
                }

                if (url == '' && paramHolder.paidStatus != '') {
                    url = '?ad_post_type=' + paramHolder.paidStatus;
                } else if (paramHolder.paidStatus != '') {
                    url = url + '&ad_post_type=' + paramHolder.paidStatus;
                }

                if (url == '' && paramHolder.isPaid != '') {
                    url = '?is_paid=' + paramHolder.isPaid;
                } else if (paramHolder.isPaid != '') {
                    url = url + '&is_paid=' + paramHolder.isPaid;
                }

                if (url == '' && paramHolder.productRelation.length > 0) {
                    url = '?product_relation=';

                    let keys = '';
                    let values = '';

                    paramHolder.productRelation.forEach((custom, index) => {
                        if (custom.core_keys_id != '' && custom.value != '') {
                            if (index == 0) {
                                keys = custom.core_keys_id;
                                values = custom.value;
                            } else {
                                keys = keys + '___' + custom.core_keys_id;
                                values = values + '___' + custom.value;
                            }
                        }
                    })
                    url = url + keys + '-----' + values;

                } else if (paramHolder.productRelation.length > 0) {
                    url = url + '&product_relation=';
                    let keys = '';
                    let values = '';
                    paramHolder.productRelation.forEach((custom, index) => {
                        if (custom.core_keys_id != '' && custom.value != '') {
                            if (index == 0) {
                                keys = custom.core_keys_id;
                                values = custom.value;
                            } else {
                                keys = keys + '___' + custom.core_keys_id;
                                values = values + '___' + custom.value;
                            }

                        }
                    })
                    url = url + keys + '-----' + values;
                }

                return url;
            }
            async function userDeleteItem(loginUserId, itemId): Promise<PsResource<ApiStatus>> {

                const holder = new UserDeleteItemParameterHolder();
                holder.itemId = itemId;

                loading.value = true;

                const returnedData = await PsApiService.deleteItem<ApiStatus>(new ApiStatus(), loginUserId, holder.toMap());

                loading.value = false;

                return returnedData;
            }

            async function refleshProductList(loginUserId: string, holder: ProductParameterHolder) {

                loading.value = true;
                const tempOffset = 0;
                const tempLimit = itemList.data.length;
                const responseData = await PsApiService.getItemListByUserId<Product>(new Product(), tempLimit, tempOffset, loginUserId, holder.toMap());
                itemList.data = responseData.data;
                itemList.code = responseData.code;
                itemList.status = responseData.status;
                itemList.message = responseData.message;
                loading.value = false;
            }


            return {
                itemList,
                item,
                loading,
                limit,
                offset,
                isNoMoreRecord,
                id,
                paramHolder,
                phoneparamHolder,
                phoneList,
                updateProductList,
                loadItemList,
                resetProductList,
                loadItem,
                submitItemEntry,
                postItemImageUpload,
                postReorderItemImage,
                postItemVideoUpload,
                postItemVideoThumbUpload,
                getURLforList,
                userDeleteItem,
                refleshProductList,
                loadPhoneCountryCode,
                filterPhoneUpdate
            }

        }),
);
