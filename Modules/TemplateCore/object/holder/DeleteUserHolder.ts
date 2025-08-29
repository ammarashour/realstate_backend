

export default class DeleteUserHolder {

    userId: string = '';


    DeleteUserHolder() {
        this.userId = '';

    }


    toMap(): {} {
        const map = {};
        map['user_id'] = this.userId;
        return map;
    }
}