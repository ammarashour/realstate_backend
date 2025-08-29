export default class FbLoginParameterHolder {

    facebookId: string = '';
    userName: string = '';
    userEmail: string = '';
    profilePhotoUrl: string = '';
    profileImgId: string = '';
    deviceToken: string = '';
    platformName: string = '';


    FbLoginParameterHolder() {
        this.facebookId = '';
        this.userName = '';
        this.userEmail = '';
        this.profilePhotoUrl = '';
        this.profileImgId = '';
        this.deviceToken = '';
        this.platformName = '';

    }

    toMap(): {} {
        const map = {};
        map['facebook_id'] = this.facebookId;
        map['user_name'] = this.userName;
        map['user_email'] = this.userEmail;
        map['profile_photo_url'] = this.profilePhotoUrl;
        map['profile_img_id'] = this.profileImgId;
        map['device_token'] = this.deviceToken;
        map['platform_name'] = this.platformName;


        return map;
    }
}