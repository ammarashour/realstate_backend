import { PsObject } from "./core/PsObject";

export default class ItemType extends PsObject<ItemType>{

    id: string = '';
    name: string = '';
    status: string = '';
    addedDate: string = '';
    

    init(
        id: string,
        name: string,
        status: string,
        addedDate: string,
        
    ) {
        this.id = id;
        this.name = name;
        this.status = status;
        this.addedDate = addedDate;
        
        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }


    fromMap(obj: any) {
        return new ItemType().init(
         obj.id,
         obj.name,
         obj.status,
         obj.added_date,
        
        );
    }


    fromMapList(objList : any[] ) : ItemType[] {
        const itemType : ItemType[] = [];
        for(const obj in objList) {
            if(obj != null) {
                itemType.push(this.fromMap(obj));
            }
        }

        return itemType;
    }


    toMap(object: ItemType): any {
        const map = {};
        map['id'] = object.id;
        map['name'] = object.name;
        map['status'] = object.status;
        map['added_date'] = object.addedDate;
       
        return map;
    }

    toMapList(objectList: ItemType[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    
}