import { PsObject } from "./core/PsObject";

export default class PsAppSetting extends PsObject<PsAppSetting>{

    latitude: string = '';
    longitude: string = '';
    isSubLocation: string = '';
    isThumbnailGenerate: string = '';
    isPaidApp: string = '';
    isPromoteEnable: string = '';
    isBlockUser: string = '';
    isSubcatSubscription: string = '';
    maxImgUploadOfItem: string = '';
    oneDay: string = '';
    SelectedPriceType: string = '';
    SelectedChatType: string = '';
    SoldOutFeatureSetting: string = '';
    hidePricesetting: string='';

    init(
        latitude: string,
        longitude: string,
        isSubLocation: string,
        isThumbnailGenerate: string,
        isPaidApp: string,
        isPromoteEnable: string,
        isBlockUser: string,
        isSubcatSubscription: string,
        maxImgUploadOfItem: string,
        oneDay: string,
        SelectedPriceType: string,
        SelectedChatType: string,
        SoldOutFeatureSetting: string,
        hidePricesetting:string

    ) {
        this.latitude = latitude;
        this.longitude = longitude;
        this.isSubLocation = isSubLocation;
        this.isThumbnailGenerate = isThumbnailGenerate;
        this.isPaidApp = isPaidApp;
        this.isPromoteEnable = isPromoteEnable;
        this.isBlockUser = isBlockUser;
        this.isSubcatSubscription = isSubcatSubscription;
        this.maxImgUploadOfItem = maxImgUploadOfItem;
        this.oneDay = oneDay
        this.SelectedPriceType = SelectedPriceType;
        this.SelectedChatType = SelectedChatType ;
        this.SoldOutFeatureSetting = SoldOutFeatureSetting ;
        this.hidePricesetting = hidePricesetting;

        return this;

    }

    getPrimaryKey(): string {
        return this.latitude;
    }

    toMap(object: PsAppSetting): any {
        const map = {};
        map['lat'] = object.latitude;
        map['lng'] = object.longitude;
        map['is_sub_location'] = object.isSubLocation;
        map['is_thumb2x_3x_generate'] = object.isThumbnailGenerate;
        map['is_paid_app'] = object.isPaidApp;
        map['is_promote_enable'] = object.isPromoteEnable;
        map['is_block_user'] = object.isBlockUser;
        map['is_sub_subscription'] = object.isSubcatSubscription;
        map['max_img_upload_of_item'] = object.maxImgUploadOfItem;
        map['one_day_per_price'] = object.oneDay;
        map['selected_price_type'] = object.SelectedPriceType;
        map['selected_chat_type'] = object.SelectedChatType;
        map['soldout_feature_setting'] = object.SoldOutFeatureSetting;
        map['hide_price_setting'] = object.hidePricesetting;


        return map;
    }

    toMapList(objectList: PsAppSetting[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new PsAppSetting().init(


            obj.lat,
            obj.lng,
            obj.is_sub_location,
            obj.is_thumb2x_3x_generate,
            obj.is_paid_app,
            obj.is_promote_enable,
            obj.is_block_user,
            obj.is_sub_subscription,
            obj.max_img_upload_of_item,
            obj.one_day_per_price,
            obj.selected_price_type,
            obj.selected_chat_type,
            obj.soldout_feature_setting,
            obj.hide_price_setting


        );
    }
    fromMapList(objList: any[]): PsAppSetting[] {
        const psAppSettingList: PsAppSetting[] = [];
        for (const obj in objList) {
            if (obj != null) {
                psAppSettingList.push(this.fromMap(obj));
            }
        }

        return psAppSettingList;
    }


}
