import { PsObject } from "./core/PsObject";

export default class UserUnReadMessage extends PsObject<UserUnReadMessage>{

    // id : string ='';
    notiUnreadCount : string ='';
    buyerUnreadCount : string ='';
    sellerUnreadCount : string ='';
   

    init(
        // id : string,
        notiUnreadCount : string,
        buyerUnreadCount : string,
        sellerUnreadCount : string,

    ) {
        // this.id =id;
        this.notiUnreadCount =notiUnreadCount;
        this.buyerUnreadCount =buyerUnreadCount;
        this.sellerUnreadCount =sellerUnreadCount;
      

        return this;

    }

    getPrimaryKey(): string {
        return 'this.id';
    }

    toMap(object: UserUnReadMessage): any {
        const map = {};
        // map['id'] = object.id;
        map['noti_unread_count'] = object.notiUnreadCount;
        map['buyer_unread_count'] = object.buyerUnreadCount;
        map['seller_unread_count'] = object.sellerUnreadCount;
        

        return map;
    }

    toMapList(objectList: UserUnReadMessage[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new UserUnReadMessage().init(


            // obj.id,
            obj.noti_unread_count,
            obj.buyer_unread_count,
            obj.seller_unread_count,

       );
    }   
    fromMapList(objList : any[] ) : UserUnReadMessage[] {
        const userUnReadMessageList : UserUnReadMessage[] = [];
        for(const obj in objList) {
            if(obj != null) {
                userUnReadMessageList.push(this.fromMap(obj));
            }
        }

        return userUnReadMessageList;
    }
    

}