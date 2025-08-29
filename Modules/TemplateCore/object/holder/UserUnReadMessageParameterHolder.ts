export default class UserUnReadMessageParameterHolder {

    userId: string = '';
    deviceToken: string = '';


    UserUnReadMessageParameterHolder() {
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