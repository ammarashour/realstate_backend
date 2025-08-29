import { PsObject } from "./core/PsObject";

export default class DealOption extends PsObject<DealOption>{

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
        return new DealOption().init(
         obj.id,
         obj.name,
         obj.status,
         obj.added_date,
        
        );
    }


    fromMapList(objList : any[] ) : DealOption[] {
        const dealOptionList : DealOption[] = [];
        for(const obj in objList) {
            if(obj != null) {
                dealOptionList.push(this.fromMap(obj));
            }
        }

        return dealOptionList;
    }


    toMap(object: DealOption): any {
        const map = {};
        map['id'] = object.id;
        map['name'] = object.name;
        map['status'] = object.status;
        map['added_date'] = object.addedDate;
       
        return map;
    }

    toMapList(objectList: DealOption[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    
}