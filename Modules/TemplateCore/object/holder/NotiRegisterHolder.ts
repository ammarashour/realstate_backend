export default class NotiRegisterHolder {

    platformName: string = '';
    deviceId: string = '';
    loginUserId: string = '';


    NotiRegisterHolder() {
        this.platformName = '';
        this.deviceId = '';
        this.loginUserId = '';

    }

    toMap(): {} {
        const map = {};
        map['platform_name'] = this.platformName;
        map['device_token'] = this.deviceId;
        map['user_id'] = this.loginUserId;

        return map;
    }
}