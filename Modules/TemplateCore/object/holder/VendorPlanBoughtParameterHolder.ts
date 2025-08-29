export default class VendorPlanBoughtParameterHolder {

    userId: string = '';
    subscriptionPlanId: string = '';
    paymentMethod: string = '';
    paymentMethodNounce: string = '';
    price: string = '';
    razorId: string = '';
    isPaystack: string = '';
    vendorId : string = '';

    VendorPlanBoughtParameterHolder() {
        this.userId = '';
        this.subscriptionPlanId = '';
        this.paymentMethod = '';
        this.paymentMethodNounce = '';
        this.price = '';
        this.razorId = '';
        this.isPaystack = '';
        this.vendorId = '';
    }

    toMap(): {} {
        const map = {};
        map['user_id'] = this.userId;
        map['subscription_plan_id'] = this.subscriptionPlanId;
        map['payment_method'] = this.paymentMethod;
        map['payment_method_nonce'] = this.paymentMethodNounce;
        map['price'] = this.price;
        map['razor_id'] = this.razorId;
        map['is_paystack'] = this.isPaystack;
        map['vendor_id'] = this.vendorId;

        return map;
    }
}