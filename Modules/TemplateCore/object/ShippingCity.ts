import { PsObject } from "./core/PsObject";

export default class ShippingCity extends PsObject<ShippingCity>{

    id: string = '';
    countryId : string ='';
    name: string = '';
    status: string = '';
    addedDate: string = '';
    addedUserId: string = '';
    updatedDate: string = '';
    updatedUserId: string = '';
    updatedFlag: string = '';
    addedDateStr: string = '';

    init(
        id: string,
        countryId: string,
        name: string,
        status: string,
        addedDate: string,
        addedUserId: string,
        updatedDate: string,
        updatedUserId: string,
        updatedFlag: string,
        addedDateStr: string,

    ) {
        this.id = id;
        this.countryId = countryId;
        this.name = name;
        this.status = status;
        this.addedDate = addedDate;
        this.addedUserId = addedUserId;
        this.updatedDate = updatedDate;
        this.updatedUserId = updatedUserId;
        this.updatedFlag = updatedFlag;
        this.addedDateStr = addedDateStr;

        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: ShippingCity): any {
        const map = {};
        map['id'] = object.id;
        map['country_id'] = object.countryId;
        map['name'] = object.name;
        map['status'] = object.status;
        map['added_date'] = object.addedDate;
        map['added_user_id'] = object.addedUserId;
        map['updated_date'] = object.updatedDate;
        map['updated_user_id'] = object.updatedUserId;
        map['updated_flag'] = object.updatedFlag;
        map['added_date_str'] = object.addedDateStr;

        return map;
    }

    toMapList(objectList: ShippingCity[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new ShippingCity().init(


          obj.id,
          obj.country_id,
          obj.name,
          obj.status,
          obj.added_date,
          obj.added_user_id,
          obj.updated_date,
          obj.updated_user_id,
          obj.updated_flag,
          obj.added_date_str,

       );
    }   
    fromMapList(objList : any[] ) : ShippingCity[] {
        const shippingCityList : ShippingCity[] = [];
        for(const obj in objList) {
            if(obj != null) {
                shippingCityList.push(this.fromMap(obj));
            }
        }

        return shippingCityList;
    }
    

}