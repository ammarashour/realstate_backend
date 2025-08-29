import { PsObject } from "./core/PsObject";
import ItemInfo from "./ItemInfo";

export default class OrderHistory extends PsObject<OrderHistory> {

    id: string;
    orderCode : string;
    paymentStatus: string = '';
    paymentStatusColor: string = '';
    orderStatus: string = '';
    orderStatusColor: string = '';
    orderDate: string = '';
    vendorName : string = '';
    itemInfo : ItemInfo[] = [new ItemInfo()];
    itemCount : string;
    total : string;

    init(
        id: string,
        orderCode : string,
        paymentStatus: string,
        paymentStatusColor: string,
        orderStatus: string,
        orderStatusColor: string,
        orderDate : string,
        vendorName : string,
        itemInfo : ItemInfo[],
        itemCount : string,
        total : string
    ) {
        this.id = id;
        this.orderCode  = orderCode;
        this.paymentStatus = paymentStatus;
        this.paymentStatusColor = paymentStatusColor;
        this.orderStatus = orderStatus;
        this.orderStatusColor = orderStatusColor;
        this.orderDate  = orderDate;
        this.vendorName  = vendorName;
        this.itemInfo =itemInfo;
        this.itemCount = itemCount;
        this.total = total;
        return this;

    }

    getPrimaryKey(): string {
        return '';
    }

    toMap(object: OrderHistory): any {
        const map = {};
        map['id'] = object.id;
        map['order_code'] = object.orderCode;
        map['payment_status'] = object.paymentStatus;
        map['payment_status_color'] = object.paymentStatusColor;
        map['order_status'] = object.orderStatus;
        map['order_status_color'] = object.orderStatusColor;
        map['order_date'] = object.orderDate;
        map['vendor_name'] = object.vendorName;
        map['item_info'] = new ItemInfo().toMapList(object.itemInfo);
        map['item_count'] = object.itemCount;
        map['total'] = object.total;
        return map;
    }

    toMapList(objectList: OrderHistory[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }
        return mapList;
    }

    fromMap(obj: any) {
        return new OrderHistory().init(
            obj.id,
            obj.order_code,
            obj.payment_status,
            obj.payment_status_color,
            obj.order_status,
            obj.order_status_color,
            obj.order_date,
            obj.vendor_name,
            new ItemInfo().fromMapList(obj.item_info),
            obj.item_count,
            obj.total,
        );
    }

    fromMapList(objList: any[]): OrderHistory[] {
        const list: OrderHistory[] = [];
        for (const obj of objList as Array<OrderHistory>) {
            if (obj != null) {
                list.push(this.fromMap(obj));
            }
        }

        return list;
    }
}
