import { PsObject } from "@templateCore/object/core/PsObject";

export default class ShippingAddress extends PsObject<ShippingAddress> {

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

    toMap(object: ShippingAddress): any {
        const map = {};
        map['id'] = object.id;
        map['shipping_first_name'] = object.firstName;
        map['shipping_last_name'] = object.lastName;
        map['shipping_email'] = object.email;
        map['shipping_phone_no'] = object.phoneNo;
        map['shipping_address'] = object.address;
        map['shipping_country'] = object.country;
        map['shipping_state'] = object.state;
        map['shipping_city'] = object.city;
        map['shipping_postal_code'] = object.postalCode;
        map['is_save_shipping_info_for_next_time'] = object.isSaveShippingInfoForNextTime;
        map['is_save_billing_info_for_next_time'] = object.isSaveBillingInfoForNextTime;

        return map;
    }

    toMapList(objectList: ShippingAddress[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }
        return mapList;
    }

    fromMap(obj: any) {
        return new ShippingAddress().init(
            obj.id,
            obj.shipping_first_name,
            obj.shipping_last_name,
            obj.shipping_email,
            obj.shipping_phone_no,
            obj.shipping_address,
            obj.shipping_country,
            obj.shipping_state,
            obj.shipping_city,
            obj.shipping_postal_code,
            obj.is_save_shipping_info_for_next_time,
            obj.is_save_billing_info_for_next_time,
        );
    }

    fromMapList(objList: any[]): ShippingAddress[] {
        const list: ShippingAddress[] = [];
        for (const obj of objList as Array<ShippingAddress>) {
            if (obj != null) {
                list.push(this.fromMap(obj));
            }
        }

        return list;
    }
}
