import { PsObject } from "./core/PsObject";

export default class State extends PsObject<State>{

    id : string = '';
    name : string = '';    

    init(
      id :string,
      name :string

    ) {
        this.id =id;
        this.name =name;
        
        return this;
    }

    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: State): any {
        const map = {};
        map['id'] =object.id;
        map['name'] =object.name;

        return map;
    }

    toMapList(objectList: State[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new State().init(
            obj.id,
            obj.name
       );
    }   
    fromMapList(objList : any[] ) : State[] {
        const stateList : State[] = [];
        for(const obj in objList) {
            if(obj != null) {
                stateList.push(this.fromMap(obj));
            }
        }

        return stateList;
    }
    

}