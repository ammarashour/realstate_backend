import { PsObject } from "./core/PsObject";

export default class ItemColor extends PsObject<ItemColor>{

    id: string = '';
    productId: string = '';
    colorValue: string = '';
    addedDate: string = '';
    addedUserId: string = '';
    updatedDate: string = '';
    updatedUserId: string = '';
    updatedFlag: string = '';

    getPrimaryKey(): string {
        return this.id;
    }

    init(
        id: string,
        productId: string,
        colorValue: string,
        addedDate: string,
        addedUserId: string,
        updatedDate: string,
        updatedUserId: string,
        updatedFlag: string,
       

    ) {
        this.id = id;
        this.productId = productId;
        this.colorValue = colorValue;
        this.addedDate = addedDate;
        this.addedUserId = addedUserId;
        this.updatedDate = updatedDate;
        this.updatedUserId = updatedUserId;
        this.updatedFlag = updatedFlag;
       
        return this;

    }

    fromMap(obj: any) {
        return new ItemColor().init(
         obj.id,
         obj.product_id,
         obj.color_value,
         obj.added_date,
         obj.added_user_id,
         obj.updated_date,
         obj.updated_user_id,
         obj.updated_flag,

        );
    }

    fromMapList(objList : any[] ) : ItemColor[] {
        const itemColor : ItemColor[] = [];
        for(const obj in objList) {
            if(obj != null) {
                itemColor.push(this.fromMap(obj));
            }
        }

        return itemColor;
    }
    
    toMap(object: ItemColor): any {
        const map = {};
        map['id'] = object.id;
        map['product_id'] = object.productId;
        map['color_value'] = object.colorValue;
        map['added_date'] = object.addedDate;
        map['added_user_id'] = object.addedUserId;
        map['updated_date'] = object.updatedDate;
        map['updated_user_id'] = object.updatedUserId;
        map['updated_flag'] = object.updatedFlag;

        return map;
    }
    toMapList(objectList: ItemColor[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }
   
   

}