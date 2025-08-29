
import { PsObject } from "@templateCore/object/core/PsObject";
import DefaultPhoto from "./DefaultPhoto";


export default class Bunner extends PsObject<Bunner> {

    id: string = '';
    name: string = '';
    status: string = '';
    addedDate: string = '';
    addedUserId: string = '';
    updatedDate: string = '';
    updatedUserId: string = '';
    addedDateStr: string = '';
    updatedFlag: string = '';
    defaultPhoto: DefaultPhoto = new DefaultPhoto();
    


    init(

        id: string,
        name: string,
        status: string,
        addedDate: string,
        addedUserId: string,
        updatedDate: string,
        updatedUserId: string,
        addedDateStr: string,
        updatedFlag: string,
        defaultPhoto: DefaultPhoto,
        

    ) {
        this.id = id;
        this.name = name;
        this.status = status;
        this.addedDate = addedDate;
        this.addedUserId = addedUserId;
        this.updatedDate = updatedDate;
        this.updatedUserId = updatedUserId;
        this.addedDateStr = addedDateStr;
        this.updatedFlag = updatedFlag;
        this.defaultPhoto = defaultPhoto;
        

        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: Bunner): any {
        const map = {};

        map['id'] = object.id;
        map['name'] = object.name;
        map['status'] = object.status;
        map['added_date'] = object.addedDate;
        map['added_user_id'] = object.addedUserId;
        map['updated_date'] = object.updatedDate;
        map['updated_user_id'] = object.updatedUserId;
        map['added_date_str'] = object.addedDateStr;
        map['updated_flag'] = object.updatedFlag;
        map['default_photo'] = new DefaultPhoto().toMap(object.defaultPhoto);
       


        return map;
    }

    toMapList(objectList: Bunner[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new Bunner().init(
            obj.id,
            obj.name,
            obj.status,
            obj.added_date,
            obj.added_user_id,
            obj.updated_date,
            obj.updated_user_id,
            obj.added_date_str,
            obj.updated_flag,
            new DefaultPhoto().fromMap(obj.default_photo),
            
        );
    }

    fromMapList(objList: any[]): Bunner[] {
        const bunner: Bunner[] = [];
        for (const obj in objList) {
            if (obj != null) {
                bunner.push(this.fromMap(obj));
            }
        }

        return bunner;
    }
}
