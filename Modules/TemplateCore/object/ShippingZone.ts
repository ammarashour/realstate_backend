import { PsObject } from "./core/PsObject";

export default class ShippingZone extends PsObject<ShippingZone>{

    id : string = '';
    shippingZonePackageName : string = '';
    shippingCost : string = '';

    init(
      id :string,
      shippingZonePackageName :string,
      shippingCost :string,

    ) {
        this.id =id;
        this.shippingZonePackageName =shippingZonePackageName;
        this.shippingCost =shippingCost;

        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: ShippingZone): any {
        const map = {};
        map['id'] =object.id;
        map['shipping_zone_package_name'] =object.shippingZonePackageName;
        map['shipping_cost'] =object.shippingCost;

        return map;
    }

    toMapList(objectList: ShippingZone[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new ShippingZone().init(

            obj.id,
            obj.shipping_zone_package_name,
            obj.shipping_cost, 

       );
    }   
    fromMapList(objList : any[] ) : ShippingZone[] {
        const shippingZoneList : ShippingZone[] = [];
        for(const obj in objList) {
            if(obj != null) {
                shippingZoneList.push(this.fromMap(obj));
            }
        }

        return shippingZoneList;
    }
    

}