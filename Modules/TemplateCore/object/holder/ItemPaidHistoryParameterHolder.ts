export default class ItemPaidHistoryParameterHolder {

    itemId: string = '';
    amount: string = '';
    startDate: string = '';
    howManyDay: string = '';
    paymentMethod: string = '';
    paymentMethodNounce: string = '';
    startTimeStamp: string = '';
    razorId: string = '';
    purchasedId: string = '';
    isPaystack: string = '';

    ItemPaidHistoryParameterHolder() {
        this.itemId = '';
        this.amount = '';
        this.startDate = '';
        this.howManyDay = '';
        this.paymentMethod = '';
        this.paymentMethodNounce = '';
        this.startTimeStamp = '';
        this.razorId = '';
        this.purchasedId = '';
        this.isPaystack = '';
    }

    toMap(): {} {
        const map = {};
        map['item_id'] = this.itemId;
        map['amount'] = this.amount;
        map['start_date'] = this.startDate;
        map['how_many_day'] = this.howManyDay;
        map['payment_method'] = this.paymentMethod;
        map['payment_method_nonce'] = this.paymentMethodNounce;
        map['start_timestamp'] = this.startTimeStamp;
        map['razor_id'] = this.razorId;
        map['purchased_id'] = this.purchasedId;
        map['is_paystack'] = this.isPaystack;

        return map;
    }
}