import { PsObject } from "@templateCore/object/core/PsObject";
import OrderSummaryDetail from "./OrderSummaryDetail";

export default class OrderSummary extends PsObject<OrderSummary> {

    orderSummary: OrderSummaryDetail = new OrderSummaryDetail();

    init(
        orderSummary: OrderSummaryDetail
    ) {
        this.orderSummary = orderSummary;
        return this;
    }

    getPrimaryKey(): string {
        return '';
    }

    toMap(object: OrderSummary): any {
        const map = {};
        map['order_summary'] = new OrderSummaryDetail().toMap(object.orderSummary);
        return map;
    }

    toMapList(objectList: OrderSummary[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new OrderSummary().init(
            new OrderSummaryDetail().fromMap(obj.order_summary),
        );
    }

    fromMapList(objList: any[]): OrderSummary[] {
        const list: OrderSummary[] = [];
        for (const obj of objList as Array<OrderSummary>) {
            if (obj != null) {
                list.push(this.fromMap(obj));
            }
        }
        return list;
    }
}
