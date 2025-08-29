

export default class ResetUnReadMessageHolder {

    itemId: string = '';
    buyerUserId: string = '';
    sellerUserId: string = '';
    type: string = '';


    ResetUnReadMessageHolder() {
        this.itemId = '';
        this.buyerUserId = '';
        this.sellerUserId = '';
        this.type = '';

    }


    toMap(): {} {
        const map = {};
        map['item_id'] = this.itemId;
        map['buyer_user_id'] = this.buyerUserId;
        map['seller_user_id'] = this.sellerUserId;
        map['type'] = this.type;
        return map;
    }
}