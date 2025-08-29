export default class ChatNotiPrameterHolder {

    itemId: string = '';
    buyerUserId: string = '';
    sellerUserId: string = '';
    message: string = '';
    type: string = '';


    ChatNotiPrameterHolder() {
        this.itemId = '';
        this.buyerUserId = '';
        this.sellerUserId = '';
        this.message = '';
        this.type = '';


    }

    toMap(): {} {
        const map = {};
        map['item_id'] = this.itemId;
        map['buyer_user_id'] = this.buyerUserId;
        map['seller_user_id'] = this.sellerUserId;
        map['message'] = this.message;
        map['type'] = this.type;



        return map;
    }
}