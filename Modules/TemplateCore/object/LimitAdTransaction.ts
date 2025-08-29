import { PsObject } from "./core/PsObject";
import User from "./User";
import Package from "./Package";

export default class LimitAdTransaction extends PsObject<LimitAdTransaction>{

    id: string = '';
    userId: string = '';
    packageId: string = '';
    paymentMethod: string = '';
    price: string = '';
    razorId: string = '';
    ispayStack: string = '';
    status: string = '';
    addedDate: string = '';
    user: User = new User();
    package: Package = new Package();
    addedDateStr: string = '';

    init(
        id: string,
        userId: string,
        packageId: string,
        paymentMethod: string,
        price: string,
        razorId: string,
        status: string,
        ispayStack: string,
        addedDate: string,
        user: User,
        psPackage: Package,
        addedDateStr: string,

    ) {
        this.id = id;
        this.userId = userId;
        this.packageId = packageId;
        this.paymentMethod = paymentMethod;
        this.price = price;
        this.razorId = razorId;
        this.ispayStack = ispayStack;
        this.status = status;
        this.addedDate = addedDate;
        this.user = user;
        this.package = psPackage;
        this.addedDateStr = addedDateStr;

        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }


    fromMap(obj: any) {
        return new LimitAdTransaction().init(
         obj.id,
         obj.user_id,
         obj.package_id,
         obj.payment_method,
         obj.price,
         obj.razor_id,
         obj.isPaystack,
         obj.status,
         obj.added_date,
         new User().fromMap(obj.user),
         new Package().fromMap(obj.package),
         obj.added_date_str,
        );
    }


    fromMapList(objList : any[] ) : LimitAdTransaction[] {
        const limitAdTransaction : LimitAdTransaction[] = [];
        for(const obj in objList) {
            if(obj != null) {
                limitAdTransaction.push(this.fromMap(obj));
            }
        }

        return limitAdTransaction;
    }


    toMap(object: LimitAdTransaction): any {
        const map = {};
        map['id'] = object.id;
        map['user_id'] = object.userId;
        map['package_id'] = object.packageId;
        map['payment_method'] = object.paymentMethod;
        map['price'] = object.price;
        map['razor_id'] = object.razorId;
        map['isPaystack'] = object.ispayStack;
        map['status'] = object.status;
        map['added_date'] = object.addedDate;
        map['added_date_str'] = object.addedDateStr;
        map['user'] = new User().toMap(object.user);
        map['package'] = new Package().toMap(object.package);

        return map;
    }

    toMapList(objectList: LimitAdTransaction[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }


}
