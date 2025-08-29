export default class UserLoginParameterHolder {

    userEmail: string = '';
    userPassword: string = '';
    deviceToken: string = '';
    platformName: string = '';

    UserLoginParameterHolder() {
        this.userEmail = '';
        this.userPassword = '';
        this.deviceToken = '';
        this.platformName = '';
    }

    toMap() : {} {
        const map = {};
        map['user_email'] = this.userEmail;
        map['user_password'] = this.userPassword;
        map['device_token'] = this.deviceToken;
        map['platform_name'] = this.platformName;

        return map;
    }
}