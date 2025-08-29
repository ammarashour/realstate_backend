import { PsObject } from "./core/PsObject";

export default class ItemLocation extends PsObject<ItemLocation>{

    id : string = '';
    name : string = '';
    lat : string = '';
    lng : string = '';
    status : string = '';
    addedDate : string = '';
    addedDateStr : string = '';

    getPrimaryKey(): string {
        return this.id;
    }

    init(
        id: string,
        name: string,
        lat: string,
        lng: string,
        status: string,
        addedDate: string,
        addedDateStr: string,

    ) {
        this.id = id;
        this.name = name;
        this.lat = lat;
        this.lng = lng;
        this.status = status;
        this.addedDate = addedDate;
        this.addedDateStr = addedDateStr;

        return this;

    }

    fromMap(obj : any) {
        return new ItemLocation().init(
         obj.id,
         obj.name,
         obj.lat,
         obj.lng,
         obj.status,
         obj.added_date,
         obj.added_date_str,
        
        );
    }

    fromMapList(objList : any[] ) : ItemLocation[] {
        const itemLocation : ItemLocation[] = [];
        for(const obj in objList) {
            if(obj != null) {
                itemLocation.push(this.fromMap(obj));
            }
        }

        return itemLocation;
    }

    toMap(object: ItemLocation) : any {
        const map = {};
      
        map['id'] = object.id; 
        map['name'] = object.name; 
        map['lat'] = object.lat; 
        map['lng'] = object.lng; 
        map['status'] = object.status; 
        map['added_date'] = object.addedDate; 
        map['added_date_str'] = object.addedDateStr; 
       
        
        return map;
    }

    toMapList(objectList: ItemLocation[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

}
