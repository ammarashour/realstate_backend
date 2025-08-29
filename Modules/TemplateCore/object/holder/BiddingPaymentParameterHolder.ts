export default class BiddingPaymentParameterHolder {

    itemId: string = '';
    amount: string = '';
    biddingId: string = '';
    sellerId: string = '';
    buyerId: string = '';
    paymentMethodNounce: string = '';
    paymentMethod: string = '';

    BiddingPaymentParameterHolder() {
        this.itemId = '';
        this.amount = '';
        this.biddingId = '';
        this.sellerId = '';
        this.buyerId = '';
        this.paymentMethodNounce = '';
        this.paymentMethod = '';

    }

    toMap(): {} {
        const map = {};
        map['item_id'] = this.itemId;
        map['amount'] = this.amount;
        map['bidding_id'] = this.biddingId;
        map['seller_id'] = this.sellerId;
        map['buyer_id'] = this.buyerId;
        map['payment_method_nonce'] = this.paymentMethodNounce;
        map['payment_method'] = this.paymentMethod;


        return map;
    }
}