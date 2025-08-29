
import { PsObject } from "./core/PsObject";
import OfflinePayment from "./OfflinePayment";

export default class OfflinePaymentMethod extends PsObject<OfflinePaymentMethod>{


    //id: string = '';
    message: string = '';
    offlinePayment: OfflinePayment[] = [];

    init(
        //id: string,
        message: string,
        offlinePayment: OfflinePayment[],

    ) {
        //this.id = id;
        this.message = message;
        this.offlinePayment = offlinePayment;
        
        return this;

    }

    getPrimaryKey(): string {
        return this.message;
    }

    toMap(object: OfflinePaymentMethod): any {
        const map = {};
       // map['id'] = object.id;
        map['message'] = object.message;
        map['offline_payment'] = new OfflinePayment().toMapList(object.offlinePayment);

        return map;
    }

    toMapList(objectList: OfflinePaymentMethod[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any): OfflinePaymentMethod {
        return new OfflinePaymentMethod().init(

           // obj.id,
            obj.message,
            new OfflinePayment().fromMapList(obj.offline_payment),

        );
    }

    fromMapList(objList: any[]): OfflinePaymentMethod[] {
        const offlinePaymentMethodList: OfflinePaymentMethod[] = [];
        for (const obj in objList) {
            if (obj != null) {
                offlinePaymentMethodList.push(this.fromMap(obj));
            }
        }

        return offlinePaymentMethodList;
    }

}
