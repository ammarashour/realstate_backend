import { PsObject } from "./core/PsObject";

export default class ValidationStatus extends PsObject<ValidationStatus>{


    status : string ='';
    message : Object ={};

    init(
        status: string,
        message: Object,

    ) {
        this.status =status;
        this.message =message;
        return this;

    }

    getPrimaryKey(): string {
        return this.status;
    }

    fromMap(obj : any) : ValidationStatus {
        return new ValidationStatus().init(

         obj.status,
         obj.message,

        );
    }

    fromMapList(objList : any[] ) : ValidationStatus[] {
        const ValidationStatusList : ValidationStatus[] = [];
        for(const obj in objList) {
            if(obj != null) {
                ValidationStatusList.push(this.fromMap(obj));
            }
        }

        return ValidationStatusList;
    }

    toMap(object: ValidationStatus) : any {
        const map = {};
        map['status'] = object.status;
        map['message'] = object.message;


        return map;
    }

    toMapList(objectList: ValidationStatus[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }
}
