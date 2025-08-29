

export default class SyncChatHistoryParameterHolder {

    itemId: string = '';
    buyerUserId: string = '';
    sellerUserId: string = '';
    type: string = '';
    isUserOnline:string = '';
    message: string = '';


    SyncChatHistoryParameterHolder() {
        this.itemId = '';
        this.buyerUserId = '';
        this.sellerUserId = '';
        this.type = '';
        this.isUserOnline='';
        this.message = '';

    }


    toMap(): {} {
        const map = {};
        map['item_id'] = this.itemId;
        map['buyer_user_id'] = this.buyerUserId;
        map['seller_user_id'] = this.sellerUserId;
        map['type'] = this.type;
        map['is_user_online'] = this.isUserOnline;
        map['message'] = this.message;
        return map;
    }
}