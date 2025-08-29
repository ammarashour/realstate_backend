
import { PsObject } from "@templateCore/object/core/PsObject";
import Product from "./Product";
import User from "./User";

export default class Offer extends PsObject<Offer> {

    id : string = '';
    itemId : string = '';
    buyerUserId : string = '';
    sellerUserId : string = '';
    negoPrice : string = '';
    buyerUnreadCount : string = '';
    sellerUnreadCount : string = '';
    isAccept : string = '';
    addedDate : string = '';
    isOffer : string = '';
    offerStatus : string = '';
    offerAmount : string = '';
    addedDateStr : string = '';
    item : Product = new Product();
    buyer   : User = new User();
    seller   : User = new User();
    


    init(

        id : string,
        itemId : string,
        buyerUserId : string,
        sellerUserId : string,
        negoPrice : string,
        buyerUnreadCount : string,
        sellerUnreadCount : string,
        isAccept : string,
        addedDate : string,
        isOffer : string,
        offerStatus : string,
        offerAmount : string,
        addedDateStr : string,    
        item: Product,
        buyer: User,
        seller: User
        

    ) {
        this.id = id;
        this.itemId = itemId;
        this.buyerUserId = buyerUserId;
        this.sellerUserId = sellerUserId;
        this.negoPrice = negoPrice;
        this.buyerUnreadCount = buyerUnreadCount;
        this.sellerUnreadCount = sellerUnreadCount;
        this.isAccept = isAccept;
        this.addedDate = addedDate;
        this.isOffer = isOffer;
        this.offerStatus = offerStatus;
        this.offerAmount = offerAmount;
        this.addedDateStr = addedDateStr;
        this.item = item;
        this.buyer =buyer;
        this.seller=seller;

        

        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: Offer): any {
        const map = {};

        map['id'] = object.id;
        map['item_id'] = object.itemId;
        map['buyer_user_id'] = object.buyerUserId;
        map['seller_user_id'] = object.sellerUserId;
        map['nego_price'] = object.negoPrice;
        map['buyer_unread_count'] = object.buyerUnreadCount;
        map['seller_unread_count'] = object.sellerUnreadCount;
        map['is_accept'] = object.isAccept;
        map['added_date'] = object.addedDate;
        map['is_offer'] = object.isOffer;
        map['offer_status'] = object.offerStatus;
        map['offer_amount'] = object.offerAmount;
        map['added_date_str'] = object.addedDateStr;
        map['item'] = new Product().toMap(object.item);
        map['buyer'] = new User().toMap(object.buyer);
        map['seller'] = new User().toMap(object.seller);


        return map;
    }

    toMapList(objectList: Offer[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new Offer().init(

            obj.id,
            obj.item_id,
            obj.buyer_user_id,
            obj.seller_user_id,
            obj.nego_price,
            obj.buyer_unread_count,
            obj.seller_unread_count,
            obj.is_accept,
            obj.added_date,
            obj.is_offer,
            obj.offer_status,
            obj.offer_amount,
            obj.added_date_str,
            new Product().fromMap(obj.item),
            new User().fromMap(obj.buyer),
            new User().fromMap(obj.seller),
            
        );
    }

    fromMapList(objList: any[]): Offer[] {
        const offerList: Offer[] = [];
        for (const obj in objList) {
            if (obj != null) {
                offerList.push(this.fromMap(obj));
            }
        }

        return offerList;
    }
}
