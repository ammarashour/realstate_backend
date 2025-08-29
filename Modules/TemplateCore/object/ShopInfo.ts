import DefaultIcon from "./DefaultIcon";
import { PsObject } from "@templateCore/object/core/PsObject";
import DefaultPhoto from "./DefaultPhoto";

export default class ShopInfo extends PsObject<ShopInfo> {

    id : string = '';
    shippingId : string = '';
    name : string = '';
    description : string = '';
    email : string = '';
    coordinate : string = '';
    privacyPolicy : string = '';
    lat : string = '';
    lng : string = '';
    paypalEmail : string = '';
    paypalEnvironment : string = '';
    paypalAppidLive : string = '';
    paypalMerchantname : string = '';
    paypalCustomerid : string = '';
    paypalIpnurl : string = '';
    paypalMemo : string = '';
    paypalMerchantId : string = '';
    paypalPublicKey : string = '';
    paypalPrivateKey : string = '';
    paypalCustomerId : string = '';
    bankAccount : string = '';
    bankName : string = '';
    bankCode : string = '';
    branchCode : string = '';
    swiftCode : string = '';
    codEmail : string = '';
    stripePublishableKey : string = '';
    stripeSecretKey : string = '';
    currencySymbol : string = '';
    currencyShortForm : string = '';
    senderEmail : string = '';
    added : string = '';
    status : string = '';
    paypalEnabled : string = '';
    stripeEnabled : string = '';
    codEnabled : string = '';
    banktransferEnabled : string = '';
    isFeature : string = '';
    overallTaxLabel : string = '';
    overallTaxValue : string = '';
    shippingTaxLabel : string = '';
    shippingTaxValue : string = '';
    whapsappNo : string = '';
    refundPolicy : string = '';
    terms : string = '';
    aboutWebsite : string = '';
    facebook : string = '';
    googlePlus : string = '';
    instagram : string = '';
    youtube : string = '';
    pinterest : string = '';
    twitter : string = '';
    aboutPhone1 : string = '';
    aboutPhone2 : string = '';
    aboutPhone3 : string = '';  
    address1 : string = '';
    address2 : string = '';
    address3 : string = '';
    touchCount : string = '';
    addedUserId : string = '';
    updatedDate : string = '';
    updatedUserId : string = '';
    featuredDate : string = '';
    messenger : string = '';
    standardShippingEnable : string = '';
    zoneShippingEnable : string = '';
    noShippingEnable : string = '';
    defaultIcon : DefaultIcon = new DefaultIcon();
    defaultPhoto : DefaultPhoto = new DefaultPhoto();



