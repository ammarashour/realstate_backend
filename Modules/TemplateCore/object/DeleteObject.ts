import { PsObject } from "./core/PsObject";

export default class DeleteObject extends PsObject<DeleteObject>{

    id: string = '';
    typeId: string = '';
    typeName: string = '';
    deletedDate: string = '';
    

    init(
        id: string,
        typeId: string,
        typeName: string,
        deletedDate: string,
        
    ) {
        this.id = id;
        this.typeId = typeId;
        this.typeName = typeName;
        this.deletedDate = deletedDate;
        
        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }


    fromMap(obj: any) {
        return new DeleteObject().init(
         obj.id,
         obj.type_id,
         obj.type_name,
         obj.deleted_date,
        
        );
    }


    fromMapList(objList : any[] ) : DeleteObject[] {
        const deleteObjectList : DeleteObject[] = [];
        for(const obj in objList) {
            if(obj != null) {
                deleteObjectList.push(this.fromMap(obj));
            }
        }

        return deleteObjectList;
    }


    toMap(object: DeleteObject): any {
        const map = {};
        map['id'] = object.id;
        map['type_id'] = object.typeId;
        map['type_name'] = object.typeName;
        map['deleted_date'] = object.deletedDate;
       
        return map;
    }

    toMapList(objectList: DeleteObject[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    
}