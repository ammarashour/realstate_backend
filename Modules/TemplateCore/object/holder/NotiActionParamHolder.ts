export default class NotiActionParameterHolder {

    userId: string = '';
    deviceToken: string = '';
    notiId: string = '';
    notiType: string = '';


    NotiActionParameterHolder() {
        this.userId = '';
        this.deviceToken = '';
        this.notiId = '';
        this.notiType = '';

    }

    toMap(): {} {
        const map = {};
        map['user_id'] = this.userId;
        map['device_token'] = this.deviceToken;
        map['noti_id'] = this.notiId;
        map['noti_type'] = this.notiType;

        return map;
    }
}