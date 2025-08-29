export default class NotiParameterHolder {

    userId: string = '';
    deviceToken: string = '';


    NotiParameterHolder() {
        this.userId = '';
        this.deviceToken = '';

    }

    toMap(): {} {
        const map = {};
        map['user_id'] = this.userId;
        map['device_token'] = this.deviceToken;

        return map;
    }
}