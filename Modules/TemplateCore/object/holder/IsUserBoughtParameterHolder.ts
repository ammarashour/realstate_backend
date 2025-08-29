export default class IsUserBoughtParameterHolder {

    itemId: string = '';
    buyerUserId: string = '';
    sellerUserId: string = '';
    isUserOnline: string = '';



    IsUserBoughtParameterHolder() {
        this.itemId = '';
        this.buyerUserId = '';
        this.sellerUserId = '';
        this.isUserOnline='';

    }

    toMap(): {} {
        const map = {};
        map['item_id'] = this.itemId;
        map['buyer_user_id'] = this.buyerUserId;
        map['seller_user_id'] = this.sellerUserId;
        map['is_user_online'] = this.isUserOnline;
        return map;
    }
}