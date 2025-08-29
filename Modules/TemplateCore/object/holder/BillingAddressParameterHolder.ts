export default class BillingAddressParameterHolder
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

    BillingAddressParameterHolder(){
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
        map['billing_first_name'] = this.firstName;
        map['billing_last_name'] = this.lastName;
        map['billing_email'] = this.email;
        map['billing_phone_no'] = this.phoneNo;
        map['billing_address'] = this.address;
        map['billing_country'] = this.country;
        map['billing_state'] = this.state;
        map['billing_city'] = this.city;
        map['billing_postal_code'] = this.postalCode;
        map['is_save_shipping_info_for_next_time'] = this.isSaveShippingInfoForNextTime;
        map['is_save_billing_info_for_next_time'] = this.isSaveBillingInfoForNextTime;
        return map;
    }
}
