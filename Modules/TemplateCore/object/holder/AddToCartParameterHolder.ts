export default class AddToCartParameterHolder {

    vendorId: string = '';
    itemId: string = '';
    quantity: string = '';
    userId: string = '';
    isSelect: string = '';

    AddToCartParameterHolder() {
        this.vendorId = '';
        this.itemId = '';
        this.quantity = '';
        this.userId = '';
        this.isSelect = '';
    }

    toMap(): {} {
        const map = {};
        map['vendor_id'] = this.vendorId;
        map['item_id'] = this.itemId;
        map['quantity'] = this.quantity;
        map['user_id'] = this.userId;
        map['is_select'] = this.isSelect;


        return map;
    }
}
