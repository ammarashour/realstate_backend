import { reactive,ref } from 'vue';

import ShippingAndBillingParameterHolder from '@templateCore/object/holder/ShippingAndBillingParameterHolder';
import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import { defineStore } from 'pinia'
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';
import ShippingAndBillingAddress from '@templateCore/object/ShippingAndBillingAddress';
import ShippingAddress from '@templateCore/object/ShippingAddress';
import BillingAddress from '@templateCore/object/BillingAddress';
import ShippingAddressParameterHolder from '@templateCore/object/holder/ShippingAddressParameterHolder';
import BillingAddressParameterHolder from '@templateCore/object/holder/BillingAddressParameterHolder';
import ApiStatus from '@templateCore/object/ApiStatus';

export const useVendorShippingBillingStoreState = makeSeparatedStore((key: string) =>
    defineStore(`vendorShippingBillingStore/${key}`,
        () => {
            const isNoMoreRecord = reactive({
                value: false
            })
            const shippingAddressList = reactive<PsResource<ShippingAddress[]>>(new PsResource());
            const billingAddressList = reactive<PsResource<BillingAddress[]>>(new PsResource());
            const defaultShippingBillingAddress = reactive<PsResource<ShippingAndBillingAddress>>(new PsResource());

            const shippingAndBillingParameterHolder = reactive<ShippingAndBillingParameterHolder>(new ShippingAndBillingParameterHolder());
            const shippingAddressParameterHolder = reactive<ShippingAddressParameterHolder>(new ShippingAddressParameterHolder());
            const loading = reactive({
                value : false
            });

            let limit = ref(10);
            let offset: Number = 0;

            function updateShippingList(responseData : PsResource<ShippingAddress[]>) {

                if(shippingAddressList != null
                    && shippingAddressList.data != null
                    && shippingAddressList.data.length > 0
                    && offset != 0) {

                    if(responseData.data != null && responseData.data.length > 0) {
                        if(responseData.data?.length < limit.value){
                            isNoMoreRecord.value = true;
                        } else {
                            isNoMoreRecord.value = false;
                        }
                        shippingAddressList.data.push(...responseData.data);
                    }else {
                        isNoMoreRecord.value = true;
                    }

                    shippingAddressList.code = responseData.code;
                    shippingAddressList.status = responseData.status;
                    shippingAddressList.message = responseData.message;

                }else {
                    if(responseData.data?.length < limit.value || responseData.data == null){
                        isNoMoreRecord.value = true;
                    } else {
                        isNoMoreRecord.value = false;
                    }
                    shippingAddressList.data = responseData.data;
                    shippingAddressList.code = responseData.code;
                    shippingAddressList.status = responseData.status;
                    shippingAddressList.message = responseData.message;

                }

                if(shippingAddressList != null && shippingAddressList.data != null ) {
                    offset = shippingAddressList.data.length;
                }

            }

            function updateBillingList(responseData : PsResource<BillingAddress[]>) {

                if(billingAddressList != null
                    && billingAddressList.data != null
                    && billingAddressList.data.length > 0
                    && offset != 0) {

                    if(responseData.data != null && responseData.data.length > 0) {
                        if(responseData.data?.length < limit.value){
                            isNoMoreRecord.value = true;
                        } else {
                            isNoMoreRecord.value = false;
                        }
                        billingAddressList.data.push(...responseData.data);
                    }else {
                        isNoMoreRecord.value = true;
                    }

                    billingAddressList.code = responseData.code;
                    billingAddressList.status = responseData.status;
                    billingAddressList.message = responseData.message;

                }else {
                    if(responseData.data?.length < limit.value || responseData.data == null){
                        isNoMoreRecord.value = true;
                    } else {
                        isNoMoreRecord.value = false;
                    }
                    billingAddressList.data = responseData.data;
                    billingAddressList.code = responseData.code;
                    billingAddressList.status = responseData.status;
                    billingAddressList.message = responseData.message;

                }

                if(billingAddressList != null && billingAddressList.data != null ) {
                    offset = billingAddressList.data.length;
                }

            }

            async function resetDefaultShippingBillingAddress(loginUserId: String){

                loading.value = true;

                const responseData = await PsApiService.getDefaultShippingBillingAddress<ShippingAndBillingAddress>(new ShippingAndBillingAddress(), loginUserId);

                defaultShippingBillingAddress.code = responseData.code;
                defaultShippingBillingAddress.data = responseData.code == 204 ? null : responseData.data;
                defaultShippingBillingAddress.status = responseData.status;
                defaultShippingBillingAddress.message = responseData.message;

                loading.value = false;

                return defaultShippingBillingAddress;
            }

            async function resetShippingAddressList(loginUserId: String){

                offset = 0;

                loading.value = true;

                const responseData = await PsApiService.getShippingAddressList<ShippingAddress>(new ShippingAddress(), loginUserId);

                updateShippingList(responseData);

                loading.value = false;
            }

            async function resetBillingAddressList(loginUserId: String){

                offset = 0;

                loading.value = true;

                const responseData = await PsApiService.getBillingAddressList<BillingAddress>(new BillingAddress(), loginUserId);

                updateBillingList(responseData);

                loading.value = false;
            }

            async function addNewShippingAddress(loginUserId: String, holder: ShippingAddressParameterHolder){
                loading.value = true;

                const status = await PsApiService.postAddShippingAddress<ApiStatus>(new ApiStatus(), loginUserId, holder.toMap());

                loading.value = false;

                return status;
            }

            async function updateShippingAddress(loginUserId: String, holder: ShippingAddressParameterHolder){
                loading.value = true;

                const status = await PsApiService.postUpdateShippingAddress<ApiStatus>(new ApiStatus(), loginUserId, holder.toMap());

                loading.value = false;

                return status;
            }

            async function addNewBillingAddress(loginUserId: String, holder: BillingAddressParameterHolder){
                loading.value = true;

                const status = await PsApiService.postAddBillingAddress<ApiStatus>(new ApiStatus(), loginUserId, holder.toMap());

                loading.value = false;

                return status;
            }

            async function updateBillingAddress(loginUserId: String, holder: BillingAddressParameterHolder){
                loading.value = true;

                const status = await PsApiService.postUpdateBillingAddress<ApiStatus>(new ApiStatus(), loginUserId, holder.toMap());

                loading.value = false;

                return status;
            }

            return {
                limit,
                offset,
                loading,
                isNoMoreRecord,
                shippingAddressList,
                billingAddressList,
                defaultShippingBillingAddress,
                shippingAndBillingParameterHolder,
                shippingAddressParameterHolder,
                resetDefaultShippingBillingAddress,
                resetShippingAddressList,
                resetBillingAddressList,
                addNewShippingAddress,
                updateShippingAddress,
                addNewBillingAddress,
                updateBillingAddress,
            }
        }),
);
