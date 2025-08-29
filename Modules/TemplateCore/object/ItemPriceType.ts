import { PsObject } from "./core/PsObject";

export default class ItemPriceType extends PsObject<ItemPriceType>{

    id: string = '';
    name: string = '';
    status: string = '';
    addedDate: string = '';
    isEmptyObject: string = '';

    init(
        id: string,
        name: string,
        status: string,
        addedDate: string,
        isEmptyObject: string,
    ) {
        this.id = id;
        this.name = name;
        this.status = status;
        this.addedDate = addedDate;
        this.isEmptyObject = isEmptyObject;
        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    fromMap(obj: any) {
        return new ItemPriceType().init(
        obj.id,
        obj.name,
        obj.status,
        obj.added_date,
        obj.is_empty_object,
       );
    }

    fromMapList(objList: any[]): ItemPriceType[] {
        const itemPriceType: ItemPriceType[] = [];
        for (const obj in objList) {
            if (obj != null) {
                itemPriceType.push(this.fromMap(obj));
            }
        }

        return itemPriceType;
    }

    toMap(object: ItemPriceType): any {
        const map = {};
        map['id'] = object.id;
        map['name'] = object.name;
        map['status'] = object.status;
        map['added_date'] = object.addedDate;
        map['is_empty_object'] = object.isEmptyObject;

        return map;
    }

    toMapList(objectList: ItemPriceType[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }


}