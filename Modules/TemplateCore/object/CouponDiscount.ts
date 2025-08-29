import { PsObject } from "./core/PsObject";

export default class CouponDiscount extends PsObject<CouponDiscount>{

    id : string = '';
    couponName : string = '';
    couponCode : string = '';
    couponAmount : string = '';
    isPublished : string = '';
    addedDate : string = '';
    updatedDate : string = '';
    addedUserId : string = '';
    updatedUserId : string = '';
    addedDateStr : string = '';
    updatedFlag : string = '';

    getPrimaryKey(): string {
        return this.id;
    }

    init(
        id : string ,
        couponName : string ,
        couponCode : string ,
        couponAmount : string ,
        isPublished : string ,
        addedDate : string ,
        updatedDate : string ,
        addedUserId : string ,
        updatedUserId : string ,
        addedDateStr : string ,
        updatedFlag : string ,
       

    ) {
        this.id = id;
        this.couponName = couponName;
        this.couponCode = couponCode;
        this.couponAmount = couponAmount;
        this.isPublished = isPublished;
        this.addedDate = addedDate;
        this.updatedDate = updatedDate;
        this.addedUserId = addedUserId;
        this.updatedUserId = updatedUserId;
        this.addedDateStr = addedDateStr;
        this.updatedFlag = updatedFlag;
       
        return this;

    }

    fromMap(obj: any) {
        return new CouponDiscount().init(
            obj.id,
            obj.coupon_name,
            obj.coupon_code,
            obj.coupon_amount,
            obj.is_published,
            obj.added_date,
            obj.updated_date,
            obj.added_user_id,
            obj.updated_user_id,
            obj.added_date_str,
            obj.updated_flag,

        );
    }

    fromMapList(objList : any[] ) : CouponDiscount[] {
        const couponDiscountList : CouponDiscount[] = [];
        for(const obj in objList) {
            if(obj != null) {
                couponDiscountList.push(this.fromMap(obj));
            }
        }

        return couponDiscountList;
    }
    
    toMap(object: CouponDiscount): any {
        const map = {};
        map['id'] = object.id;
        map['coupon_name'] = object.couponName;
        map['coupon_code'] = object.couponCode;
        map['coupon_amount'] = object.couponAmount;
        map['is_published'] = object.isPublished;
        map['added_date'] = object.addedDate;
        map['updated_date'] = object.updatedDate;
        map['added_user_id'] = object.addedUserId;
        map['updated_user_id'] = object.updatedUserId;
        map['added_date_str'] = object.addedDateStr;
        map['updated_flag'] = object.updatedFlag;

        return map;
    }
    toMapList(objectList: CouponDiscount[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }
   
   

}