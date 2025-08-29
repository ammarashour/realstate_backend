import { PsObject } from "@templateCore/object/core/PsObject";
import Payment from "./Payment";
import PaymentInfo from "./PaymentInfo";
import PaymentAttributes from "./PaymentAttributes";

export default class PackageWithPurchasedCount extends PsObject<PackageWithPurchasedCount> {

    id : string ='';
    paymentId : string ='';
    coreKeysId : string ='';
    shopId : string ='';
    payment : Payment = new Payment();
    purchasedCount : string = '';
    paymentInfo : PaymentInfo = new PaymentInfo();
    paymentAttributes : PaymentAttributes = new PaymentAttributes();
    addedDateStr : string ='';

    init(

        id : string,
        paymentId : string,
        coreKeysId : string,
        shopId : string,
        payment : Payment,
        purchasedCount : string,
        paymentInfo : PaymentInfo,
        paymentAttributes : PaymentAttributes,
        addedDateStr : string


    ) {
        this.id  = id ;
        this.paymentId  = paymentId ;
        this.coreKeysId  = coreKeysId ;
        this.shopId  = shopId ;
        this.payment = payment;
        this.purchasedCount = purchasedCount;
        this.paymentInfo = paymentInfo;
        this.paymentAttributes = paymentAttributes;
        this.addedDateStr = addedDateStr;

        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: PackageWithPurchasedCount): any {
        const map = {};

        map['id'] = object.id;
        map['payment_id'] = object.paymentId;
        map['coreKeys_id'] = object.coreKeysId;
        map['shop_id'] = object.shopId;
        map['payment'] = new Payment().toMap(object.payment);
        map['purchased_count'] = object.purchasedCount;
        map['payment_info'] = new PaymentInfo().toMap(object.paymentInfo);
        map['payment_attributes'] = new PaymentAttributes().toMap(object.paymentAttributes);
        map['added_date_str'] = object.addedDateStr;

        return map;
    }

    toMapList(objectList: PackageWithPurchasedCount[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new PackageWithPurchasedCount().init(

            obj.id,
            obj.payment_id,
            obj.core_keys_id,
            obj.shop_id,
            new Payment().fromMap(obj.payment),
            obj.purchased_count,
            new PaymentInfo().fromMap(obj.payment_info),
            new PaymentAttributes().fromMap(obj.payment_attributes),
            obj.added_date_str,

        );
    }

    fromMapList(objList: any[]): PackageWithPurchasedCount[] {
        const ratingList: PackageWithPurchasedCount[] = [];
        for (const obj in objList) {
            if (obj != null) {
                ratingList.push(this.fromMap(obj));
            }
        }

        return ratingList;
    }
}
