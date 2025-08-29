import { PsObject } from "@templateCore/object/core/PsObject";

export default class Payment extends PsObject<Payment> {

    id : string ='';
    name : string ='';
    description : string ='';
    status : string ='';
    addedDateStr : string ='';

    init(

        id : string,
        name : string,
        description : string,
        status : string,
        addedDateStr : string,


    ) {
        this.id = id;
        this.name = name;
        this.description = description;
        this.status = status;
        this.addedDateStr = addedDateStr;

        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: Payment): any {
        const map = {};

        map['id'] = object.id;
        map['name'] = object.name;
        map['description'] = object.description;
        map['status'] = object.status;
        map['added_date_str'] = object.addedDateStr;

        return map;
    }

    toMapList(objectList: Payment[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new Payment().init(

            obj.id,
            obj.name,
            obj.description,
            obj.status,
            obj.added_date_str,

        );
    }

    fromMapList(objList: any[]): Payment[] {
        const ratingList: Payment[] = [];
        for (const obj in objList) {
            if (obj != null) {
                ratingList.push(this.fromMap(obj));
            }
        }

        return ratingList;
    }
}
