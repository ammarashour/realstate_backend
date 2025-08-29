import DefaultIcon from "./DefaultIcon";
import { PsObject } from "@templateCore/object/core/PsObject";

export default class OfflinePayment extends PsObject<OfflinePayment> {

    id: string = '';
    shopId: string = '';
    title: string = '';
    description: string = '';
    addedDate: string = '';
    addedUserId: string = '';
    updatedDate: string = '';
    updatedUserId: string = '';
    defaultIcon: DefaultIcon = new DefaultIcon();
    addedDateStr: string = '';

    init(

        id: string,
        shopId: string,
        title: string,
        description: string,
        addedDate: string,
        addedUserId: string,
        updatedDate: string,
        updatedUserId: string,
        defaultIcon: DefaultIcon,
        addedDateStr: string,
    ) {
        this.id = id;
        this.shopId = shopId;
        this.title = title;
        this.description = description;
        this.addedDate = addedDate;
        this.addedUserId = addedUserId;
        this.updatedDate = updatedDate;
        this.updatedUserId = updatedUserId;
        this.defaultIcon = defaultIcon;
        this.addedDateStr = addedDateStr;


        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }


    toMap(object: OfflinePayment): any {
        const map = {};

        map['id'] = object.id;
        map['shop_id'] = object.shopId;
        map['title'] = object.title;
        map['description'] = object.description;
        map['added_date'] = object.addedDate;
        map['added_user_id'] = object.addedUserId;
        map['updated_date'] = object.updatedDate;
        map['updated_user_id'] = object.updatedUserId;
        map['default_icon'] = new DefaultIcon().toMap(object.defaultIcon);
        map['added_date_str'] = object.addedDateStr;

        return map;
    }

    toMapList(objectList: OfflinePayment[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any)  {
        return new OfflinePayment().init(
            obj.id,
            obj.shop_id,
            obj.title,
            obj.description,
            obj.added_date,
            obj.added_user_id,
            obj.updated_date,
            obj.updated_user_id,
            new DefaultIcon().fromMap(obj.default_icon),
            obj.added_date_str,
        );
    }

    fromMapList(objList: any[]): OfflinePayment[] {
        const offline: OfflinePayment[] = [];
        for (let i = 0; i < objList.length; i++) {

            if (objList[i] != null) {
                offline.push(this.fromMap(objList[i]));
            }

        }

        return offline;
    }
}
