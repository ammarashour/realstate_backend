import { PsObject } from "./core/PsObject";

export default class UserInfo extends PsObject<UserInfo>{

    userStatus: string = '';


    init(
        userStatus: string,
      

    ) {
        this.userStatus = userStatus;
     

        return this;

    }

    getPrimaryKey(): string {
        return this.userStatus;
    }

    toMap(object: UserInfo): any {
        const map = {};
        map['user_status'] = object.userStatus;
      

        return map;
    }

    toMapList(objectList: UserInfo[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new UserInfo().init(


          obj.id,
         

       );
    }   
    fromMapList(objList : any[] ) : UserInfo[] {
        const userInfoList : UserInfo[] = [];
        for(const obj in objList) {
            if(obj != null) {
                userInfoList.push(this.fromMap(obj));
            }
        }

        return userInfoList;
    }
    

}