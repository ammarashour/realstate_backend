import OrderSummaryParameterHolder from "./OrderSummaryParameterHolder";
import OrderSummaryObj from "../OrderSummaryObj";

export default class ShippingAndBillingParameterHolder {
    vendorId: string = '';
    shippingFirstName: string = '';
    shippingLastName: string = '';
    shippingEmail: string = '';
    shippingPhoneNo: string = '';
    shippingAddress: string = '';
    shippingCountry: string = '';
    shippingState: string = '';
    shippingCity: string = '';
    shippingPostalCode: string = '';
    isSaveShippingInfoForNextTime: string = '';
    billingFirstName: string = '';
    billingLastName: string = '';
    billingEmail: string = '';
    billingPhoneNo: string = '';
    billingAddress: string = '';
    billingCountry: string = '';
    billingState: string = '';
    billingCity: string = '';
    billingPostalCode: string = '';
    isSaveBillingInfoForNextTime: string = '';
    orderSummary: OrderSummaryObj[] = [new OrderSummaryObj()];
    totalPrice: string = '';

    ShippingAndBillingParameterHolder() {
        this.vendorId = '';
        this.shippingFirstName = '';
        this.shippingLastName = '';
        this.shippingEmail = '';
        this.shippingPhoneNo = '';
        this.shippingAddress = '';
        this.shippingCountry = '';
        this.shippingState = '';
        this.shippingCity = '';
        this.shippingPostalCode = '';
        this.isSaveShippingInfoForNextTime = '';
        this.billingFirstName = '';
        this.billingLastName = '';
        this.billingEmail = '';
        this.billingPhoneNo = '';
        this.billingAddress = '';
        this.billingCountry = '';
        this.billingState = '';
        this.billingCity = '';
        this.billingPostalCode = '';
        this.isSaveBillingInfoForNextTime = '';
        this.orderSummary = [];
        this.totalPrice = '';
    }

    toMap(): {} {
        const map = {};
        map['vendor_id'] = this.vendorId;
        map['shipping_first_name'] = this.shippingFirstName;
        map['shipping_last_name'] = this.shippingLastName;
        map['shipping_email'] = this.shippingEmail;
        map['shipping_phone_no'] = this.shippingPhoneNo;
        map['shipping_address'] = this.shippingAddress;
        map['shipping_country'] = this.shippingCountry;
        map['shipping_state'] = this.shippingState;
        map['shipping_city'] = this.shippingCity;
        map['shipping_postal_code'] = this.shippingPostalCode;
        map['is_save_shipping_info_for_next_time'] = this.isSaveShippingInfoForNextTime;
        map['billing_first_name'] = this.billingFirstName;
        map['billing_last_name'] = this.billingLastName;
        map['billing_email'] = this.billingEmail;
        map['billing_phone_no'] = this.billingPhoneNo;
        map['billing_address'] = this.billingAddress;
        map['billing_country'] = this.billingCountry;
        map['billing_state'] = this.billingState;
        map['billing_city'] = this.billingCity;
        map['billing_postal_code'] = this.billingPostalCode;
        map['is_save_billing_info_for_next_time'] = this.isSaveBillingInfoForNextTime;
        map['order_summary'] = this.orderSummary;
        map['total_price'] = this.totalPrice;
        return map;
    }
}
