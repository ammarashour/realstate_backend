export default class UserRegistesrParameterHolder {

    name: string = '';
    userName: string = '';
    userEmail: string = '';
    userPassword: string = '';
    deviceToken: string = '';
    platformName: string = '';
    deviceInfo: string = '';
    deviceId: string = '';


    UserRegistesrParameterHolder() {
        this.name = '';
        this.userName = '';
        this.userEmail = '';
        this.userPassword = '';
        this.deviceToken = '';
        this.platformName = '';
        this.deviceInfo = '';
        this.deviceId = '';

    }

    toMap(): {} {
        const map = {};
        map['name'] = this.name;
        map['username'] = this.userName;
        map['email'] = this.userEmail;
        map['password'] = this.userPassword;
        map['device_token'] = this.deviceToken;
        map['platform_name'] = this.platformName;
        map['device_info'] = this.deviceInfo;
        map['device_id'] = this.deviceId;


        return map;
    }
}