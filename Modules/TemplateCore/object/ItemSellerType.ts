import { PsObject } from "./core/PsObject";

export default class SellerType extends PsObject<SellerType>{

    id: string = '';
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
    

    fromMap(obj: any) {
        return new SellerType().init(
         obj.id,
         obj.seller_type,
         obj.status,
         obj.added_date,
         obj.added_user_id,
         obj.updated_date,
         obj.updated_user_id,
         obj.updated_flag,
         obj.added_date_str,

        );
    }

    fromMapList(objList : any[] ) : SellerType[] {
        const sellerType : SellerType[] = [];
        for(const obj in objList) {
            if(obj != null) {
                sellerType.push(this.fromMap(obj));
            }
        }

        return sellerType;
    }
    


    toMap(object: SellerType): any {
        const map = {};
        map['id'] = object.id;
        map['seller_type'] = object.name;
        map['status'] = object.status;
        map['added_date'] = object.addedDate;
        map['added_user_id'] = object.addedUserId;
        map['updated_date'] = object.updatedDate;
        map['updated_user_id'] = object.updatedUserId;
        map['updated_flag'] = object.updatedFlag;
        map['added_date_str'] = object.addedDateStr;

        return map;
    }

    toMapList(objectList: SellerType[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

   

}