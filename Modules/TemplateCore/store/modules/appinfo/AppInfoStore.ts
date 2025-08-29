import { reactive,ref } from 'vue';
import PsApiService from '@templateCore/api/PsApiService';
import PsResource from '@templateCore/api/common/PsResource';
import PsAppInfo from '@templateCore/object/PsAppInfo';
import PsApi from '@templateCore/api/common/PsApi';
import { PsValueStore } from '@templateCore/store/modules/core/PsValueStore';
import AppInfoParameterHolder from '@templateCore/object/holder/AppInfoParameterHolder';
import PsConst from '@templateCore/object/constant/ps_constants';
import { defineStore } from 'pinia'

export const usePsAppInfoStoreState = defineStore('AppInfoStore',
 () => {
    let appInfo = reactive<PsResource<PsAppInfo>>(new PsResource());
    let loading = reactive({
        value: false
    });
    let isThumbnailGenerate : boolean = false;

    let id: string = "";
    let realStartDate: string = '0';
    let realEndDate: string = '0';

    async function loadAppInfo(holder: AppInfoParameterHolder) {

        loading.value = true;
        // if (PsValueStore.psValueStore == null || PsValueStore.psValueStore.startDate == null) {
        //     realStartDate = PsUtils.formatDate(new Date());
        // } else {
        //     realStartDate = PsValueStore.psValueStore.endDate;

        // }

        // realEndDate = PsUtils.formatDate(new Date());

        // holder.startDate = realStartDate;
        // holder.endDate = realEndDate;

        let userId = PsConst.NO_LOGIN_USER;
        if(holder){
            userId = holder.userId;
        }

        const psValueStore = PsValueStore();
        const response = await PsApiService.postPsAppInfo<PsAppInfo>(new PsAppInfo(), userId);

        appInfo.data = response.data;
        appInfo.code = response.code;
        appInfo.status = response.status;
        appInfo.message = response.message;

        if(appInfo.psAppSetting?.isThumbnailGenerate == PsConst.ONE){
            isThumbnailGenerate = true;
        }

        psValueStore.replacePublishKey(appInfo.data.stripePublishableKey);

        loading.value = false;

        return appInfo;

    }

    async function loadAppInfoProps(appInfoObj: PsResource<PsAppInfo>) {

        loading.value = true;

        const psValueStore = PsValueStore();
        // console.log(appInfoObj.original);
        const response = await PsApi.wrapDataFromProps(new PsAppInfo(), appInfoObj.original);

        appInfo.data = response.data;
        appInfo.code = response.code;
        appInfo.status = response.status;
        appInfo.message = response.message;

        if(appInfo.psAppSetting?.isThumbnailGenerate == PsConst.ONE){
            isThumbnailGenerate = true;
        }

        psValueStore.replacePublishKey(appInfo.data.stripePublishableKey);

        loading.value = false;

        return appInfo;

    }

    function isPaidAppOn() {
        return appInfo?.data?.psAppSetting?.isPaidApp ==  PsConst.ONE;
    }

    function isPromoteEnable() {
        return appInfo?.data?.psAppSetting?.isPromoteEnable ==  PsConst.ONE;
    }

    function isNoPriceSettingOn() {
        return appInfo?.data?.psAppSetting?.SelectedPriceType ==  PsConst.NO_PRICE;
    }

    function isShowItemVideo(){
       return appInfo.data.mobileSetting.is_show_item_video == PsConst.ONE;
    }

    function isAllPaymentDisable() : boolean{
        if (appInfo?.data != null &&
            appInfo?.data.inAppPurchasedEnabled == PsConst.ZERO &&
            appInfo?.data.stripeEnable == PsConst.ZERO &&
            appInfo?.data.paypalEnable == PsConst.ZERO &&
            appInfo?.data.payStackEnabled == PsConst.ZERO &&
            appInfo?.data.razorEnable == PsConst.ZERO &&
            appInfo?.data.offlineEnabled == PsConst.ZERO) {
            return true;
        } else {
            return false;
        }
    }

    function isSubmitButtonShow (roleId : number , isVerifybluemark : number) : boolean{
        if (appInfo.data?.uploadSetting == 'admin') {
            if (roleId == PsConst.ONE) {
                return true;
            } else {
                return false;
            }
        }
        else if (appInfo.data?.uploadSetting == 'admin-bluemark') {
            if (roleId == PsConst.ONE || isVerifybluemark == PsConst.ONE) {
                return true;
            } else {
                return false;
            }
        }
        else (appInfo.data?.uploadSetting == 'all')
            return true;

    }

    function maxImgUploadOfItem(){
        return appInfo.data.psAppSetting.maxImgUploadOfItem;
    }

    function isShowSubCategory(){
        return appInfo?.data?.mobileSetting.is_show_subcategory == PsConst.ONE ;
    }

    function isShowDiscount(){
        return appInfo.data?.mobileSetting?.is_show_discount == PsConst.ONE ;
    }

    function isShowSubLocation() {
        return appInfo.data?.psAppSetting?.isSubLocation == PsConst.ONE ;
    }

    function isFilterLocationOn() {
        return appInfo.data?.mobileSetting?.no_filter_with_location_on_map == PsConst.ONE ;
    }

    function selectPriceType (priceType : String){
        return appInfo.data?.psAppSetting?.SelectedPriceType == priceType;
    }

    function isVendorSettingOn() {
        return appInfo.data?.vendorConfig.vendor_feature_setting == 1 ? true : false;
    }

    function isVendorCheckoutSettingOn() {
        return appInfo.data?.vendorConfig.vendor_checkout_setting == 1 ? true : false;
    }

    function vendorSubScriptionSetting() {
        return appInfo.data?.vendorConfig.vendor_subscription_setting;
    }

    function isSoldOutFeatureSettingOn() {
        return appInfo.data?.psAppSetting.SoldOutFeatureSetting == 1 ? true : false;
    }

    return{
        appInfo,
        loading,
        id,
        isThumbnailGenerate,
        realStartDate,
        realEndDate,
        maxImgUploadOfItem,
        loadAppInfo,
        isShowItemVideo,
        isAllPaymentDisable,
        isSubmitButtonShow,
        isShowSubCategory,
        isShowDiscount,
        selectPriceType,
        isPaidAppOn,
        isPromoteEnable,
        isShowSubLocation,
        isFilterLocationOn,
        isNoPriceSettingOn,
        isVendorSettingOn,
        vendorSubScriptionSetting,
        isVendorCheckoutSettingOn,
        isSoldOutFeatureSettingOn,
        loadAppInfoProps
    }

})
