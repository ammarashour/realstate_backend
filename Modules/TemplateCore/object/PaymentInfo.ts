import { PsObject } from "@templateCore/object/core/PsObject";

export default class PaymentInfo extends PsObject<PaymentInfo> {

    id : string ='';
    paymentId : string ='';
    coreKeysId : string ='';
    value : string ='';
    uiTypeId : string ='';
    shopId : string ='';
    addedDateStr : string ='';

    init(

        id : string,
        paymentId : string,
        coreKeysId : string,
        value : string,
        uiTypeId : string,
        shopId : string,
        addedDateStr : string,


    ) {
        this.id = id;
        this.paymentId = paymentId;
        this.coreKeysId = coreKeysId;
        this.value = value;
        this.uiTypeId = uiTypeId;
        this.shopId = shopId;
        this.addedDateStr = addedDateStr;

        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: PaymentInfo): any {
        const map = {};

        map['id'] = object.id;
        map['payment_id'] = object.paymentId;
        map['core_keys_id'] = object.coreKeysId;
        map['value'] = object.value;
        map['ui_type_id'] = object.uiTypeId;
        map['shop_id'] = object.shopId;
        map['added_date_str'] = object.addedDateStr;

        return map;
    }

    toMapList(objectList: PaymentInfo[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new PaymentInfo().init(

            obj.id,
            obj.payment_id,
            obj.core_keys_id,
            obj.value,
            obj.ui_type_id,
            obj.shop_id,
            obj.added_date_str,

        );
    }

    fromMapList(objList: any[]): PaymentInfo[] {
        const ratingList: PaymentInfo[] = [];
        for (const obj in objList) {
            if (obj != null) {
                ratingList.push(this.fromMap(obj));
            }
        }

        return ratingList;
    }
}