    init(
        id : string ,
        shippingId : string ,
        name : string ,
        description : string ,
        email : string ,
        coordinate : string ,
        privacyPolicy : string ,
        lat : string ,
        lng : string ,
        paypalEmail : string ,
        paypalEnvironment : string ,
        paypalAppidLive : string ,
        paypalMerchantname : string ,
        paypalCustomerid : string ,
        paypalIpnurl : string ,
        paypalMemo : string ,
        paypalMerchantId : string ,
        paypalPublicKey : string ,
        paypalPrivateKey : string ,
        paypalCustomerId : string ,
        bankAccount : string ,
        bankName : string ,
        bankCode : string ,
        branchCode : string ,
        swiftCode : string ,
        codEmail : string ,
        stripePublishableKey : string ,
        stripeSecretKey : string ,
        currencySymbol : string ,
        currencyShortForm : string ,
        senderEmail : string ,
        added : string ,
        status : string ,
        paypalEnabled : string ,
        stripeEnabled : string ,
        codEnabled : string ,
        banktransferEnabled : string ,
        isFeature : string ,
        overallTaxLabel : string ,
        overallTaxValue : string ,
        shippingTaxLabel : string ,
        shippingTaxValue : string ,
        whapsappNo : string ,
        refundPolicy : string ,
        terms : string ,
        aboutWebsite : string ,
        facebook : string ,
        googlePlus : string ,
        instagram : string ,
        youtube : string ,
        pinterest : string ,
        twitter : string ,
        aboutPhone1 : string ,
        aboutPhone2 : string ,
        aboutPhone3 : string ,
        address1 : string ,
        address2 : string ,
        address3 : string ,
        touchCount : string ,
        addedUserId : string ,
        updatedDate : string ,
        updatedUserId : string ,
        featuredDate : string ,
        messenger : string ,
        standardShippingEnable : string ,
        zoneShippingEnable : string ,
        noShippingEnable : string ,
        defaultIcon : DefaultIcon,
        defaultPhoto : DefaultPhoto,        
        ) {
            this.id =id;
            this.shippingId =shippingId;
            this.name =name;
            this.description =description;
            this.email =email;
            this.coordinate =coordinate;
            this.privacyPolicy =privacyPolicy;
            this.lat =lat;
            this.lng =lng;
            this.paypalEmail =paypalEmail;
            this.paypalEnvironment =paypalEnvironment;
            this.paypalAppidLive =paypalAppidLive;
            this.paypalMerchantname =paypalMerchantname;
            this.paypalCustomerid =paypalCustomerid;
            this.paypalIpnurl =paypalIpnurl;
            this.paypalMemo =paypalMemo;
            this.paypalMerchantId =paypalMerchantId;
            this.paypalPublicKey =paypalPublicKey;
            this.paypalPrivateKey =paypalPrivateKey;
            this.paypalCustomerId =paypalCustomerId;
            this.bankAccount =bankAccount;
            this.bankName =bankName;
            this.bankCode =bankCode;
            this.branchCode =branchCode;
            this.swiftCode =swiftCode;
            this.codEmail =codEmail;
            this.stripePublishableKey =stripePublishableKey;
            this.stripeSecretKey =stripeSecretKey;
            this.currencySymbol =currencySymbol;
            this.currencyShortForm =currencyShortForm;
            this.senderEmail =senderEmail;
            this.added =added;
            this.status =status;
            this.paypalEnabled =paypalEnabled;
            this.stripeEnabled =stripeEnabled;
            this.codEnabled =codEnabled;
            this.banktransferEnabled =banktransferEnabled;
            this.isFeature =isFeature;
            this.overallTaxLabel =overallTaxLabel;
            this.overallTaxValue =overallTaxValue;
            this.shippingTaxLabel =shippingTaxLabel;
            this.shippingTaxValue =shippingTaxValue;
            this.whapsappNo =whapsappNo;
            this.refundPolicy =refundPolicy;
            this.terms =terms;
            this.aboutWebsite =aboutWebsite;
            this.facebook =facebook;
            this.googlePlus =googlePlus;
            this.instagram =instagram;
            this.youtube =youtube;
            this.pinterest =pinterest;
            this.twitter =twitter;
            this.aboutPhone1 =aboutPhone1;
            this.aboutPhone2 =aboutPhone2;
            this.aboutPhone3 =aboutPhone3;
            this.address1 =address1;
            this.address2 =address2;
            this.address3 =address3;
            this.touchCount =touchCount;
            this.addedUserId =addedUserId;
            this.updatedDate =updatedDate;
            this.updatedUserId =updatedUserId;
            this.featuredDate =featuredDate;
            this.messenger =messenger;
            this.standardShippingEnable =standardShippingEnable;
            this.zoneShippingEnable =zoneShippingEnable;
            this.noShippingEnable =noShippingEnable;
        this.defaultIcon = defaultIcon;
        this.defaultPhoto = defaultPhoto;
       

        return this;

    }
    
    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: ShopInfo) : any {
        const map = {};

        map['id'] = object.id;
        map['shipping_id'] = object.shippingId;
        map['name'] = object.name;
        map['description'] = object.description;
        map['email'] = object.email;
        map['coordinate'] = object.coordinate;
        map['privacy_policy'] = object.privacyPolicy;
        map['lat'] = object.lat;
        map['lng'] = object.lng;
        map['paypal_email'] = object.paypalEmail;
        map['paypal_environment'] = object.paypalEnvironment;
        map['paypal_appid_live'] = object.paypalAppidLive;
        map['paypal_merchantname'] = object.paypalMerchantname;
        map['paypal_customerid'] = object.paypalCustomerid;
        map['paypal_ipnurl'] = object.paypalIpnurl;
        map['paypal_memo'] = object.paypalMemo;
        map['paypal_merchant_id'] = object.paypalMerchantId;
        map['paypal_public_key'] = object.paypalPublicKey;
        map['paypal_private_key'] = object.paypalPrivateKey;
        map['paypal_customerid'] = object.paypalCustomerId;
        map['bank_account'] = object.bankAccount;
        map['bank_name'] = object.bankName;
        map['bank_code'] = object.bankCode;
        map['branch_code'] = object.branchCode;
        map['swift_code'] = object.swiftCode;
        map['cod_email'] = object.codEmail;
        map['stripe_publishable_key'] = object.stripePublishableKey;
        map['stripe_secret_key'] = object.stripeSecretKey;
        map['currency_symbol'] = object.currencySymbol;
        map['currency_short_form'] = object.currencyShortForm;
        map['sender_email'] = object.senderEmail;
        map['added'] = object.added;
        map['status'] = object.status;
        map['paypal_enabled'] = object.paypalEnabled;
        map['stripe_enabled'] = object.stripeEnabled;
        map['cod_enabled'] = object.codEnabled;
        map['banktransfer_enabled'] = object.banktransferEnabled;
        map['is_feature'] = object.isFeature;
        map['overall_tax_label'] = object.overallTaxLabel;
        map['overall_tax_value'] = object.overallTaxValue;
        map['shipping_tax_label'] = object.shippingTaxLabel;
        map['shipping_tax_value'] = object.shippingTaxValue;
        map['whapsapp_no'] = object.whapsappNo;
        map['refund_policy'] = object.refundPolicy;
        map['terms'] = object.terms;
        map['about_website'] = object.aboutWebsite;
        map['facebook'] = object.facebook;
        map['google_plus'] = object.googlePlus;
        map['instagram'] = object.instagram;
        map['youtube'] = object.youtube;
        map['pinterest'] = object.pinterest;
        map['twitter'] = object.twitter;
        map['about_phone1'] = object.aboutPhone1;
        map['about_phone2'] = object.aboutPhone2;
        map['about_phone3'] = object.aboutPhone3;
        map['address1'] = object.address1;
        map['address2'] = object.address2;
        map['address3'] = object.address3;
        map['touch_count'] = object.touchCount;
        map['added_user_id'] = object.addedUserId;
        map['updated_date'] = object.updatedDate;
        map['updated_user_id'] = object.updatedUserId;
        map['featured_date'] = object.featuredDate;
        map['messenger'] = object.messenger;
        map['standard_shipping_enable'] = object.standardShippingEnable;
        map['zone_shipping_enable'] = object.zoneShippingEnable;
        map['no_shipping_enable'] = object.noShippingEnable;
        map['default_icon'] = new DefaultIcon().toMap(object.defaultIcon);
        map['default_photo'] = new DefaultPhoto().toMap(object.defaultPhoto);

        return map;
    }

    toMapList(objectList: ShopInfo[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj : any) {
        return new ShopInfo().init(

            obj.id,
            obj.shipping_id,
            obj.name,
            obj.description,
            obj.email,
            obj.coordinate,
            obj.privacy_policy,
            obj.lat,
            obj.lng,
            obj.paypal_email,
            obj.paypal_environment,
            obj.paypal_appid_live,
            obj.paypal_merchantname,
            obj.paypal_customerid,
            obj.paypal_ipnurl,
            obj.paypal_memo,
            obj.paypal_merchant_id,
            obj.paypal_public_key,
            obj.paypal_private_key,
            obj.paypal_customerid,
            obj.bank_account,
            obj.bank_name,
            obj.bank_code,
            obj.branch_code,
            obj.swift_code,
            obj.cod_email,
            obj.stripe_publishable_key,
            obj.stripe_secret_key,
            obj.currency_symbol,
            obj.currency_short_form,
            obj.sender_email,
            obj.added,
            obj.status,
            obj.paypal_enabled,
            obj.stripe_enabled,
            obj.cod_enabled,
            obj.banktransfer_enabled,
            obj.is_feature,
            obj.overall_tax_label,
            obj.overall_tax_value,
            obj.shipping_tax_label,
            obj.shipping_tax_value,
            obj.whapsapp_no,
            obj.refund_policy,
            obj.terms,
            obj.about_website,
            obj.facebook,
            obj.google_plus,
            obj.instagram,
            obj.youtube,
            obj.pinterest,
            obj.twitter,
            obj.about_phone1,
            obj.about_phone2,
            obj.about_phone3,
            obj.address1,
            obj.address2,
            obj.address3,
            obj.touch_count,
            obj.added_user_id,
            obj.updated_date,
            obj.updated_user_id,
            obj.featured_date,
            obj.messenger,
            obj.standard_shipping_enable,
            obj.zone_shipping_enable,
            obj.no_shipping_enable,
        new DefaultIcon().fromMap(obj.default_icon),
        new DefaultPhoto().fromMap(obj.default_photo),
        );        
    }

    fromMapList(objList : any[] ) : ShopInfo[] {
        const shopInfoList : ShopInfo[] = [];
        for(const obj in objList) {
            if(obj != null) {
                shopInfoList.push(this.fromMap(obj));
            }
        }

        return shopInfoList;
    }
}
