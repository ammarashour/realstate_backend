import { PsObject } from "@templateCore/object/core/PsObject";

export default class CoreKey extends PsObject<CoreKey> {

    coreKeysId : string ='';
    name : string ='';
    description : string ='';
    status : string ='';
    addedDateStr : string ='';

    init(

        coreKeysId : string,
        name : string,
        description : string,
        status : string,
        addedDateStr : string,


    ) {
        this.coreKeysId = coreKeysId;
        this.name = name;
        this.description = description;
        this.status = status;
        this.addedDateStr = addedDateStr;

        return this;

    }

    getPrimaryKey(): string {
        return this.coreKeysId;
    }

    toMap(object: CoreKey): any {
        const map = {};

        map['core_keys_id'] = object.coreKeysId;
        map['name'] = object.name;
        map['description'] = object.description;
        map['status'] = object.status;
        map['added_date_str'] = object.addedDateStr;

        return map;
    }

    toMapList(objectList: CoreKey[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new CoreKey().init(

            obj.core_keys_id,
            obj.name,
            obj.description,
            obj.status,
            obj.added_date_str,

        );
    }

    fromMapList(objList: any[]): CoreKey[] {
        const ratingList: CoreKey[] = [];
        for (const obj in objList) {
            if (obj != null) {
                ratingList.push(this.fromMap(obj));
            }
        }

        return ratingList;
    }
}
