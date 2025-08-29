// import firebaseApp from 'firebase/app';
// import "firebase/auth";
import PsConst from "../constant/ps_constants";
// import PsConfig from '@template1/config/PsConfig';
import { defineStore } from 'pinia'
import { ref } from 'vue'


export const usePsValueHolderState = defineStore('PsValueHolder',
{
    state: () => ({ 
        loginExpiryTime : ref(0),
        loginUserId : ref(''),
        locationId : ref(''),
        locationName : ref(''),
        subLocationId : ref(''),
        subLocationName : ref(''),
        locationLat : ref(''),
        locationLng : ref(''),    
        loginUserName : ref(''),
        userIdToVerify : ref(''),
        userNameToVerify : ref(''),
        userEmailToVerify : ref(''),
        userPasswordToVerify : ref(''),
        deviceToken : ref(''),
        notiSetting : ref('true'),
        isCustomCamera : ref(true),
        overAllTaxLabel : ref(''),
        overAllTaxValue : ref(''),
        shippingTaxLabel : ref(''),
        shopId : ref(''),
        messenger : ref(''),
        whatsApp : ref(''),
        phone : ref(''),
        shippingTaxValue : ref(''),
        appInfoVersionNo : ref(''),
        appInfoForceUpdate : ref(false),
        appInfoForceUpdateTitle : ref(''),
        appInfoForceUpdateMsg : ref(''),
        startDate : ref(''),
        endDate : ref(''),
        paypalEnabled : ref(''),
        stripeEnabled : ref(''),
        codEnabled : ref(''),
        bankEnabled : ref(''),
        publishKey : ref(''),
        depositPercent : ref(''),
        coolDownPeriod : ref(''),
        bidFee : ref(''),
        uploadFee : ref(''),
        memberShipDuration : ref(''),
        membershipAmount : ref(''),
        shippingId : ref(''),
        standardShippingEnable : ref(''),
        zoneShippingEnable : ref(''),
        noShippingEnable : ref(''),
        creditCardNo : ref(''),
        creditCardMonth : ref(''),
        creditCardYear : ref(''),
        creditCardCVC : ref(''),
        creditCardName : ref(''),
        creditCardValid : ref(false),
        resultStartTime : ref(0),
        userPassword : ref(''),
        languageCode : ref(''),
        showProfile : ref(''),
    }),

    actions: {
        loadData() {
            this.loginExpiryTime.value = localStorage.loginExpiryTime;
            this.locationId.value = localStorage.locationId;
            this.locationName.value = localStorage.locationName;
            this.subLocationId.value = localStorage.subLocationId;
            this.subLocationName.value = localStorage.subLocationName;
            this.locationLat.value = localStorage.locationLat;
            this.locationLng.value = localStorage.locationLng;
            this.loginUserId.value = localStorage.loginUserId;
            this.loginUserName.value = localStorage.loginUserName;
            this.userIdToVerify.value = localStorage.userIdToVerify;
            this.userNameToVerify.value = localStorage.userNameToVerify;
            this.userEmailToVerify.value = localStorage.userEmailToVerify;
            this.userPasswordToVerify.value = localStorage.userPasswordToVerify;
            this.deviceToken.value = localStorage.deviceToken;
            this.notiSetting.value = localStorage.notiSetting;
            // this.notiSetting.value = localStorage.notiSetting ?? appInfo('enableNotification');
            this.isCustomCamera.value = localStorage.isCustomCamera;
            this.overAllTaxLabel.value = localStorage.overAllTaxLabel;
            this.overAllTaxValue.value = localStorage.overAllTaxValue;
            this.shippingTaxLabel.value = localStorage.shippingTaxLabel;
            this.shopId.value = localStorage.shopId;
            this.messenger.value = localStorage.messenger;
            this.whatsApp.value = localStorage.whatsApp;
            this.phone.value = localStorage.phone;
            this.shippingTaxValue.value = localStorage.shippingTaxValue;
            this.appInfoVersionNo.value = localStorage.appInfoVersionNo;
            this.appInfoForceUpdate.value = localStorage.appInfoForceUpdate;
            this.appInfoForceUpdateTitle.value = localStorage.appInfoForceUpdateTitle;
            this.appInfoForceUpdateMsg.value = localStorage.appInfoForceUpdateMsg;
            this.startDate.value = localStorage.startDate;
            this.endDate.value = localStorage.endDate;
            this.paypalEnabled.value = localStorage.paypalEnabled;
            this.stripeEnabled.value = localStorage.stripeEnabled;
            this.codEnabled.value = localStorage.codEnabled;
            this.bankEnabled.value = localStorage.bankEnabled;
            this.publishKey.value = localStorage.publishKey;
            this.depositPercent.value = localStorage.depositPercent;
            this.coolDownPeriod.value = localStorage.coolDownPeriod;
            this.bidFee.value = localStorage.bidFee ;
            this.uploadFee.value = localStorage.uploadFee;
            this.memberShipDuration.value = localStorage.memberShipDuration;
            this.membershipAmount.value = localStorage.membershipAmount;
            this.depositPercent.value = localStorage.depositPercent;
            this.shippingId.value = localStorage.shippingId;
            this.standardShippingEnable.value = localStorage.standardShippingEnable;
            this.zoneShippingEnable.value = localStorage.zoneShippingEnable;
            this.noShippingEnable.value = localStorage.noShippingEnable;
            this.creditCardNo.value = localStorage.creditCardNo;
            this.creditCardValid.value = localStorage.creditCardValid;
            this.creditCardMonth.value = localStorage.creditCardMonth;
            this.creditCardYear.value = localStorage.creditCardYear;
            this.creditCardCVC.value = localStorage.creditCardCVC;
            this.creditCardName.value = localStorage.creditCardName;
            this.resultStartTime.value = localStorage.resultStartTime;
            this.userPassword.value = localStorage.userPassword;
            this.languageCode.value = localStorage.languageCode;
            // this.showProfile.value = localStorage.showProfile ?? appInfo('showUserProfile');
            this.showProfile.value = localStorage.showProfile;
        },
        replacelanguageCode(languageCode: string){
            localStorage.languageCode = languageCode;
            this.languageCode.value = languageCode;
        },
        replaceshowProfile(showProfile: string){
            localStorage.showProfile = showProfile;
            this.showProfile.value = showProfile;
            localStorage.showProfile = showProfile;
        },

        replaceCreditCard(creditCardNo: string,
                            creditCardMonth: string,
                            creditCardYear: string,
                            creditCardCVC: string,
                            creditCardName: string,
                            creditCardValid : Boolean) {

            localStorage.creditCardNo = creditCardNo;
            localStorage.creditCardMonth = creditCardMonth;
            localStorage.creditCardYear = creditCardYear;
            localStorage.creditCardCVC = creditCardCVC;
            localStorage.creditCardName = creditCardName;
            localStorage.creditCardValid = creditCardValid;

            this.creditCardNo.value = creditCardNo;
            this.creditCardMonth.value = creditCardMonth;
            this.creditCardYear.value = creditCardYear;
            this.creditCardCVC.value = creditCardCVC;
            this.creditCardName.value = creditCardName;
            this.creditCardValid.value = creditCardValid;
            
        },
        replaceLoginUserId(loginUserId : string ) {
            localStorage.loginUserId = loginUserId;
            this.loginUserId.value = loginUserId;
        },

        replaceNotiSetting(notiSetting : string ) {
            localStorage.notiSetting = notiSetting;
            this.notiSetting.value = notiSetting;
        },

        replacedeviceToken(deviceToken : string ) {
            localStorage.deviceToken = deviceToken;
            this.deviceToken.value = deviceToken;
        },


        replaceLoginUserName(loginUsername : string) {

            localStorage.loginUserName = loginUsername;
            this.loginUserName.value = loginUsername;
        },

        replaceLoginExpiryTime(time: number) {
            this.loginExpiryTime.value = time;
            localStorage.loginExpiryTime = this.loginExpiryTime;
        },

        replaceUserIdToVerify(userIdToVerify : string) {
            localStorage.userIdToVerify = userIdToVerify;
            this.userIdToVerify.value = userIdToVerify;
        },

        replaceUserNameToVerify(userNameToVerify : string) {
            localStorage.userNameToVerify = userNameToVerify;
            this.userNameToVerify.value = userNameToVerify;
        },

        replaceUserEmailToVerify(userEmailToVerify : string) {
            localStorage.userEmailToVerify = userEmailToVerify;
            this.userEmailToVerify.value = userEmailToVerify;
        },

        replaceUserPasswordToVerify(userPasswordToVerify : string) {
            localStorage.userPasswordToVerify = userPasswordToVerify;
            this.userPasswordToVerify.value = userPasswordToVerify;
        },

        replaceLocation(locationId : string, locationName : string, locationLat : string, locationLng: string) {
            localStorage.locationId = locationId;
            this.locationId.value = locationId;

            localStorage.locationName = locationName;
            this.locationName.value = locationName;

            localStorage.locationLat = locationLat;
            this.locationLat.value = locationLat;

            localStorage.locationLng = locationLng;
            this.locationLng.value = locationLng;
        },
        replaceSubLocation(subLocationId : string, subLocationName : string ) {
            localStorage.subLocationId = subLocationId;
            this.subLocationId.value = subLocationId;

            localStorage.subLocationName = subLocationName;
            this.subLocationName.value = subLocationName;

        },

        // Stripe Publish Key
        replacePublishKey(pubKey : string) {
            localStorage.publishKey = pubKey;
            this.publishKey.value = pubKey;
        },

        updateLoginExpiryTime() {
            const date = new Date()
            date.setDate(date.getDate() + 1); // set one day        
            this.replaceLoginExpiryTime(date.getTime());
        }
    },

})