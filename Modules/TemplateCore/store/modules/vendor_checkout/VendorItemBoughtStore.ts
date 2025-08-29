import { reactive,ref } from 'vue';

import VendorItemBoughtParameterHolder from '@templateCore/object/holder/VendorItemBoughtParameterHolder';
import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import ApiStatus from '@templateCore/object/ApiStatus';
import VendorInfo from '../../../object/VendorInfo';
import { defineStore } from 'pinia'
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';

export const useVendorItemBoughtStore = makeSeparatedStore((key: string) =>
    defineStore(`vendorItemBoughtStore/${key}`,
        () => {

            const vendorInfo = reactive<PsResource<VendorInfo>>(new PsResource());
            const paymentNonce = ref('');

            const loading = reactive({
                value: false
            });

            let paramHolder = reactive<VendorItemBoughtParameterHolder>(new VendorItemBoughtParameterHolder());

            async function postVendorItemBought(loginUserId: string, holder:VendorItemBoughtParameterHolder) : Promise<PsResource<ApiStatus>>  {

                loading.value = true;
        
                const vendorItemBoughtRespone = await PsApiService.postVendorItemBought<ApiStatus>(new ApiStatus(), loginUserId, holder.toMap());
        
                loading.value = false;
        
                return vendorItemBoughtRespone;
        
            }

            async function getVendorPaypalToken(vendorId,loginUserId) {
                loading.value = true;
        
                const status = await PsApiService.getVendorPaypalToken<ApiStatus>(new ApiStatus(), vendorId ,loginUserId);
        
                loading.value = false;
        
                return status;
            }

            async function getVendorInfo(vendorId,loginUserId) {
                loading.value = true;

                const responseData = await PsApiService.postShippingAndBilling<VendorInfo>(new VendorInfo(), vendorId ,loginUserId);
                vendorInfo.data = responseData.data;
                vendorInfo.code = responseData.code;
                vendorInfo.status = responseData.status;
                vendorInfo.message = responseData.message;
                loading.value = false;

                return vendorInfo;
            }

            return {
                paramHolder,
                postVendorItemBought,
                getVendorPaypalToken,
                getVendorInfo,
                paymentNonce,
                loading,
            }

        }),
);
