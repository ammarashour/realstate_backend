
import { PsObject } from "@templateCore/object/core/PsObject";
import DefaultPhoto from "./DefaultPhoto";

export default class ReportedItem extends PsObject<ReportedItem> {

    id : string = '';
    title : string = '';
    status : string = '';
    addedDate : string = '';
    defaultPhoto : DefaultPhoto = new DefaultPhoto();



    init(
        id : string,
        title : string,
        status : string,
        addedDate : string,
        defaultPhoto : DefaultPhoto,        
        ) {
        this.id = id;
        this.title = title;
        this.status = status;
        this.addedDate = addedDate;
        this.defaultPhoto = defaultPhoto;
       

        return this;

    }
    
    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: ReportedItem) : any {
        const map = {};

        map['id'] = object.id;
        map['title'] = object.title;
        map['status'] = object.status;
        map['added_date'] = object.addedDate;
        map['default_photo'] = new DefaultPhoto().toMap(object.defaultPhoto);

        return map;
    }

    toMapList(objectList: ReportedItem[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj : any) {
        return new ReportedItem().init(

        obj.id,
        obj.title,
        obj.status,
        obj.added_date,
        new DefaultPhoto().fromMap(obj.default_photo),
        );        
    }

    fromMapList(objList : any[] ) : ReportedItem[] {
        const reportedItemList : ReportedItem[] = [];
        for(const obj in objList) {
            if(obj != null) {
                reportedItemList.push(this.fromMap(obj));
            }
        }

        return reportedItemList;
    }
}
