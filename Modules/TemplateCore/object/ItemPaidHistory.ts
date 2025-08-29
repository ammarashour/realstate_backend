import { PsObject } from "./core/PsObject";
import Product from "./Product";

export default class ItemPaidHistory extends PsObject<ItemPaidHistory>{

    id: string = '';
    itemId: string = '';
    startDate: string = '';
    endDate: string = '';
    amount: string = '';
    paymentMethod: string = '';
    transCode: string = '';
    status: string = '';
    addedDate: string = '';
    addedUserId: string = '';
    updatedDate: string = '';
    updatedUserId: string = '';
    updatedFlag: string = '';
    addedDateStreet: string = '';
    paidStatus: string = '';
    item: Product = new Product();


    init(
        id: string,
        itemId: string,
        startDate: string,
        endDate: string,
        amount: string,
        paymentMethod: string,
        transCode: string,
        status: string,
        addedDate: string,
        addedUserId: string,
        updatedDate: string,
        updatedUserId: string,
        updatedFlag: string,
        addedDateStreet: string,
        paidStatus: string,
        item: Product,


    ) {
        this.id = id;
        this.itemId = itemId;
        this.startDate = startDate;
        this.endDate = endDate;
        this.amount = amount;
        this.paymentMethod = paymentMethod;
        this.transCode = transCode;
        this.status = status;
        this.addedDate = addedDate;
        this.addedUserId = addedUserId;
        this.updatedDate = updatedDate;
        this.updatedUserId = updatedUserId;
        this.updatedFlag = updatedFlag;
        this.addedDateStreet = addedDateStreet;
        this.paidStatus = paidStatus;
        this.item = item;

        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }


    fromMap(obj: any) {
        return new ItemPaidHistory().init(
            obj.id,
            obj.item_id,
            obj.start_date,
            obj.end_date,
            obj.amount,
            obj.payment_method,
            obj.trans_code,
            obj.status,
            obj.added_date,
            obj.added_user_id,
            obj.updated_date,
            obj.updated_user_id,
            obj.updated_flag,
            obj.added_date_str,
            obj.paid_status,
            new Product().fromMap(obj.item),

        );
    }


    fromMapList(objList: any[]): ItemPaidHistory[] {
        const itemPaidHistoryList: ItemPaidHistory[] = [];
        for (const obj in objList) {
            if (obj != null) {
                itemPaidHistoryList.push(this.fromMap(obj));
            }
        }

        return itemPaidHistoryList;
    }


    toMap(object: ItemPaidHistory): any {
        const map = {};
        map['id'] = object.id;
        map['item_id'] = object.itemId;
        map['start_date'] = object.startDate;
        map['end_date'] = object.endDate;
        map['amount'] = object.amount;
        map['payment_method'] = object.paymentMethod;
        map['trans_code'] = object.transCode;
        map['status'] = object.status;
        map['added_date'] = object.addedDate;
        map['added_user_id'] = object.addedUserId;
        map['updated_date'] = object.updatedDate;
        map['updated_user_id'] = object.updatedUserId;
        map['updated_flag'] = object.updatedFlag;
        map['added_date_str'] = object.addedDateStreet;
        map['paid_status'] = object.paidStatus;
        map['item'] = new Product().toMap(object.item);

        return map;
    }

    toMapList(objectList: ItemPaidHistory[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }


}