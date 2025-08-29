import { PsObject } from "./core/PsObject";

export default class ItemLocationTownship extends PsObject<ItemLocationTownship>{

    id : string = '';
    cityId : string = '';
    townshipName : string = '';
    odering : string = '';
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
        cityId: string,
        townshipName : string,
        odering :string,
        lat: string,
        lng: string,
        status: string,
        addedDate: string,
        addedDateStr: string,

    ) {
        this.id = id;
        this.cityId = cityId;
        this.townshipName = townshipName;
        this.odering = odering;
        this.lat = lat;
        this.lng = lng;
        this.status = status;
        this.addedDate = addedDate;
        this.addedDateStr = addedDateStr;

        return this;

    }

    fromMap(obj : any) {
        return new ItemLocationTownship().init(
         obj.id,
         obj.city_id,
         obj.name,
         obj.odering,
         obj.lat,
         obj.lng,
         obj.status,
         obj.added_date,
         obj.added_date_str,
        
        );
    }

    fromMapList(objList : any[] ) : ItemLocationTownship[] {
        const itemLocation : ItemLocationTownship[] = [];
        for(const obj in objList) {
            if(obj != null) {
                itemLocation.push(this.fromMap(obj));
            }
        }

        return itemLocation;
    }

    toMap(object: ItemLocationTownship) : any {
        const map = {};
      
        map['id'] = object.id; 
        map['city_id'] = object.cityId; 
        map['name'] = object.townshipName;
        map['odering'] = object.odering;
        map['lat'] = object.lat; 
        map['lng'] = object.lng; 
        map['status'] = object.status; 
        map['added_date'] = object.addedDate; 
        map['added_date_str'] = object.addedDateStr; 
       
        
        return map;
    }

    toMapList(objectList: ItemLocationTownship[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

}
