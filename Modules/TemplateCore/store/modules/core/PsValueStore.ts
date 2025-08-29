// import firebaseApp from 'firebase/app';
// import "firebase/auth";
// import PsConst from "@templateCore/object/constant/ps_constants";
// import PsConfig from '@template1/config/PsConfig';
import { defineStore } from 'pinia'
import { ref } from 'vue'


export const PsValueStore  = defineStore('PsValueStore ',
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
            this.loginExpiryTime = localStorage.loginExpiryTime;
            this.locationId = localStorage.locationId;
            this.locationName = localStorage.locationName;
            this.subLocationId = localStorage.subLocationId;
            this.subLocationName = localStorage.subLocationName;
            this.locationLat = localStorage.locationLat;
            this.locationLng = localStorage.locationLng;
            this.loginUserId = localStorage.loginUserId;
            this.loginUserName = localStorage.loginUserName;
            this.userIdToVerify = localStorage.userIdToVerify;
            this.userNameToVerify = localStorage.userNameToVerify;
            this.userEmailToVerify = localStorage.userEmailToVerify;
            this.userPasswordToVerify = localStorage.userPasswordToVerify;
            this.deviceToken = localStorage.deviceToken;
            this.notiSetting = localStorage.notiSetting;
            // this.notiSetting = localStorage.notiSetting ?? appInfo('enableNotification');
            this.isCustomCamera = localStorage.isCustomCamera;
            this.overAllTaxLabel = localStorage.overAllTaxLabel;
            this.overAllTaxValue = localStorage.overAllTaxValue;
            this.shippingTaxLabel = localStorage.shippingTaxLabel;
            this.shopId = localStorage.shopId;
            this.messenger = localStorage.messenger;
            this.whatsApp = localStorage.whatsApp;
            this.phone = localStorage.phone;
            this.shippingTaxValue = localStorage.shippingTaxValue;
            this.appInfoVersionNo = localStorage.appInfoVersionNo;
            this.appInfoForceUpdate = localStorage.appInfoForceUpdate;
            this.appInfoForceUpdateTitle = localStorage.appInfoForceUpdateTitle;
            this.appInfoForceUpdateMsg = localStorage.appInfoForceUpdateMsg;
            this.startDate = localStorage.startDate;
            this.endDate = localStorage.endDate;
            this.paypalEnabled = localStorage.paypalEnabled;
            this.stripeEnabled = localStorage.stripeEnabled;
            this.codEnabled = localStorage.codEnabled;
            this.bankEnabled = localStorage.bankEnabled;
            this.publishKey = localStorage.publishKey;
            this.depositPercent = localStorage.depositPercent;
            this.coolDownPeriod = localStorage.coolDownPeriod;
            this.bidFee = localStorage.bidFee ;
            this.uploadFee = localStorage.uploadFee;
            this.memberShipDuration = localStorage.memberShipDuration;
            this.membershipAmount = localStorage.membershipAmount;
            this.depositPercent = localStorage.depositPercent;
            this.shippingId = localStorage.shippingId;
            this.standardShippingEnable = localStorage.standardShippingEnable;
            this.zoneShippingEnable = localStorage.zoneShippingEnable;
            this.noShippingEnable = localStorage.noShippingEnable;
            this.creditCardNo = localStorage.creditCardNo;
            this.creditCardValid = localStorage.creditCardValid;
            this.creditCardMonth = localStorage.creditCardMonth;
            this.creditCardYear = localStorage.creditCardYear;
            this.creditCardCVC = localStorage.creditCardCVC;
            this.creditCardName = localStorage.creditCardName;
            this.resultStartTime = localStorage.resultStartTime;
            this.userPassword = localStorage.userPassword;
            this.languageCode = localStorage.languageCode;
            // this.showProfile = localStorage.showProfile ?? appInfo('showUserProfile');
            this.showProfile = localStorage.showProfile;
        },
        replacelanguageCode(languageCode: string){
            localStorage.languageCode = languageCode;
            this.languageCode = languageCode;
        },
        replaceshowProfile(showProfile: string){
            localStorage.showProfile = showProfile;
            this.showProfile = showProfile;
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

            this.creditCardNo = creditCardNo;
            this.creditCardMonth = creditCardMonth;
            this.creditCardYear = creditCardYear;
            this.creditCardCVC = creditCardCVC;
            this.creditCardName = creditCardName;
            this.creditCardValid = creditCardValid;

        },
        replaceLoginUserId(loginUserId : string ) {
            localStorage.loginUserId = loginUserId;
            this.loginUserId = loginUserId;
        },

        replaceNotiSetting(notiSetting : string ) {
            localStorage.notiSetting = notiSetting;
            this.notiSetting = notiSetting;
        },

        replacedeviceToken(deviceToken : string ) {
            localStorage.deviceToken = deviceToken;
            this.deviceToken = deviceToken;
        },


        replaceLoginUserName(loginUsername : string) {

            localStorage.loginUserName = loginUsername;
            this.loginUserName = loginUsername;
        },

        replaceLoginExpiryTime(time: number) {
            this.loginExpiryTime = time;
            localStorage.loginExpiryTime = this.loginExpiryTime;
        },

        replaceUserIdToVerify(userIdToVerify : string) {
            localStorage.userIdToVerify = userIdToVerify;
            this.userIdToVerify = userIdToVerify;
        },

        replaceUserNameToVerify(userNameToVerify : string) {
            localStorage.userNameToVerify = userNameToVerify;
            this.userNameToVerify = userNameToVerify;
        },

        replaceUserEmailToVerify(userEmailToVerify : string) {
            localStorage.userEmailToVerify = userEmailToVerify;
            this.userEmailToVerify = userEmailToVerify;
        },

        replaceUserPasswordToVerify(userPasswordToVerify : string) {
            localStorage.userPasswordToVerify = userPasswordToVerify;
            this.userPasswordToVerify = userPasswordToVerify;
        },

        replaceLocation(locationId : string, locationName : string, locationLat : string, locationLng: string) {
            localStorage.locationId = locationId;
            this.locationId = locationId;

            localStorage.locationName = locationName;
            this.locationName = locationName;

            localStorage.locationLat = locationLat;
            this.locationLat = locationLat;

            localStorage.locationLng = locationLng;
            this.locationLng = locationLng;
        },
        replaceSubLocation(subLocationId : string, subLocationName : string ) {
            localStorage.subLocationId = subLocationId;
            this.subLocationId = subLocationId;

            localStorage.subLocationName = subLocationName;
            this.subLocationName = subLocationName;

        },

        // Stripe Publish Key
        replacePublishKey(pubKey : string) {
            localStorage.publishKey = pubKey;
            this.publishKey = pubKey;
        },

        updateLoginExpiryTime() {
            const date = new Date()
            date.setDate(date.getDate() + 1); // set one day
            this.replaceLoginExpiryTime(date.getTime());
        },

        getLoginUserId() : string {
            this.loginUserId = localStorage.loginUserId;

            if(this.loginUserId != null && this.loginUserId != "") {
                return this.loginUserId;
            }else {
                return 'nologinuser';
            }
        },

        getPublishedKey() : string {
            this.publishKey = localStorage.publishKey;
            return this.publishKey;
        }
    },

})
