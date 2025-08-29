import { PsObject } from "./core/PsObject";

export default class OrderSummaryObj extends PsObject<OrderSummaryObj>{

    itemId: string = '';
    quantity: string = '';
    originalPrice: string = '';
    salePrice: string = '';
    subTotal: string = '';
    discountPrice: string = '';

    init(
        itemId: string,
        quantity: string ,
        originalPrice: string ,
        salePrice: string ,
        subTotal: string ,
        discountPrice: string ,

    ) {
        this.itemId = itemId;
        this.quantity = quantity;
        this.originalPrice = originalPrice;
        this.salePrice = salePrice;
        this.subTotal = subTotal;
        this.discountPrice = discountPrice;

        return this;

    }

    getPrimaryKey(): string {
        return '';
    }


    fromMap(obj: any) {
        return new OrderSummaryObj().init(
         obj.item_id,
         obj.quantity,
         obj.original_price,
         obj.sale_price,
         obj.sub_total,
         obj.discount_price
        );
    }

    toMap(object: OrderSummaryObj): any {
        const map = {};
        map['item_id'] = object.itemId;
        map['quantity'] = object.quantity;
        map['original_price'] = object.originalPrice;
        map['sale_price'] = object.salePrice;
        map['sub_total'] = object.subTotal;
        map['discount_price'] = object.discountPrice;

        return map;
    }

    fromMapList(objList: any[]): OrderSummaryObj[] {
        const OrderSummaryObjList: OrderSummaryObj[] = [];
        for (const obj of objList as Array<OrderSummaryObj>) {
            if (obj != null) {
                OrderSummaryObjList.push(this.fromMap(obj));
            }
        }
        return OrderSummaryObjList;
    }
    toMapList(objectList: OrderSummaryObj[]) {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }
}