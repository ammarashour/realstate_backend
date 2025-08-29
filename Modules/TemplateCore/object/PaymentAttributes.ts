import { PsObject } from "@templateCore/object/core/PsObject";

export default class PaymentAttributes extends PsObject<PaymentAttributes> {

    type : string ='';
    count : string ='';
    price : string ='';
    status : string ='';
    currencySymbol : string ='';
    currencyShortForm : string ='';

    init(

        type : string,
        count : string,
        price : string,
        status : string,
        currencySymbol : string,
        currencyShortForm : string,


    ) {
        this.type = type;
        this.count = count;
        this.price = price;
        this.status = status;
        this.currencySymbol = currencySymbol;
        this.currencyShortForm = currencyShortForm;

        return this;

    }

    getPrimaryKey(): string {
        return this.type;
    }

    toMap(object: PaymentAttributes): any {
        const map = {};

        map['type'] = object.type;
        map['count'] = object.count;
        map['price'] = object.price;
        map['status'] = object.status;
        map['currency_symbol'] = object.currencySymbol;
        map['currency_short_form'] = object.currencyShortForm;

        return map;
    }

    toMapList(objectList: PaymentAttributes[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new PaymentAttributes().init(

            obj.type,
            obj.count,
            obj.price,
            obj.status,
            obj.currency_symbol,
            obj.currency_short_form,

        );
    }

    fromMapList(objList: any[]): PaymentAttributes[] {
        const ratingList: PaymentAttributes[] = [];
        for (const obj in objList) {
            if (obj != null) {
                ratingList.push(this.fromMap(obj));
            }
        }

        return ratingList;
    }
}
