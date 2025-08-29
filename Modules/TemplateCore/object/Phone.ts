import { PsObject } from "@templateCore/object/core/PsObject";
import DefaultPhoto from "./DefaultPhoto";
import Category from "./Category";
import SubCategory from "./SubCategory";
// import ItemType from "./ItemType";
// import ItemPriceType from "./ItemPriceType";
import ItemCurrency from "./ItemCurrency";
import ItemLocation from "./ItemLocation";
import ItemLocationTownship from "./ItemLocationTownship";
// import ProductRelation from "./ProductRelation";
// import ConditionOfItem from "./ConditionOfItem";
// import DealOption from "./DealOption";
import User from "./User";

export default class Phone extends PsObject<Phone> {

    id: string = '';
    country_name: string = '';
    country_code: string = '';
    // itemTypeId : string = '';
    // itemPriceTypeId : string = '';
    status: string = '';
    is_default: string = '';


    init(

        id: string,
        country_name: string = '',
        country_code: string = '',
        status: string = '',
        is_default: string = '',

    ) {
        this.id = id;
        this.country_name = country_name;
        this.country_code = country_code;
        this.status = status;
        this.is_default = is_default;

        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: Phone): any {
        const map = {};

        map['id'] = object.id;
        map['country_name'] = object.country_name;
        map['country_code'] = object.country_code;
        // map['item_type_id'] = object.itemTypeId;
        // map['item_price_type_id'] = object.itemPriceTypeId;
        map['status'] = object.status;
        map['is_default'] = object.is_default;

        return map;
    }

    toMapList(objectList: Product[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new Phone().init(

            obj.id,
            obj.country_name,
            obj.country_code,
            obj.status,
            obj.is_default,
           );
    }

    fromMapList(objList: any[]): Phone[] {
        const productList: Phone[] = [];
        for (const obj in objList) {
            if (obj != null) {
                productList.push(this.fromMap(obj));
            }
        }

        return productList;
    }
}
