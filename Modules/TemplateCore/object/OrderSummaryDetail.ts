import { PsObject } from "@templateCore/object/core/PsObject";
import ItemInfo from "./ItemInfo";

export default class OrderSummaryDetail extends PsObject<OrderSummaryDetail> {

    orderCode : string;
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
    paymentMethod: string = '';
    paymentStatus: string = '';
    paymentStatusColor: string = '';
    orderStatus: string = '';
    orderStatusColor: string = '';
    paymentDate: string = '';
    vendorName : string = '';
    itemInfo : ItemInfo[] = [new ItemInfo()];
    subTotal : string;
    totalDiscount : string;
    deliveryFee : string;
    total : string;

    init(
        orderCode : string,
        shippingFirstName: string,
        shippingLastName: string,
        shippingEmail: string,
        shippingPhoneNo: string,
        shippingAddress: string,
        shippingCountry: string,
        shippingState: string,
        shippingCity: string,
        shippingPostalCode: string,
        isSaveShippingInfoForNextTime: string,
        billingFirstName: string,
        billingLastName: string,
        billingEmail: string,
        billingPhoneNo: string,
        billingAddress: string,
        billingCountry: string,
        billingState: string,
        billingCity: string,
        billingPostalCode: string,
        isSaveBillingInfoForNextTime: string,
        paymentMethod: string,
        paymentStatus: string,
        paymentStatusColor: string,
        orderStatus: string,
        orderStatusColor: string,
        paymentDate : string,
        vendorName : string,
        itemInfo : ItemInfo[],
        subTotal : string,
        totalDiscount : string,
        deliveryFee : string,
        total : string
    ) {
        this.orderCode  = orderCode;
        this.shippingFirstName = shippingFirstName;
        this.shippingLastName = shippingLastName;
        this.shippingEmail = shippingEmail;
        this.shippingPhoneNo = shippingPhoneNo;
        this.shippingAddress = shippingAddress;
        this.shippingCountry = shippingCountry;
        this.shippingState = shippingState;
        this.shippingCity = shippingCity;
        this.shippingPostalCode = shippingPostalCode;
        this.isSaveShippingInfoForNextTime = isSaveShippingInfoForNextTime;
        this.billingFirstName = billingFirstName;
        this.billingLastName = billingLastName;
        this.billingEmail = billingEmail;
        this.billingPhoneNo = billingPhoneNo;
        this.billingAddress = billingAddress;
        this.billingCountry = billingCountry;
        this.billingState = billingState;
        this.billingCity = billingCity;
        this.billingPostalCode = billingPostalCode;
        this.isSaveBillingInfoForNextTime = isSaveBillingInfoForNextTime;
        this.paymentMethod = paymentMethod;
        this.paymentStatus = paymentStatus;
        this.paymentStatusColor = paymentStatusColor;
        this.orderStatus = orderStatus;
        this.orderStatusColor = orderStatusColor;
        this.paymentDate  = paymentDate;
        this.vendorName  = vendorName;
        this.itemInfo =itemInfo;
        this.subTotal = subTotal;
        this.totalDiscount = totalDiscount;
        this.deliveryFee = deliveryFee;
        this.total = total;
        return this;

    }

    getPrimaryKey(): string {
        return '';
    }

    toMap(object: OrderSummaryDetail): any {
        const map = {};
        map['order_code'] = object.orderCode;
        map['shipping_first_name'] = object.shippingFirstName;
        map['shipping_last_name'] = object.shippingLastName;
        map['shipping_email'] = object.shippingEmail;
        map['shipping_phone_no'] = object.shippingPhoneNo;
        map['shipping_address'] = object.shippingAddress;
        map['shipping_country'] = object.shippingCountry;
        map['shipping_state'] = object.shippingState;
        map['shipping_city'] = object.shippingCity;
        map['shipping_postal_code'] = object.shippingPostalCode;
        map['is_save_shipping_info_for_next_time'] = object.isSaveShippingInfoForNextTime;
        map['billing_first_name'] = object.shippingFirstName;
        map['billing_last_name'] = object.shippingLastName;
        map['billing_email'] = object.shippingEmail;
        map['billing_phone_no'] = object.shippingPhoneNo;
        map['billing_address'] = object.shippingAddress;
        map['billing_country'] = object.shippingCountry;
        map['billing_state'] = object.shippingState;
        map['billing_city'] = object.shippingCity;
        map['billing_postal_code'] = object.shippingPostalCode;
        map['is_save_billing_info_for_next_time'] = object.isSaveShippingInfoForNextTime;
        map['payment_method'] = object.paymentMethod;
        map['payment_status'] = object.paymentStatus;
        map['payment_status_color'] = object.paymentStatusColor;
        map['order_status'] = object.orderStatus;
        map['order_status_color'] = object.orderStatusColor;
        map['payment_date'] = object.paymentDate;
        map['vendor_name'] = object.vendorName;
        map['item_info'] = new ItemInfo().toMapList(object.itemInfo);
        map['sub_total'] = object.subTotal;
        map['total_discount'] = object.totalDiscount;
        map['delivery_fee'] = object.deliveryFee;
        map['total'] = object.total;
        return map;
    }

    toMapList(objectList: OrderSummaryDetail[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }
        return mapList;
    }

    fromMap(obj: any) {
        return new OrderSummaryDetail().init(
            obj.order_code,
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
            obj.billing_first_name,
            obj.billing_last_name,
            obj.billing_email,
            obj.billing_phone_no,
            obj.billing_address,
            obj.billing_country,
            obj.billing_state,
            obj.billing_city,
            obj.billing_postal_code,
            obj.is_save_billing_info_for_next_time,
            obj.payment_method,
            obj.payment_status,
            obj.payment_status_color,
            obj.order_status,
            obj.order_status_color,
            obj.payment_date,
            obj.vendor_name,
            new ItemInfo().fromMapList(obj.item_info),
            obj.sub_total,
            obj.total_discount,
            obj.delivery_fee,
            obj.total,
        );
    }

    fromMapList(objList: any[]): OrderSummaryDetail[] {
        const list: OrderSummaryDetail[] = [];
        for (const obj of objList as Array<OrderSummaryDetail>) {
            if (obj != null) {
                list.push(this.fromMap(obj));
            }
        }

        return list;
    }
}
