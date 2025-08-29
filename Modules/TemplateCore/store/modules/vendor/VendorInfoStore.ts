import { reactive,ref } from 'vue';
import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import VendorInfo from '@templateCore/object/VendorInfo';
import { PsValueStore } from '@templateCore/store/modules/core/PsValueStore';
import AppInfoParameterHolder from '@templateCore/object/holder/AppInfoParameterHolder';
import PsConst from '@templateCore/object/constant/ps_constants';
import { defineStore } from 'pinia'

export const useVendorInfoStoreState = defineStore('VendorInfoStore',
 () => {
    let vendorInfo = reactive<PsResource<VendorInfo>>(new PsResource());
    let loading = reactive({
        value: false
    });

    let id: string = "";
    let realStartDate: string = '0';
    let realEndDate: string = '0';
    const psValueStore = PsValueStore();
    // const loginUserId = psValueStore.getLoginUserId();

    async function loadVendorInfo(vendorId: String) {

        loading.value = true;
        let loginUserId = psValueStore.getLoginUserId();

        const response = await PsApiService.getVendorInfo<VendorInfo>(new VendorInfo(), vendorId, loginUserId);
        console.log(response);
        vendorInfo.data = response.data;
        vendorInfo.code = response.code;
        vendorInfo.status = response.status;
        vendorInfo.message = response.message;

        psValueStore.replacePublishKey(vendorInfo.data.vendorStripePublishableKey);

        loading.value = false;

        return vendorInfo;

    }

    function isPaypalEnabled(){
        return vendorInfo?.data?.vendorPaypalEnabled == true ;
    }
    function isFlutterwaveEnabled(){
        return vendorInfo?.data?.vendorFlutterwaveEnabled == true ;
    }
    function isPaystackEnabled(){
        return vendorInfo?.data?.vendorPaystackEnabled == true ;
    }
    function isRazorEnabled(){
        return vendorInfo?.data?.vendorRazorEnabled == true ;
    }
    function isStripeEnabled(){
        return vendorInfo?.data?.vendorStripeEnabled == true ;
    }
    function isCodEnabled(){
        return vendorInfo?.data?.vendorCodEnabled == true ;
    }
    function getRazorKey(){
        return vendorInfo?.data?.vendorRazorKey;
    }
    function getPaystackKey(){
        return vendorInfo?.data?.vendorPaystackKey;
    }
    function getStripePublishedKey(){
        return vendorInfo?.data?.vendorStripePublishableKey;
    }

    function isDeliverySettingOn(){
        return vendorInfo?.data?.vendorDeliverySetting?.deliverySetting == PsConst.ONE;
    }
    function getDeliveryCharges(){
        return vendorInfo?.data?.vendorDeliverySetting?.deliveryCharges;
    }

    return{
        vendorInfo,
        loading,
        id,
        realStartDate,
        realEndDate,
        loadVendorInfo,
        isPaypalEnabled,
        isPaystackEnabled,
        isRazorEnabled,
        isCodEnabled,
        isStripeEnabled,
        getRazorKey,
        getPaystackKey,
        getStripePublishedKey,
        isDeliverySettingOn,
        getDeliveryCharges,
        isFlutterwaveEnabled
    }

})
