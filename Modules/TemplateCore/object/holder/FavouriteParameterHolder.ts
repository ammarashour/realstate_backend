

export default class FavouriteParameterHolder {

    itemId: string = '';
    userId: string = '';

    FavouriteParameterHolder() {
        this.itemId = '';
        this.userId = '';
    }

    toMap(): {} {
        const map = {};
        map['item_id'] = this.itemId;
        map['user_id'] = this.userId;

        return map;
    }
}