export default class NotiUnRegisterHolder {

    platformName: string = '';
    deviceId: string = '';
    userId: string = '';


    NotiUnRegisterHolder() {
        this.platformName = '';
        this.deviceId = '';
        this.userId = '';

    }

    toMap(): {} {
        const map = {};
        map['platform_name'] = this.platformName;
        map['device_token'] = this.deviceId;
        map['user_id'] = this.userId;

        return map;
    }
}