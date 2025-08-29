import { PsObject } from "./core/PsObject";
import DefaultPhoto from "./DefaultPhoto";

export default class AboutUs extends PsObject<AboutUs>{

    aboutId : string = '';
    aboutTitle : string = '';
    aboutDescription : string = '';
    aboutEmail : string = '';
    aboutPhone : string = '';
    aboutWebsite : string = '';
    // adsOn : string = '';
    // adsClient : string = '';
    // adsSlot : string = '';
    // analytOn : string = '';
    // analytTrackId : string = '';
    facebook : string = '';
    googlePlus : string = '';
    instagram : string = '';
    youtube : string = '';
    pinterest : string = '';
    twitter : string = '';
    privacypolicy : string = '';
    gdpr : string = '';
    uploadPoint : string = '';
    safetyTips : string = '';
    faqPage : string = '';
    termsAndConditions : string = '';
    defaultPhoto: DefaultPhoto = new DefaultPhoto();

    init(
        aboutId : string,
        aboutTitle : string,
        aboutDescription : string,
        aboutEmail : string,
        aboutPhone : string,
        aboutWebsite : string,
        // adsOn : string,
        // adsClient : string,
        // adsSlot : string,
        // analytOn : string,
        // analytTrackId : string,
        facebook : string,
        googlePlus : string,
        instagram : string,
        youtube : string,
        pinterest : string,
        twitter : string,
        privacypolicy : string,
        gdpr : string,
        uploadPoint : string,
        safetyTips : string,
        faqPage : string,
        termsAndConditions : string,
        defaultPhoto : DefaultPhoto,
        

    ) {
        this.aboutId = aboutId;
        this.aboutTitle = aboutTitle;
        this.aboutDescription = aboutDescription;
        this.aboutEmail = aboutEmail;
        this.aboutPhone = aboutPhone;
        this.aboutWebsite = aboutWebsite;
        // this.adsOn = adsOn;
        // this.adsClient = adsClient;
        // this.adsSlot = adsSlot;
        // this.analytOn = analytOn;
        // this.analytTrackId = analytTrackId;
        this.facebook = facebook;
        this.googlePlus = googlePlus;
        this.instagram = instagram;
        this.youtube = youtube;
        this.pinterest = pinterest;
        this.twitter = twitter;
        this.privacypolicy = privacypolicy;
        this.gdpr = gdpr;
        this.uploadPoint = uploadPoint;
        this.safetyTips = safetyTips;
        this.faqPage = faqPage;
        this.termsAndConditions = termsAndConditions;
        this.defaultPhoto = defaultPhoto;
        

        return this;

    }

    getPrimaryKey(): string {
        return this.aboutId;
    }


    fromMap(obj: any) {
        return new AboutUs().init(
            obj.id,
            obj.about_title,
            obj.about_description,
            obj.about_email,
            obj.about_phone,
            obj.about_website,
            // obj.ads_on,
            // obj.ads_client,
            // obj.ads_slot,
            // obj.analyt_on,
            // obj.analyt_track_id,
            obj.facebook,
            obj.google_plus,
            obj.instagram,
            obj.youtube,
            obj.pinterest,
            obj.twitter,
            obj.privacy_policy,
            obj.GDPR,
            obj.upload_point,
            obj.safety_tips,
            obj.faq_pages,
            obj.terms_and_conditions,
            new DefaultPhoto().fromMap(obj.default_photo),
        );
    }


    fromMapList(objList : any[] ) : AboutUs[] {
        const aboutUs : AboutUs[] = [];
        for(const obj in objList) {
            if(obj != null) {
                aboutUs.push(this.fromMap(obj));
            }
        }

        return aboutUs;
    }


    toMap(object: AboutUs): any {
        const map = {};
        map['id'] = object.aboutId;
        map['about_title'] = object.aboutTitle;
        map['about_description'] = object.aboutDescription;
        map['about_email'] = object.aboutEmail;
        map['about_phone'] = object.aboutPhone;
        map['about_website'] = object.aboutWebsite;
        // map['ads_on'] = object.adsOn;
        // map['ads_client'] = object.adsClient;
        // map['ads_slot'] = object.adsSlot;
        // map['analyt_on'] = object.analytOn;
        // map['analyt_track_id'] = object.analytTrackId;
        map['facebook'] = object.facebook;
        map['google_plus'] = object.googlePlus;
        map['instagram'] = object.instagram;
        map['youtube'] = object.youtube;
        map['pinterest'] = object.pinterest;
        map['twitter'] = object.twitter;
        map['privacy_policy'] = object.privacypolicy;
        map['GDPR'] = object.gdpr;
        map['upload_point'] = object.uploadPoint;
        map['safety_tips'] = object.safetyTips;
        map['faq_pages'] = object.faqPage;
        map['terms_and_conditions'] = object.termsAndConditions;
        map['default_photo'] = new DefaultPhoto().toMap(object.defaultPhoto);

        return map;
    }

    toMapList(objectList: AboutUs[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    
}