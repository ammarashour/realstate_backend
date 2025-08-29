export default class ItemLimitParameterHolder {

    userId: string = '';
    packageId: string = '';
    paymentMethod: string = '';
    paymentMethodNounce: string = '';
    price: string = '';
    razorId: string = '';
    isPaystack: string = '';
    currency: string = '';

    ItemLimitParameterHolder() {
        this.userId = '';
        this.packageId = '';
        this.paymentMethod = '';
        this.paymentMethodNounce = '';
        this.price = '';
        this.razorId = '';
        this.isPaystack = '';
        this.currency = '';
    }

    toMap(): {} {
        const map = {};
        map['user_id'] = this.userId;
        map['package_id'] = this.packageId;
        map['payment_method'] = this.paymentMethod;
        map['payment_method_nonce'] = this.paymentMethodNounce;
        map['price'] = this.price;
        map['razor_id'] = this.razorId;
        map['is_paystack'] = this.isPaystack;
        map['currency'] = this.currency;

        return map;
    }
}
