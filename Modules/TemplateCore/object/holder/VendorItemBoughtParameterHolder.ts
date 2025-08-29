export default class VendorItemBoughtParameterHolder {

    userId: string = '';
    paymentMethod: string = '';
    paymentMethodNounce: string = '';
    paymentAmount: string = '';
    razorId: string = '';
    isPaystack: string = '';
    orderId: string = '';
    vendorId : string = '';
    currencyId : string = '';
    isSingleCheckout : string = '';

    VendorItemBoughtParameterHolder() {
        this.userId = '';
        this.paymentMethod = '';
        this.paymentMethodNounce = '';
        this.paymentAmount = '';
        this.razorId = '';
        this.isPaystack = '';
        this.orderId = '';
        this.vendorId = '';
        this.currencyId = '';
        this.isSingleCheckout = '';
    }

    toMap(): {} {
        const map = {};
        map['user_id'] = this.userId;
        map['payment_method'] = this.paymentMethod;
        map['payment_method_nonce'] = this.paymentMethodNounce;
        map['payment_amount'] = this.paymentAmount;
        map['razor_id'] = this.razorId;
        map['is_paystack'] = this.isPaystack;
        map['order_id'] = this.orderId;
        map['vendor_id'] = this.vendorId;
        map['currency_id'] = this.currencyId;
        map['is_single_checkout'] = this.isSingleCheckout;

        return map;
    }
}
