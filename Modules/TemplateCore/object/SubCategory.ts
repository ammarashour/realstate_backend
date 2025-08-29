import { PsObject } from "./core/PsObject";
import DefaultPhoto from "./DefaultPhoto";
import DefaultIcon from "./DefaultIcon";

export default class SubCategory extends PsObject<SubCategory>{

    id: string = '';
    name: string = '';
    status: string = '';
    addedDate: string = '';
    addedUserId: string = '';
    updatedDate: string = '';
    catId:string ='';
    updatedUserId: string = '';
    updatedFlag: string = '';
    addedDateStreet: string = '';
    isSubScribe: string = '';
    defaultPhoto : DefaultPhoto = new DefaultPhoto();
    defaultIcon: DefaultIcon = new DefaultIcon();

    init(
        id: string,
        name: string,
        status: string,
        addedDate: string,
        addedUserId: string,
        updatedDate: string,
        catId:string,
        updatedUserId: string,
        updatedFlag: string,
        addedDateStreet: string,
        isSubScribe: string,
        defaultPhoto : DefaultPhoto, 
        defaultIcon: DefaultIcon   

    ) {
        this.id = id;
        this.name = name;
        this.status = status;
        this.addedDate = addedDate;
        this.addedUserId = addedUserId;
        this.updatedDate = updatedDate;
        this.catId =catId;
        this.updatedUserId = updatedUserId;
        this.updatedFlag = updatedFlag;
        this.addedDateStreet = addedDateStreet;
        this.isSubScribe = isSubScribe;
        this.defaultPhoto = defaultPhoto;
        this.defaultIcon = defaultIcon;

        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: SubCategory): any {
        const map = {};
        map['id'] = object.id;
        map['name'] = object.name;
        map['status'] = object.status;
        map['added_date'] = object.addedDate;
        map['added_user_id'] = object.addedUserId;
        map['updated_date'] = object.updatedDate;
        map['cat_id'] = object.catId;
        map['updated_user_id'] = object.updatedUserId;
        map['updated_flag'] = object.updatedFlag;
        map['added_date_str'] = object.addedDateStreet;
        map['is_subscribed_fe'] = object.isSubScribe;
        map['default_photo'] = new DefaultPhoto().toMap(object.defaultPhoto);
        map['default_icon'] = new DefaultIcon().toMap(object.defaultIcon);

        return map;
    }

    toMapList(objectList: SubCategory[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    // toMapList(objectList: SubCategory[]) : any[] {
    //     const mapList : any[] = [];
    //     for(let i = 0; i < objectList.length; i++) {
    //         if(objectList[i] != null) {
    //             mapList.push(this.toMap(objectList[i]));
    //         }
    //     }

    //     return mapList;
    // }

    fromMap(obj: any) {
        return new SubCategory().init(
          obj.id,
          obj.name,
          obj.status,
          obj.added_date,
          obj.added_user_id,
          obj.updated_date,
          obj.cat_id,
          obj.updated_user_id,
          obj.updated_flag,
          obj.added_date_str,
          obj.is_subscribed_fe,
          new DefaultPhoto().fromMap(obj.default_photo),
          new DefaultIcon().fromMap(obj.default_icon),

       );
    }   
    // fromMapList(objList : any[] ) : SubCategory[] {
    //     const subCategoryList : SubCategory[] = [];
    //     for(const obj in objList) {
    //         if(obj != null) {
    //             subCategoryList.push(this.fromMap(obj));
    //         }
    //     }

    //     return subCategoryList;
    // }

    fromMapList(objList: any[]): SubCategory[] {
        const subCategoryList: SubCategory[] = [];
        for (const obj of objList as Array<SubCategory>) {
            if (obj != null) {
                subCategoryList.push(this.fromMap(obj));
            }
        }

        return subCategoryList;
    }
    

}