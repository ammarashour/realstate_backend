import { PsObject } from "./core/PsObject";

export default class ApiStatus extends PsObject<ApiStatus>{


    status : string ='';
    message : string ='';

    init(
        status: string,
        message: string,
        
    ) {
        this.status =status;
        this.message =message;
        return this;

    }

    getPrimaryKey(): string {
        return this.status;
    }

    fromMap(obj : any) : ApiStatus {
        return new ApiStatus().init(

         obj.status, 
         obj.message, 
         
        );
    }

    fromMapList(objList : any[] ) : ApiStatus[] {
        const ApiStatusList : ApiStatus[] = [];
        for(const obj in objList) {
            if(obj != null) {
                ApiStatusList.push(this.fromMap(obj));
            }
        }

        return ApiStatusList;
    }

    toMap(object: ApiStatus) : any {
        const map = {};
        map['status'] = object.status;
        map['message'] = object.message;
       
    
        return map;
    }

    toMapList(objectList: ApiStatus[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }
}