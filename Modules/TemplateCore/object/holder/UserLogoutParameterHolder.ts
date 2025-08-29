

export default class UserLogoutParameterHolder {


    userId: string = '';


    UserLogoutParameterHolder() {

        this.userId = '';

    }

    toMap(): {} {
        const map = {};

        map['user_id'] = this.userId;


        return map;
    }
}