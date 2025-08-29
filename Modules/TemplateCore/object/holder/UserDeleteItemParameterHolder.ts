

export default class UserDeleteItemParameterHolder {

    itemId: string = '';
    userId: string = '';


    UserDeleteItemParameterHolder() {
        this.itemId = '';
        this.userId = '';

    }


    toMap(): {} {
        const map = {};
        map['id'] = this.itemId;
        map['user_id'] = this.userId;
        return map;
    }
}
