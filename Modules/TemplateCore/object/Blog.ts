
import { PsObject } from "@templateCore/object/core/PsObject";
import DefaultPhoto from "./DefaultPhoto";


export default class Blog extends PsObject<Blog> {

    id: string = '';
    name: string = '';
    description: string = '';
    status: string = '';
    addedDate: string = '';
    addedUserId: string = '';
    updatedDate: string = '';
    updatedUserId: string = '';
    addedDateStr: string = '';
    addedUserName: string = '';
    defaultPhoto: DefaultPhoto = new DefaultPhoto();



    init(

        id: string,
        name: string,
        description: string,
        status: string,
        addedDate: string,
        addedUserId: string,
        updatedDate: string,
        updatedUserId: string,
        addedDateStr: string,
        addedUserName: string,
        defaultPhoto: DefaultPhoto,


    ) {
        this.id = id;
        this.name = name;
        this.description = description;
        this.status = status;
        this.addedDate = addedDate;
        this.addedUserId = addedUserId;
        this.updatedDate = updatedDate;
        this.updatedUserId = updatedUserId;
        this.addedDateStr = addedDateStr;
        this.addedUserName = addedUserName;
        this.defaultPhoto = defaultPhoto;


        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: Blog): any {
        const map = {};

        map['id'] = object.id;
        map['name'] = object.name;
        map['description'] = object.description;
        map['status'] = object.status;
        map['added_date'] = object.addedDate;
        map['added_user_id'] = object.addedUserId;
        map['updated_date'] = object.updatedDate;
        map['updated_user_id'] = object.updatedUserId;
        map['added_date_str'] = object.addedDateStr;
        map['added_user_name'] = object.addedUserName;
        map['default_photo'] = new DefaultPhoto().toMap(object.defaultPhoto);



        return map;
    }

    toMapList(objectList: Blog[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new Blog().init(
            obj.id,
            obj.name,
            obj.description,
            obj.status,
            obj.added_date,
            obj.added_user_id,
            obj.updated_date,
            obj.updated_user_id,
            obj.added_date_str,
            obj.added_user_name,
            new DefaultPhoto().fromMap(obj.default_photo),

        );
    }

    fromMapList(objList: any[]): Blog[] {
        const blog: Blog[] = [];
        for (const obj in objList) {
            if (obj != null) {
                blog.push(this.fromMap(obj));
            }
        }

        return blog;
    }
}
