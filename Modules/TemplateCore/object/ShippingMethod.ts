import { PsObject } from "./core/PsObject";

export default class ShippingMethod extends PsObject<ShippingMethod>{
    id: string = '';
    name: string = '';
    price: string = '';
    days: string = '';
    addedDate: string = '';
    addedUserId: string = '';
    updatedDate: string = '';
    updatedUserId: string = '';
    addedDateStr: string = '';
    updatedFlag: string = '';
    isPublished: string = '';
    currencySymbol: string = '';
    currencyShortForm: string = '';

    init(
        id: string,
        name: string,
        price: string,
        days: string,
        addedDate: string,
        addedUserId: string,
        updatedDate: string,
        updatedUserId: string,
        addedDateStr: string,
        updatedFlag: string,
        isPublished: string,
        currencySymbol: string,
        currencyShortForm: string,

    ) {
        this.id = id;
        this.name = name;
        this.price = price;
        this.days = days;
        this.addedDate = addedDate;
        this.addedUserId = addedUserId;
        this.updatedDate = updatedDate;
        this.updatedUserId = updatedUserId;
        this.addedDateStr = addedDateStr;
        this.updatedFlag = updatedFlag;
        this.isPublished = isPublished;
        this.currencySymbol = currencySymbol;
        this.currencyShortForm = currencyShortForm;

        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: ShippingMethod): any {
        const map = {};
        map['id'] = object.id;
        map['name'] = object.name;
        map['price'] = object.price;
        map['days'] = object.days;
        map['added_date'] = object.addedDate;
        map['added_user_id'] = object.addedUserId;
        map['updated_date'] = object.updatedDate;
        map['updated_user_id'] = object.updatedUserId;
        map['added_date_str'] = object.addedDateStr;
        map['updated_flag'] = object.updatedFlag;
        map['is_published'] = object.isPublished;
        map['currency_symbol'] = object.currencySymbol;
        map['currency_short_form'] = object.currencyShortForm;

        return map;
    }

    toMapList(objectList: ShippingMethod[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new ShippingMethod().init(


            obj.id,
            obj.name,
            obj.price,
            obj.days,
            obj.added_date,
            obj.added_user_id,
            obj.updated_date,
            obj.updated_user_id,
            obj.added_date_str,
            obj.updated_flag,
            obj.is_published,
            obj.currency_symbol,
            obj.currency_short_form,

        );
    }
    fromMapList(objList: any[]): ShippingMethod[] {
        const shippingMethodList : ShippingMethod[] = [];
        for (const obj in objList) {
            if (obj != null) {
                shippingMethodList.push(this.fromMap(obj));
            }
        }

        return shippingMethodList;
    }


}