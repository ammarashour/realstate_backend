import { PsObject } from "./core/PsObject";
import Language from "./Language";
import DefaultPhoto from "./DefaultPhoto";
export default class FrontendConfigSetting extends PsObject<FrontendConfigSetting>{

    mapKey : string = '';
    isDemoForPayment : string = '';
    isEnableVideoSetting : string = '';
    showUserProfile : string = '';
    noFilterWithLocationOnMap : string = '';
    priceFormat : string = '';
    enableNotification : string = '';
    fcmServerKey : string = '';
    firebaseWebPushKeyPair : string = '';
    firebaseConfig : string = '';
    adClient : string = '';
    adSlot : string = '';
    copyRight : string = '';
    googlePlayUrl : string = '';
    appStoreUrl : string = '';
    googleSetting : string = '';
    appStoreSetting : string = '';
    facebook_url : string = '';
    linkedin_url : string = '';
    twitter_url : string = '';
    instagram_url : string = '';
    pinterest_url : string = '';
    youtube_url : string = '';
    facebook_setting : string = '';
    linkedin_setting : string = '';
    twitter_setting : string = '';
    instagram_setting : string = '';
    pinterest_setting : string = '';
    youtube_setting : string = '';
    bannerSrc : string = '';
    googleMap : string = '';
    openStreetMap : string = '';
    mile : string = '';
    promoteFirstChoiceDay : string = '';
    promoteSecondChoiceDay : string = '';
    promoteThirdChoiceDay : string = '';
    promoteFourthChoiceDay : string = '';
    itemUploadUi1 : Boolean = false;
    itemUploadUi2 : Boolean = false;
    defaultLanguage: Language = new Language();
    excludeLanguage: Language[] = [new Language()];
    frontendLogo: DefaultPhoto = new DefaultPhoto();
    frontendIcon: DefaultPhoto = new DefaultPhoto();
    frontendBanner: DefaultPhoto = new DefaultPhoto();
    appBrandingImage: DefaultPhoto = new DefaultPhoto();

    init(
        mapKey : string,
        isDemoForPayment : string,
        isEnableVideoSetting : string,
        showUserProfile : string,
        noFilterWithLocationOnMap : string,
        priceFormat : string,
        enableNotification : string,
        fcmServerKey : string,
        firebaseWebPushKeyPair : string,
        firebaseConfig: string,
        adClient : string,
        adSlot : string,
        copyRight : string,
        googlePlayUrl : string,
        appStoreUrl : string,
        googleSetting : string,
        appStoreSetting : string,
        facebook_url : string,
        linkedin_url : string,
        twitter_url : string,
        instagram_url : string,
        pinterest_url : string,
        youtube_url : string,
        facebook_setting : string,
        linkedin_setting : string,
        twitter_setting : string,
        instagram_setting : string,
        pinterest_setting : string,
        youtube_setting : string,
        bannerSrc : string,
        googleMap : string,
        openStreetMap : string,
        mile : string,
        promoteFirstChoiceDay : string,
        promoteSecondChoiceDay : string,
        promoteThirdChoiceDay : string,
        promoteFourthChoiceDay : string,
        itemUploadUi1 : Boolean,
        itemUploadUi2 : Boolean,
        defaultLanguage: Language,
        excludeLanguage: Language[],
        frontendLogo: DefaultPhoto,
        frontendIcon: DefaultPhoto,
        frontendBanner: DefaultPhoto,
        appBrandingImage: DefaultPhoto,

    ) {
        this.mapKey = mapKey;
        this.isDemoForPayment = isDemoForPayment;
        this.isEnableVideoSetting = isEnableVideoSetting;
        this.showUserProfile = showUserProfile;
        this.noFilterWithLocationOnMap = noFilterWithLocationOnMap;
        this.priceFormat = priceFormat;
        this.enableNotification = enableNotification;
        this.fcmServerKey = fcmServerKey;
        this.firebaseWebPushKeyPair = firebaseWebPushKeyPair;
        this.firebaseConfig = firebaseConfig;
        this.adClient = adClient;
        this.adSlot = adSlot;
        this.copyRight = copyRight;
        this.googlePlayUrl = googlePlayUrl;
        this.appStoreUrl = appStoreUrl;
        this.googleSetting = googleSetting;
        this.appStoreSetting = appStoreSetting;
        this.facebook_url = facebook_url;
        this.linkedin_url = linkedin_url;
        this.twitter_url = twitter_url;
        this.instagram_url = instagram_url;
        this.pinterest_url = pinterest_url;
        this.youtube_url = youtube_url;
        this.facebook_setting = facebook_setting;
        this.linkedin_setting = linkedin_setting;
        this.twitter_setting = twitter_setting;
        this.instagram_setting = instagram_setting;
        this.pinterest_setting = pinterest_setting;
        this.youtube_setting = youtube_setting;
        this.bannerSrc = bannerSrc;
        this.googleMap = googleMap;
        this.openStreetMap = openStreetMap;
        this.mile = mile;
        this.promoteFirstChoiceDay = promoteFirstChoiceDay;
        this.promoteSecondChoiceDay = promoteSecondChoiceDay;
        this.promoteThirdChoiceDay = promoteThirdChoiceDay;
        this.promoteFourthChoiceDay = promoteFourthChoiceDay;
        this.itemUploadUi1 = itemUploadUi1;
        this.itemUploadUi2 = itemUploadUi2;
        this.defaultLanguage = defaultLanguage;
        this.excludeLanguage = excludeLanguage;
        this.frontendLogo = frontendLogo;
        this.frontendIcon = frontendIcon;
        this.frontendBanner = frontendBanner;
        this.appBrandingImage = appBrandingImage;


        return this;

    }

