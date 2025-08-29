import { PsObject } from "./core/PsObject";

export default class Order extends PsObject<Order>{

    orderId: string = '';

    init(
        orderId: string,

    ) {
        this.orderId = orderId;

        return this;

    }

    getPrimaryKey(): string {
        return this.orderId;
    }


    fromMap(obj: any) {
        return new Order().init(
         obj.order_id,
        );
    }

    toMap(object: Order): any {
        const map = {};
        map['order_id'] = object.orderId;

        return map;
    }

    fromMapList(objList: any[]): Order[] {
        const orderList: Order[] = [];
        for (const obj of objList as Array<Order>) {
            if (obj != null) {
                orderList.push(this.fromMap(obj));
            }
        }
        return orderList;
    }
    toMapList(objectList: Order[]) {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }
}