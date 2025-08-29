import { PsObject } from "./core/PsObject";

export default class ItemSpec extends PsObject<ItemSpec>{

    id: string = '';
    productId: string ='';
    name: string = '';
    description: string = '';
    addedDate: string = '';
    addedUserId: string = '';
    updatedDate: string = '';
    updatedUserId: string = '';
    updatedFlag: string = '';

    init(
        id: string,
        productId : string,
        name: string,
        description: string,
        addedDate: string,
        addedUserId: string,
        updatedDate: string,
        updatedUserId: string,
        updatedFlag: string,
        

    ) {
        this.id = id;
        this.productId =productId;
        this.name = name;
        this.description = description;
        this.addedDate = addedDate;
        this.addedUserId = addedUserId;
        this.updatedDate = updatedDate;
        this.updatedUserId = updatedUserId;
        this.updatedFlag = updatedFlag;
      
        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    fromMap(obj: any) {
        return new ItemSpec().init(
         obj.id,
         obj.name,
         obj.description,
         obj.added_date,
         obj.added_user_id,
         obj.updated_date,
         obj.updated_user_id,
         obj.updated_flag,
         obj.product_id,

        );
    }


    fromMapList(objList : any[] ) : ItemSpec[] {
        const itemSpecList : ItemSpec[] = [];
        for(const obj in objList) {
            if(obj != null) {
                itemSpecList.push(this.fromMap(obj));
            }
        }

        return itemSpecList;
    }


    toMap(object: ItemSpec): any {
        const map = {};
        map['id'] = object.id;
        map['product_id'] = object.productId;
        map['name'] = object.name;
        map['description'] = object.description;
        map['added_date'] = object.addedDate;
        map['added_user_id'] = object.addedUserId;
        map['updated_date'] = object.updatedDate;
        map['updated_user_id'] = object.updatedUserId;
        map['updated_flag'] = object.updatedFlag;


        return map;
    }

    toMapList(objectList: ItemSpec[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    
}