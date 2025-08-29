import { PsObject } from "@templateCore/object/core/PsObject";
import CartItem from "./CartItem";


export default class Cart extends PsObject<Cart> {

    items : CartItem[] = [new CartItem()];
    vendorId : string = '';
    vendorCurrencySymbol : string = '';
    vendorCurrencyShortForm : string = '';
    subTotal : string = '';
    totalDiscount : string = '';
    deliveryFee : string = '';
    total : string = '';

    init(
        items : CartItem[],
        vendorId : string,
        vendorCurrencySymbol : string,
        vendorCurrencyShortForm : string,
        subTotal : string,
        totalDiscount : string,
        deliveryFee : string,
        total : string,
    ) {
        this.items = items;
        this.vendorId = vendorId;
        this.vendorCurrencySymbol = vendorCurrencySymbol;
        this.vendorCurrencyShortForm = vendorCurrencyShortForm;
        this.subTotal = subTotal;
        this.totalDiscount = totalDiscount;
        this.deliveryFee = deliveryFee;
        this.total = total;

        return this;

    }

    getPrimaryKey(): string {
        return '';
    }

    toMap(object: Cart): any {
        const map = {};

        map['items'] = new CartItem().toMapList(object.items);
        map['vendor_id'] = object.vendorId;
        map['vendor_currency_symbol'] = object.vendorCurrencySymbol;
        map['vendor_currency_short_form'] = object.vendorCurrencyShortForm;
        map['sub_total'] = object.subTotal;
        map['total_discount'] = object.totalDiscount;
        map['delivery_fee'] = object.deliveryFee;
        map['total'] = object.total;


        return map;
    }

    toMapList(objectList: Cart[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new Cart().init(
            new CartItem().fromMapList(obj.items),
            obj.vendor_id,
            obj.vendor_currency_symbol,
            obj.vendor_currency_short_form,
            obj.sub_total,
            obj.total_discount,
            obj.delivery_fee,
            obj.total,
        );
    }

    fromMapList(objList: any[]): Cart[] {
        const list: Cart[] = [];
        for (const obj of objList as Array<Cart>) {
            if (obj != null) {
                list.push(this.fromMap(obj));
            }
        }

        return list;
    }
}
