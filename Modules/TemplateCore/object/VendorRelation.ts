import { PsObject } from "@templateCore/object/core/PsObject";
import SelectedValue from "./SelectedValue";


export default class VendorRelation extends PsObject<VendorRelation> {

    id : string = '';
    vendorId : string = '';
    coreKeysId : string = '';
    coreKeyName : string = '';
    isVisible : string = '';
    value : string = '';
    uiTypeId : string = '';
    addedDateStr : string = '';
    selectedValue : SelectedValue[] = [
        new SelectedValue()
    ];


    init(
        id : string,
        vendorId : string,
        coreKeysId : string,
        coreKeyName : string,
        isVisible : string,
        value : string,
        uiTypeId : string,
        addedDateStr : string,
        selectedValue : SelectedValue[],

    ) {
        this.id = id;
        this.vendorId = vendorId;
        this.coreKeysId = coreKeysId;
        this.coreKeyName = coreKeyName;
        this.isVisible = isVisible;
        this.value = value;
        this.uiTypeId = uiTypeId;
        this.addedDateStr = addedDateStr;
        this.selectedValue = selectedValue;

        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: VendorRelation): any {
        const map = {};
        map['id'] = object.id;
        map['vendor_id'] = object.vendorId;
        map['core_keys_id'] = object.coreKeysId;
        map['core_key_name'] = object.coreKeyName;
        map['isVisible'] = object.isVisible;
        map['value'] = object.value;
        map['ui_type_id'] = object.uiTypeId;
        map['added_date_str'] = object.addedDateStr;
        map['selectedValue'] = new SelectedValue().toMapList(object.selectedValue);

        return map;
    }

    toMapList(objectList: VendorRelation[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new VendorRelation().init(

            obj.id,
            obj.vendor_id,
            obj.core_keys_id,
            obj.core_key_name,
            obj.isVisible,
            obj.value,
            obj.ui_type_id,
            obj.added_date_str,
            new SelectedValue().fromMapList(obj.selectedValue),


        );
    }

    fromMapList(objList: any[]): VendorRelation[] {
        const productList: VendorRelation[] = [];
        for (const obj of objList as Array<VendorRelation>) {
            if (obj != null) {
                productList.push(this.fromMap(obj));
            }
        }

        return productList;
    }
}
