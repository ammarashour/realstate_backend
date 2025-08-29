

export default class CreditCardBiddingParameterHolder {

    itemId: string = '';
    stripePublishKey: string = '';

    CreditCardBiddingParameterHolder() {
        this.itemId = '';
        this.stripePublishKey = '';
    }

    toMap(): {} {
        const map = {};
        map['item_id'] = this.itemId;
        map['stripe_key'] = this.stripePublishKey;

        return map;
    }
}