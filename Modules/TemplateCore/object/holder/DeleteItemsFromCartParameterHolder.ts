export default class DeleteItemsFromCartParameterHolder {

    ids: any[] = [];


    DeleteItemsFromCartParameterHolder() {
        this.ids = [];
    }

    toMap(): {} {
        const map = {};
        map['cart_item_ids'] = this.ids;

        return map;
    }
}
