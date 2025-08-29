export default class ShippingAddressParameterHolder
{
    id : string = '';
    firstName: string = '';
    lastName: string = '';
    email: string = '';
    phoneNo: string = '';
    address: string = '';
    country: string = '';
    state: string = '';
    city: string = '';
    postalCode: string = '';
    isSaveShippingInfoForNextTime: string = '';
    isSaveBillingInfoForNextTime: string = '';

    ShippingAddressParameterHolder(){
        this.id = '';
        this.firstName = '';
        this.lastName = '';
        this.email = '';
        this.phoneNo = '';
        this.address = '';
        this.country = '';
        this.state = '';
        this.city = '';
        this.postalCode = '';
        this.isSaveShippingInfoForNextTime = '';
        this.isSaveBillingInfoForNextTime = '';
    }

    toMap(): {} {
        const map = {};
        map['id'] = this.id;
        map['shipping_first_name'] = this.firstName;
        map['shipping_last_name'] = this.lastName;
        map['shipping_email'] = this.email;
        map['shipping_phone_no'] = this.phoneNo;
        map['shipping_address'] = this.address;
        map['shipping_country'] = this.country;
        map['shipping_state'] = this.state;
        map['shipping_city'] = this.city;
        map['shipping_postal_code'] = this.postalCode;
        map['is_save_shipping_info_for_next_time'] = this.isSaveShippingInfoForNextTime;
        map['is_save_billing_info_for_next_time'] = this.isSaveBillingInfoForNextTime;
        return map;
    }
}
