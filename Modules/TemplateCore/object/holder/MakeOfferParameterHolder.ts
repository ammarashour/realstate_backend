import PsConst from "../constant/ps_constants";
export default class MakeOfferParameterHolder {

    itemId: string = '';
    buyerUserId: string = '';
    sellerUserId: string = '';
    negoPrice: string = '';
    type: string = '';
    isUserOnline:string = '';
    message: string = '';



    MakeOfferParameterHolder() {
        this.itemId = '';
        this.buyerUserId = '';
        this.sellerUserId = '';
        this.negoPrice = '';
        this.type =  PsConst.CHAT_TO_SELLER;
        this.isUserOnline = '';
        this.message = '';

    }

    AcceptOfferParameterHolder() {
        this.itemId = '';
        this.buyerUserId = '';
        this.sellerUserId = '';
        this.negoPrice = '';
        this.type =  PsConst.CHAT_TO_BUYER;
        this.isUserOnline = '';
        this.message = '';

    }

    toMap(): {} {
        const map = {};
        map['item_id'] = this.itemId;
        map['buyer_user_id'] = this.buyerUserId;
        map['seller_user_id'] = this.sellerUserId;
        map['nego_price'] = this.negoPrice;
        map['type'] = this.type;
        map['is_user_online'] = this.isUserOnline;
        map['message'] = this.message;

        return map;
    }
}