import { PsObject } from "./core/PsObject";
import Payment from "./Payment";
import CoreKey from "./CoreKey";
import PaymentInfo from "./PaymentInfo";
import VendorPaymentAttributes from "./VendorPaymentAttributes";

export default class VendorSubScriptionPlan extends PsObject<VendorSubScriptionPlan>{

    id: string = '';
    paymentId: string = '';
    coreKeysId: string = '';
    shopId: string = '';
    payment: Payment = new Payment();
    coreKey: CoreKey = new CoreKey();
    paymentInfo: PaymentInfo = new PaymentInfo();
    paymentAttributes: VendorPaymentAttributes = new VendorPaymentAttributes();
    addedDateStr: string = '';

    init(
        id: string,
        paymentId: string,
        coreKeysId: string,
        shopId: string,
        payment: Payment,
        coreKey: CoreKey,
        paymentInfo: PaymentInfo,
        paymentAttributes: VendorPaymentAttributes,
        addedDateStr: string

    ) {
        this.id = id;
        this.paymentId = paymentId;
        this.coreKeysId = coreKeysId;
        this.shopId = shopId;
        this.payment = payment;
        this.coreKey = coreKey;
        this.paymentInfo = paymentInfo;
        this.paymentAttributes = paymentAttributes;
        this.addedDateStr = addedDateStr;

        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }


    fromMap(obj: any) {
        return new VendorSubScriptionPlan().init(
         obj.id,
         obj.payment_id,
         obj.core_keys_id,
         obj.shop_id,
         new Payment().fromMap(obj.payment),
         new CoreKey().fromMap(obj.core_key),
         new PaymentInfo().fromMap(obj.payment_info),
         new VendorPaymentAttributes().fromMap(obj.payment_attributes),
         obj.added_date_str,
        );
    }


    fromMapList(objList : any[] ) : VendorSubScriptionPlan[] {
        const VendorSubScriptionPlan : VendorSubScriptionPlan[] = [];
        for(const obj in objList) {
            if(obj != null) {
                VendorSubScriptionPlan.push(this.fromMap(obj));
            }
        }

        return VendorSubScriptionPlan;
    }


    toMap(object: VendorSubScriptionPlan): any {
        const map = {};
        map['id'] = object.id;
        map['payment_id'] = object.paymentId;
        map['core_keys_id'] = object.coreKeysId;
        map['shop_id'] = object.shopId;
        map['payment'] = object.payment;
        map['core_key'] = object.coreKey;
        map['payment_info'] = object.paymentInfo;
        map['payment_attributes'] = object.paymentAttributes;
        map['added_date_str'] = object.addedDateStr;

        return map;
    }

    toMapList(objectList: VendorSubScriptionPlan[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }


}