    getPrimaryKey(): string {
        return this.mapKey;
    }

    toMap(object: FrontendConfigSetting): any {
        const map = {};
        map['map_key'] = object.mapKey;
        map['is_demo_for_payment'] = object.isDemoForPayment;
        map['is_enable_video_setting'] = object.isEnableVideoSetting;
        map['show_user_profile'] = object.showUserProfile;
        map['no_filter_with_location_on_map'] = object.noFilterWithLocationOnMap;
        map['price_format'] = object.priceFormat;
        map['enable_notification'] = object.enableNotification;
        map['fcm_server_key'] = object.fcmServerKey;
        map['firebase_web_push_key_pair'] = object.firebaseWebPushKeyPair;
        map['firebase_config'] = object.firebaseConfig;
        map['ad_client'] = object.adClient;
        map['ad_slot'] = object.adSlot;
        map['copyright'] = object.copyRight;
        map['google_playstore_url'] = object.googlePlayUrl;
        map['app_store_url'] = object.appStoreUrl;
        map['google_setting'] = object.googleSetting;
        map['app_store_setting'] = object.appStoreSetting;
        map['facebook_url'] = object.facebook_url;
        map['linkedin_url'] = object.linkedin_url;
        map['twitter_url'] = object.twitter_url;
        map['instagram_url'] = object.instagram_url;
        map['pinterest_url'] = object.pinterest_url;
        map['youtube_url'] = object.youtube_url;
        map['facebook_setting'] = object.facebook_setting;
        map['linkedin_setting'] = object.linkedin_setting;
        map['twitter_setting'] = object.twitter_setting;
        map['instagram_setting'] = object.instagram_setting;
        map['pinterest_setting'] = object.pinterest_setting;
        map['youtube_setting'] = object.youtube_setting;
        map['banner_src'] = object.bannerSrc;
        map['google_map'] = object.googleMap;
        map['open_street_map'] = object.openStreetMap;
        map['mile'] = object.mile;
        map['promote_first_choice_day'] = object.promoteFirstChoiceDay;
        map['promote_second_choice_day'] = object.promoteSecondChoiceDay;
        map['promote_third_choice_day'] = object.promoteThirdChoiceDay;
        map['promote_fourth_choice_day'] = object.promoteFourthChoiceDay;
        map['item_upload_ui1'] = object.itemUploadUi1;
        map['item_upload_ui2'] = object.itemUploadUi2;
        map['default_language'] = new Language().toMap(object.defaultLanguage);
        map['exclude_language'] = new Language().toMapList(object.excludeLanguage);
        map['frontend_logo'] = new DefaultPhoto().toMap(object.frontendLogo);
        map['frontend_icon'] = new DefaultPhoto().toMap(object.frontendIcon);
        map['frontend_banner'] = new DefaultPhoto().toMap(object.frontendBanner);
        map['app_branding_image'] = new DefaultPhoto().toMap(object.appBrandingImage);


        return map;
    }

    toMapList(objectList: FrontendConfigSetting[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new FrontendConfigSetting().init(


            obj.map_key,
            obj.is_demo_for_payment,
            obj.is_enable_video_setting,
            obj.show_user_profile,
            obj.no_filter_with_location_on_map,
            obj.price_format,
            obj.enable_notification,
            obj.fcm_server_key,
            obj.firebase_web_push_key_pair,
            obj.firebase_config,
            obj.ad_client,
            obj.ad_slot,
            obj.copyright,
            obj.google_playstore_url,
            obj.app_store_url,
            obj.google_setting,
            obj.app_store_setting,
            obj.facebook_url,
            obj.linkedin_url,
            obj.twitter_url,
            obj.instagram_url,
            obj.pinterest_url,
            obj.youtube_url,
            obj.facebook_setting,
            obj.linkedin_setting,
            obj.twitter_setting,
            obj.instagram_setting,
            obj.pinterest_setting,
            obj.youtube_setting,
            obj.banner_src,
            obj.google_map,
            obj.open_street_map,
            obj.mile,
            obj.promote_first_choice_day,
            obj.promote_second_choice_day,
            obj.promote_third_choice_day,
            obj.promote_fourth_choice_day,
            obj.item_upload_ui1,
            obj.item_upload_ui2,
            new Language().fromMap(obj.default_language),
            new Language().fromMapList(obj.exclude_language),
            new DefaultPhoto().fromMap(obj.frontend_logo),
            new DefaultPhoto().fromMap(obj.frontend_icon),
            new DefaultPhoto().fromMap(obj.frontend_banner),
            new DefaultPhoto().fromMap(obj.app_branding_image),

       );
    }
    fromMapList(objList : any[] ) : FrontendConfigSetting[] {
        const frontendConfigSetting : FrontendConfigSetting[] = [];
        for(const obj in objList) {
            if(obj != null) {
                frontendConfigSetting.push(this.fromMap(obj));
            }
        }

        return frontendConfigSetting;
    }


}
