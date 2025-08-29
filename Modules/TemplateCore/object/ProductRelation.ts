import { PsObject } from "@templateCore/object/core/PsObject";
import SelectedValue from "./SelectedValue";


export default class ProductRelation extends PsObject<ProductRelation> {

    id : string = '';
    itemId : string = '';
    coreKeysId : string = '';
    coreKeyName : string = '';
    isVisible : string = '';
    isDelete : string = '';
    value : string = '';
    uiTypeId : string = '';
    addedDateStr : string = '';
    selectedValue : SelectedValue[] = [
        new SelectedValue()
    ];


    init(
        id : string,
        itemId : string,
        coreKeysId : string,
        coreKeyName : string,
        isVisible : string,
        isDelete : string,
        value : string,
        uiTypeId : string,
        addedDateStr : string,
        selectedValue : SelectedValue[],

    ) {
        this.id = id;
        this.itemId = itemId;
        this.coreKeysId = coreKeysId;
        this.coreKeyName = coreKeyName;
        this.isVisible = isVisible;
        this.isDelete = isDelete;
        this.value = value;
        this.uiTypeId = uiTypeId;
        this.addedDateStr = addedDateStr;
        this.selectedValue = selectedValue;

        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: ProductRelation): any {
        const map = {};
        map['id'] = object.id;
        map['item_id'] = object.itemId;
        map['core_keys_id'] = object.coreKeysId;
        map['core_key_name'] = object.coreKeyName;
        map['isVisible'] = object.isVisible;
        map['isDelete'] = object.isDelete;
        map['value'] = object.value;
        map['ui_type_id'] = object.uiTypeId;
        map['added_date_str'] = object.addedDateStr;
        map['selectedValue'] = new SelectedValue().toMapList(object.selectedValue);

        return map;
    }

    toMapList(objectList: ProductRelation[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new ProductRelation().init(

            obj.id,
            obj.item_id,
            obj.core_keys_id,
            obj.core_key_name,
            obj.isVisible,
            obj.isDelete,
            obj.value,
            obj.ui_type_id,
            obj.added_date_str,
            new SelectedValue().fromMapList(obj.selectedValue),


        );
    }

    fromMapList(objList: any[]): ProductRelation[] {
        const productList: ProductRelation[] = [];
        for (const obj of objList as Array<ProductRelation>) {
            if (obj != null) {
                productList.push(this.fromMap(obj));
            }
        }

        return productList;
    }
}
