import { PsObject } from "@templateCore/object/core/PsObject";

export default class BillingAddress extends PsObject<BillingAddress> {

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

    init(
        id : string,
        firstName: string,
        lastName: string,
        email: string,
        phoneNo: string,
        address: string,
        country: string,
        state: string,
        city: string,
        postalCode: string,
        isSaveShippingInfoForNextTime: string,
        isSaveBillingInfoForNextTime: string,
    ) {
        this.id  = id;
        this.firstName = firstName;
        this.lastName = lastName;
        this.email = email;
        this.phoneNo = phoneNo;
        this.address = address;
        this.country = country;
        this.state = state;
        this.city = city;
        this.postalCode = postalCode;
        this.isSaveShippingInfoForNextTime = isSaveShippingInfoForNextTime;
        this.isSaveBillingInfoForNextTime = isSaveBillingInfoForNextTime;

        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: BillingAddress): any {
        const map = {};
        map['id'] = object.id;
        map['billing_first_name'] = object.firstName;
        map['billing_last_name'] = object.lastName;
        map['billing_email'] = object.email;
        map['billing_phone_no'] = object.phoneNo;
        map['billing_address'] = object.address;
        map['billing_country'] = object.country;
        map['billing_state'] = object.state;
        map['billing_city'] = object.city;
        map['billing_postal_code'] = object.postalCode;
        map['is_save_shipping_info_for_next_time'] = object.isSaveShippingInfoForNextTime;
        map['is_save_billing_info_for_next_time'] = object.isSaveBillingInfoForNextTime;

        return map;
    }

    toMapList(objectList: BillingAddress[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }
        return mapList;
    }

    fromMap(obj: any) {
        return new BillingAddress().init(
            obj.id,
            obj.billing_first_name,
            obj.billing_last_name,
            obj.billing_email,
            obj.billing_phone_no,
            obj.billing_address,
            obj.billing_country,
            obj.billing_state,
            obj.billing_city,
            obj.billing_postal_code,
            obj.is_save_shipping_info_for_next_time,
            obj.is_save_billing_info_for_next_time,
        );
    }

    fromMapList(objList: any[]): BillingAddress[] {
        const list: BillingAddress[] = [];
        for (const obj of objList as Array<BillingAddress>) {
            if (obj != null) {
                list.push(this.fromMap(obj));
            }
        }

        return list;
    }
}
