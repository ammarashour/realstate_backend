import { PsObject } from "./core/PsObject";

export default class ConditionOfItem extends PsObject<ConditionOfItem>{

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
        return new ConditionOfItem().init(
         obj.id,
         obj.name,
         obj.status,
         obj.added_date,
        
        );
    }


    fromMapList(objList : any[] ) : ConditionOfItem[] {
        const conditionOfItemList : ConditionOfItem[] = [];
        for(const obj in objList) {
            if(obj != null) {
                conditionOfItemList.push(this.fromMap(obj));
            }
        }

        return conditionOfItemList;
    }


    toMap(object: ConditionOfItem): any {
        const map = {};
        map['id'] = object.id;
        map['name'] = object.name;
        map['status'] = object.status;
        map['added_date'] = object.addedDate;
       
        return map;
    }

    toMapList(objectList: ConditionOfItem[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    
}