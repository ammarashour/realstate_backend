import { PsObject } from "./core/PsObject";
import OrderHistory from "./OrderHistory";

export default class OrderHistoryList extends PsObject<OrderHistoryList>{

    allOrderCount: string = '';
    pendingOrderCount: string = '';
    approvedOrderCount: string = '';
    deliveringOrderCount: string = '';
    deliveredOrderCount: string = '';
    orderHistory: OrderHistory[] = [new OrderHistory()];
    meta : Object;

    init(
        allOrderCount: string,
        pendingOrderCount: string,
        approvedOrderCount: string,
        deliveringOrderCount: string,
        deliveredOrderCount: string,
        orderHistory: OrderHistory[],
        meta: Object

    ) {
        this.allOrderCount = allOrderCount;
        this.pendingOrderCount = pendingOrderCount;
        this.approvedOrderCount = approvedOrderCount;
        this.deliveringOrderCount = deliveringOrderCount;
        this.deliveredOrderCount = deliveredOrderCount;
        this.orderHistory = orderHistory;
        this.meta = meta;

        return this;

    }

    getPrimaryKey(): string {
        return "";
    }


    fromMap(obj: any) {
        console.log(obj);
        return new OrderHistoryList().init(
         obj.all_order_count,
         obj.pending_order_count,
         obj.approved_order_count,
         obj.delivering_order_count,
         obj.delivered_order_count,
         new OrderHistory().fromMapList(obj.order_history),
         obj.meta
        );
    }

    toMap(object: OrderHistoryList): any {
        const map = {};
        map['all_order_count'] = object.allOrderCount;
        map['pending_order_count'] = object.pendingOrderCount;
        map['approved_order_count'] = object.approvedOrderCount;
        map['delivering_order_count'] = object.deliveringOrderCount;
        map['delivered_order_count'] = object.deliveredOrderCount;
        map['order_history'] = new OrderHistory().toMapList(object.orderHistory);
        map['meta'] = object.meta;

        return map;
    }

    fromMapList(objList: any[]): OrderHistoryList[] {
        const orderList: OrderHistoryList[] = [];
        for (const obj of objList as Array<OrderHistoryList>) {
            if (obj != null) {
                orderList.push(this.fromMap(obj));
            }
        }
        return orderList;
    }
    toMapList(objectList: OrderHistoryList[]) {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }
}
