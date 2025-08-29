import { PsObject } from "@templateCore/object/core/PsObject";


export default class SelectedValue extends PsObject<SelectedValue> {

    id : string = '';
    value : string = '';


    init(
        id : string,
        value : string,

    ) {
        this.id = id;
        this.value = value;

        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: SelectedValue): any {
        const map = {};
        map['id'] = object.id;
        map['value'] = object.value;

        return map;
    }

    toMapList(objectList: SelectedValue[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new SelectedValue().init(

            obj.id,
            obj.value,

        );
    }

    fromMapList(objList: any[]): SelectedValue[] {
        const productList: SelectedValue[] = [];
        for (const obj of objList as Array<SelectedValue>) {
            if (obj != null) {
                productList.push(this.fromMap(obj));
            }
        }

        return productList;
    }
}
