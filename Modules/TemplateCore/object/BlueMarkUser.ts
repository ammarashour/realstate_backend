import { PsObject } from "./core/PsObject";

export default class BlueMarkUser extends PsObject<BlueMarkUser>{

    id: string = '';
    note: string = '';
    userId:string ='';

    init(
        id: string,
        note: string,
        userId:string,

    ) {
        this.id = id;
        this.note = note;
        this.userId =userId;

        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: BlueMarkUser): any {
        const map = {};
        map['id'] = object.id;
        map['note'] = object.note;
        map['user_id'] = object.userId;

        return map;
    }

    toMapList(objectList: BlueMarkUser[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new BlueMarkUser().init(


          obj.id,
          obj.name,
          obj.user_id,
 
       );
    }   
    fromMapList(objList : any[] ) : BlueMarkUser[] {
        const blueMarkUserList : BlueMarkUser[] = [];
        for(const obj in objList) {
            if(obj != null) {
                blueMarkUserList.push(this.fromMap(obj));
            }
        }

        return blueMarkUserList;
    }
    

}