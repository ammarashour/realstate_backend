export default class UnBlockUserHolder {

    fromBlockUserId: string = '';
    toBlockUserId: string = '';

    UnBlockUserHolder() {
        this.fromBlockUserId = '';
        this.toBlockUserId = '';
    }

    toMap(): {} {
        const map = {};
        map['from_block_user_id'] = this.fromBlockUserId;
        map['to_block_user_id'] = this.toBlockUserId;

        return map;
    }
}