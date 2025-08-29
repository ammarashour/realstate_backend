export default class NotiPostParameterHolder {

    notiId: string = '';
    userId: string = '';
    deviceToken: string = '';


    NotiPostParameterHolder() {
        this.notiId = '';
        this.userId = '';
        this.deviceToken = '';

    }

    toMap(): {} {
        const map = {};
        map['noti_id'] = this.notiId;
        map['user_id'] = this.userId;
        map['device_token'] = this.deviceToken;

        return map;
    }
}