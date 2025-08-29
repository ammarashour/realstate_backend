import { PsObject } from "./core/PsObject";

export default class VendorDeliverySetting extends PsObject<VendorDeliverySetting>{

    deliverySetting: string = '';
    deliveryCharges: string = '';

    init(
        deliverySetting: string,
        deliveryCharges: string,

    ) {
        this.deliverySetting = deliverySetting;
        this.deliveryCharges = deliveryCharges;

        return this;

    }

    getPrimaryKey(): string {
        return '';
    }


    fromMap(obj: any) {
        return new VendorDeliverySetting().init(
         obj.delivery_setting,
         obj.delivery_charges,
        );
    }


    fromMapList(objList : any[] ) : VendorDeliverySetting[] {
        const vendorSettingList : VendorDeliverySetting[] = [];
        for(const obj in objList) {
            if(obj != null) {
                vendorSettingList.push(this.fromMap(obj));
            }
        }
        return vendorSettingList;
    }


    toMap(object: VendorDeliverySetting): any {
        const map = {};
        map['delivery_setting'] = object.deliverySetting;
        map['delivery_charges'] = object.deliveryCharges;

        return map;
    }

    toMapList(objectList: VendorDeliverySetting[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }
        return mapList;
    }


}
