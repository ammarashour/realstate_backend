import { PsObject } from "@templateCore/object/core/PsObject";

export default class VerifyTransaction extends PsObject<VerifyTransaction> {

    status : string ='';
    message : string ='';
    data : Object = {};

    init(

        status : string,
        message : string,
        data : Object


    ) {
        this.status = status;
        this.message = message;
        this.data = data;

        return this;

    }

    getPrimaryKey(): string {
        return '';
    }

    fromMap(obj: any) {
        return new VerifyTransaction().init(

            obj.status,
            obj.message,
            obj.data

        );
    }

    fromMapList(objList: any[]): VerifyTransaction[] {
        const ratingList: VerifyTransaction[] = [];
        for (const obj in objList) {
            if (obj != null) {
                ratingList.push(this.fromMap(obj));
            }
        }

        return ratingList;
    }
}
