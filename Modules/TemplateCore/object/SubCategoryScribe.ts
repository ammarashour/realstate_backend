import { PsObject } from "./core/PsObject";

export default class SubCategoryScribe extends PsObject<SubCategoryScribe>{
    userId: string = '';
    catId: string = '';
    subCatIds: string[] = [];

    init(
        userId: string,
        catId: string,
        subCatIds: string[]

    ) {
        this.userId = userId;
        this.catId = catId;
        this.subCatIds = subCatIds;

        return this;

    }

    getPrimaryKey(): string {
        return '';
    }

    toMap(object: SubCategoryScribe): any {
        const map = {};
        map['user_id'] = object.userId;
        map['cat_id'] = object.catId;
        map['sub_cat_ids'] = object.subCatIds;
        
        return map;
    }

    toMapList(objectList: SubCategoryScribe[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new SubCategoryScribe().init(

            obj.user_id,
            obj.cat_id,
            obj.sub_cat_ids,

       );
    }   
    fromMapList(objList : any[] ) : SubCategoryScribe[] {
        const subCategoryList : SubCategoryScribe[] = [];
        for(const obj in objList) {
            if(obj != null) {
                subCategoryList.push(this.fromMap(obj));
            }
        }

        return subCategoryList;
    }
}