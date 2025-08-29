import DefaultIcon from "./DefaultIcon";
import { PsObject } from "@templateCore/object/core/PsObject";
import CustomizeUiDetail from "./CustomizeUiDetail";
import UiType from "./UiType";

export default class CustomFieldDetail extends PsObject<CustomFieldDetail> {

    id: string = '';
    name: string = '';
    placeholder: string = '';
    coreKeysId: string = '';
    uiType: UiType = new UiType();
    mandatory: string = '';
    isVisible: string = '';
    isDelete: string = '';
    moduleName: string = '';
    dataType: string = '';
    isCoreField: string = '';
    addedDate: string = '';
    addedDateStr: string = '';
    customizeUiList: CustomizeUiDetail[] = [new CustomizeUiDetail()];


    init(
        id: string,
        name: string,
        placeholder: string,
        coreKeysId: string,
        uiType: UiType,
        mandatory: string,
        isVisible: string,
        isDelete: string,
        moduleName: string,
        dataType: string,
        isCoreField: string,
        addedDate: string,
        addedDateStr: string,
        customizeUiList: CustomizeUiDetail[],

    ) {
        this.id = id;
        this.name = name;
        this.placeholder = placeholder;
        this.coreKeysId = coreKeysId;
        this.uiType = uiType;
        this.mandatory = mandatory;
        this.isVisible = isVisible;
        this.isDelete = isDelete;
        this.moduleName = moduleName;
        this.dataType = dataType;
        this.isCoreField = isCoreField;
        this.addedDate = addedDate;
        this.addedDateStr = addedDateStr;
        this.customizeUiList = customizeUiList;

        return this;

    }

    getPrimaryKey(): string {
        return 'custom';
    }

    toMap(object: CustomFieldDetail): any {
        const map = {};
        map['id'] = object.id;
        map['name'] = object.name;
        map['placeholder'] = object.placeholder;
        map['core_keys_id'] = object.coreKeysId;
        map['uiType'] = new UiType().toMap(object.uiType);
        map['mandatory'] = object.mandatory;
        map['is_visible'] = object.isVisible;
        map['is_delete'] = object.isDelete;
        map['module_name'] = object.moduleName;
        map['data_type'] = object.dataType;
        map['is_core_field'] = object.isCoreField;
        map['added_date'] = object.addedDate;
        map['added_date_str'] = object.addedDateStr;
        map['customize_ui_details'] = new CustomizeUiDetail().toMapList(object.customizeUiList);

        return map;
    }

    toMapList(objectList: CustomFieldDetail[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new CustomFieldDetail().init(

            obj.id,
            obj.name,
            obj.placeholder,
            obj.core_keys_id,
            new UiType().fromMap(obj.ui_type),
            obj.mandatory,
            obj.is_visible,
            obj.is_delete,
            obj.module_name,
            obj.data_type,
            obj.is_core_field,
            obj.added_date,
            obj.added_date_str,
            new CustomizeUiDetail().fromMapList(obj.customize_ui_details),

        );
    }

    fromMapList(objList: any[]): CustomFieldDetail[] {
        const productList: CustomFieldDetail[] = [];
        for (const obj of objList as Array<CustomFieldDetail>) {
            if (obj != null) {
                productList.push(this.fromMap(obj));
            }
        }

        return productList;
    }
}
