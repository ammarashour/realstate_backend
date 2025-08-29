import { PsObject } from "@templateCore/object/core/PsObject";

export default class VendorPaymentAttributes extends PsObject<VendorPaymentAttributes> {

    duration : string ='';
    salePrice : string ='';
    discountPrice : string ='';
    status : string ='';
    currencySymbol : string ='';
    currencyShortForm : string ='';
    isMostPopularPlan : string ='';

    init(

        duration : string,
        salePrice : string,
        discountPrice : string,
        status : string,
        currencySymbol : string,
        currencyShortForm : string,
        isMostPopularPlan : string

    ) {
        this.duration = duration;
        this.salePrice = salePrice;
        this.discountPrice = discountPrice;
        this.status = status;
        this.currencySymbol = currencySymbol;
        this.currencyShortForm = currencyShortForm;
        this.isMostPopularPlan = isMostPopularPlan;

        return this;

    }

    getPrimaryKey(): string {
        return '';
    }

    toMap(object: VendorPaymentAttributes): any {
        const map = {};

        map['duration'] = object.duration;
        map['sale_price'] = object.salePrice;
        map['discount_price'] = object.discountPrice;
        map['status'] = object.status;
        map['currency_symbol'] = object.currencySymbol;
        map['currency_short_form'] = object.currencyShortForm;
        map['is_most_popular_plan'] = object.isMostPopularPlan;

        return map;
    }

    toMapList(objectList: VendorPaymentAttributes[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new VendorPaymentAttributes().init(

            obj.duration,
            obj.sale_price,
            obj.discount_price,
            obj.status,
            obj.currency_symbol,
            obj.currency_short_form,
            obj.is_most_popular_plan

        );
    }

    fromMapList(objList: any[]): VendorPaymentAttributes[] {
        const ratingList: VendorPaymentAttributes[] = [];
        for (const obj in objList) {
            if (obj != null) {
                ratingList.push(this.fromMap(obj));
            }
        }

        return ratingList;
    }
}
