export default class UserBlockParameterHolder {

     loginUserId: string = '';
     addedUserId: string = '';

    UserBlockParameterHolder() {
        this.loginUserId = '';
        this.addedUserId = '';
    }

    toMap(): {} {
        const map = {};
        map['from_block_user_id'] = this.loginUserId;
        map['to_block_user_id'] = this.addedUserId;

        return map;
    }
}