import { PsObject } from "./core/PsObject";
import ShippingZone from "./ShippingZone";

export default class ShippingCost extends PsObject<ShippingCost>{

    shippingZone : ShippingZone = new ShippingZone();
    

    init(
        shippingZone : ShippingZone,
     

    ) {
        this.shippingZone =shippingZone;
        

        return this;

    }

    getPrimaryKey(): string {
        return '';
    }

    toMap(object: ShippingCost): any {
        const map = {};
        map['shipping'] =object.shippingZone;
       

        return map;
    }

    toMapList(objectList: ShippingCost[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new ShippingCost().init(


            obj.shipping,
           

       );
    }   
    fromMapList(objList : any[] ) : ShippingCost[] {
        const shippingCostList : ShippingCost[] = [];
        for(const obj in objList) {
            if(obj != null) {
                shippingCostList.push(this.fromMap(obj));
            }
        }

        return shippingCostList;
    }
    

}