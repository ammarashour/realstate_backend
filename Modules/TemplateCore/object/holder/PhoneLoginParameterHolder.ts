export default class PhoneLoginParameterHolder {

    phoneId: string = '';
    userName: string = '';
    userPhone: string = '';
    displayName: string = '';
    deviceToken: string = '';
    platformName: string = '';
    loginMethod: string = 'phone';

    PhoneLoginParameterHolder() {
        this.phoneId = '';
        this.userName = '';
        this.userPhone = '';
        this.deviceToken = '';
        this.platformName = '';
        this.displayName = '';
        this.loginMethod = 'phone';

    }

    toMap(): {} {
        const map = {};
        map['phone_id'] = this.phoneId;
        map['user_name'] = this.userName;
        map['user_phone'] = this.userPhone;
        map['display_name'] = this.displayName;
        map['device_token'] = this.deviceToken;
        map['platform_name'] = this.platformName;
        map['loginMethod'] = this.loginMethod;


        return map;
    }
}
