import { PsObject } from "./core/PsObject";

export default class DefaultIcon extends PsObject<DefaultIcon>{


    imgId : string = '';
    imgParentId : string = '';
    imgType : string = '';
    imgPath : string = '';
    imgWidth : string = '';
    imgHeight : string = '';
    imgDesc : string = '';
    ordering: string = '';
    addedDateStr: string = '';

    init(
        imgId: string,
        imgParentId: string,
        imgType: string,
        imgPath: string,
        imgWidth: string,
        imgHeight: string,
        imgDesc: string,
        ordering: string,
        addedDateStr: string,

    ) {
        this.imgId =imgId;
        this.imgParentId =imgParentId;
        this.imgType =imgType;
        this.imgPath =imgPath;
        this.imgWidth =imgWidth;
        this.imgHeight =imgHeight;
        this.imgDesc =imgDesc;
        this.ordering = ordering;
        this.addedDateStr = addedDateStr;

        return this;

    }

    getPrimaryKey(): string {
        return this.imgId;
    }

    fromMap(obj : any) {
        if(obj == null || obj == '') {
            return new DefaultIcon();
        }else {
            return new DefaultIcon().init(
            obj.img_id,
            obj.img_parent_id,
            obj.img_type,
            obj.img_path,
            obj.img_width,
            obj.img_height,
            obj.img_desc,
            obj.ordering,
            obj.added_date_str,
            );
        }
    }

    fromMapList(objList : any[] ) : DefaultIcon[] {
        const defaultPhoto : DefaultIcon[] = [];
        for(const obj in objList) {
            if(obj != null) {
                defaultPhoto.push(this.fromMap(obj));
            }
        }

        return defaultPhoto;
    }

    toMap(object: DefaultIcon) : any {
        const map = {};
        map['img_id'] = object.imgId;
        map['img_parent_id'] = object.imgParentId;
        map['img_type'] = object.imgType;
        map['img_path'] = object.imgPath;
        map['img_width'] = object.imgWidth;
        map['img_height'] = object.imgHeight;
        map['img_desc'] = object.imgDesc;
        map['ordering'] = object.ordering;
        map['added_date_str'] = object.addedDateStr;

        return map;
    }

    toMapList(objectList: DefaultIcon[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }
}
