import DefaultPhoto from "./DefaultPhoto";
import { PsObject } from "@templateCore/object/core/PsObject";

export default class ItemInfo extends PsObject<ItemInfo> {

    itemId : string;
    itemName: string = '';
    originalPrice: string = '';
    salePrice: string = '';
    discountPrice: string = '';
    quantity: string = '';
    subTotal: string = '';
    defaultPhoto : DefaultPhoto = new DefaultPhoto();

    init(
        itemId : string,
        itemName: string,
        originalPrice: string,
        salePrice: string,
        discountPrice: string,
        quantity: string,
        subTotal: string,
        defaultPhoto : DefaultPhoto,
    ) {
        this.itemId = itemId;
        this.itemName = itemName;
        this.originalPrice = originalPrice;
        this.salePrice = salePrice;
        this.discountPrice = discountPrice;
        this.quantity = quantity;
        this.subTotal = subTotal;
        this.defaultPhoto = defaultPhoto;
        return this;

    }

    getPrimaryKey(): string {
        return '';
    }

    toMap(object: ItemInfo): any {
        const map = {};
        map['item_id'] = object.itemId;
        map['item_name'] = object.itemName;
        map['original_price'] = object.originalPrice;
        map['sale_price'] = object.salePrice;
        map['discount_price'] = object.discountPrice;
        map['quantity'] = object.quantity;
        map['sub_total'] = object.subTotal;
        map['default_photo'] = new DefaultPhoto().toMap(object.defaultPhoto);
        return map;
    }

    toMapList(objectList: ItemInfo[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }
        return mapList;
    }

    fromMap(obj: any) {
        return new ItemInfo().init(
            obj.item_id,
            obj.item_name,
            obj.original_price,
            obj.sale_price,
            obj.discount_price,
            obj.quantity,
            obj.sub_total,
            new DefaultPhoto().fromMap(obj.default_photo),
        );
    }

    fromMapList(objList: any[]): ItemInfo[] {
        const list: ItemInfo[] = [];
        for (const obj of objList as Array<ItemInfo>) {
            if (obj != null) {
                list.push(this.fromMap(obj));
            }
        }

        return list;
    }
}
