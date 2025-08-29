import { PsObject } from "./core/PsObject";

export default class PsAppVersion extends PsObject<PsAppVersion>{

    versionNo : string = '';
    versionForceUpdate : string = '';
    versionTitle : string = '';
    versionMessage : string = '';
    versionNeedClearData : string = '';
   

    init(
        versionNo : string,
        versionForceUpdate : string,
        versionTitle : string,
        versionMessage : string,
        versionNeedClearData : string,
       

    ) {
        this.versionNo = versionNo;
        this.versionForceUpdate = versionForceUpdate;
        this.versionTitle = versionTitle;
        this.versionMessage = versionMessage;
        this.versionNeedClearData = versionNeedClearData;
      

        return this;

    }

    getPrimaryKey(): string {
        return this.versionNo;
    }

    toMap(object: PsAppVersion): any {
        const map = {};
        map['version_no'] = object.versionNo;
        map['version_force_update'] = object.versionForceUpdate;
        map['version_title'] = object.versionTitle;
        map['version_message'] = object.versionMessage;
        map['version_need_clear_data'] = object.versionNeedClearData;
        

        return map;
    }

    toMapList(objectList: PsAppVersion[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new PsAppVersion().init(


            obj.version_no,
            obj.version_force_update,
            obj.version_title,
            obj.version_message,
            obj.version_need_clear_data,

       );
    }   
    fromMapList(objList : any[] ) : PsAppVersion[] {
        const psAppVersionList : PsAppVersion[] = [];
        for(const obj in objList) {
            if(obj != null) {
                psAppVersionList.push(this.fromMap(obj));
            }
        }

        return psAppVersionList;
    }
    

}