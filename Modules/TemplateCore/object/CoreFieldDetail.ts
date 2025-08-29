import { PsObject } from "@templateCore/object/core/PsObject";


export default class CoreFieldDetail extends PsObject<CoreFieldDetail> {

    id : string = '';
    fieldName : string = '';
    labelName : string = '';
    placeholder : string = '';
    dataType : string = '';
    isCoreField : string = '';
    isVisible : string = '';
    mandatory : string = '';
    addedDate : string  ='';
    addedDateStr : string = '';

    init(
        id : string,
        fieldName : string,
        labelName : string,
        placeholder : string,
        dataType : string,
        isCoreField : string,
        isVisible : string,
        mandatory : string,
        addedDate : string,
        addedDateStr  : string,
     
    ) {
        this.id = id;
        this.fieldName = fieldName;
        this.labelName = labelName;
        this.placeholder = placeholder;
        this.dataType = dataType;
        this.isCoreField = isCoreField;
        this.isVisible = isVisible;
        this.mandatory = mandatory;
        this.addedDate = addedDate;
        this.addedDateStr  = addedDateStr;

        return this;

    }

    getPrimaryKey(): string {
        return 'custom';
    }

    toMap(object: CoreFieldDetail): any {
        const map = {};
        map['id'] = object.id;
        map['field_name'] = object.fieldName;
        map['label_name'] = object.labelName;
        map['placeholder'] = object.placeholder;
        map['data_type'] = object.dataType;
        map['is_core_field'] = object.isCoreField;
        map['is_visible'] = object.isVisible;
        map['mandatory'] = object.mandatory;
        map['added_date'] = object.addedDate;
        map['added_date_str'] = object.addedDateStr;

        return map;
    }

    toMapList(objectList: CoreFieldDetail[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new CoreFieldDetail().init(

            obj.id,
            obj.field_name,
            obj.label_name,
            obj.placeholder,
            obj.data_type,
            obj.is_core_field,
            obj.is_visible,
            obj.mandatory,
            
            obj.added_date,
            obj.added_date_str,
           
        );
    }

    fromMapList(objList: any[]): CoreFieldDetail[] {
        const productList: CoreFieldDetail[] = [];
        for (const obj of objList as Array<CoreFieldDetail>) {
            if (obj != null) {
                productList.push(this.fromMap(obj));
            }
        }

        return productList;
    }
}
