export default class AppleLoginParameterHolder {

    appleId: string = '';
    userName: string = '';
    userEmail: string = '';
    profilePhotoUrl: string = '';
    deviceToken: string = '';
    platformName: string = '';


    AppleLoginParameterHolder() {
        this.appleId = '';
        this.userName = '';
        this.userEmail = '';
        this.profilePhotoUrl = '';
        this.deviceToken = '';
        this.platformName = '';

    }

    toMap(): {} {
        const map = {};
        map['apple_id'] = this.appleId;
        map['user_name'] = this.userName;
        map['user_email'] = this.userEmail;
        map['profile_photo_url'] = this.profilePhotoUrl;
        map['device_token'] = this.deviceToken;
        map['platform_name'] = this.platformName;


        return map;
    }
}