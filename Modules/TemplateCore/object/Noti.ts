
import { PsObject } from "@templateCore/object/core/PsObject";
import DefaultPhoto from "./DefaultPhoto";

export default class Noti extends PsObject<Noti> {

    id : string ='';
    description : string ='';
    message : string ='';
    addedDate : string ='';
    addedUserId : string ='';
    isRead : string ='';
    addedDateStr : string ='';
    type: string ='';
    chatFlag: string ='';
    buyerUserId: string ='';
    sellerUserId: string ='';
    senderName: string ='';
    senderCoverPhoto: string ='';
    itemId: string ='';
    defaultPhoto : DefaultPhoto = new DefaultPhoto();



    init(
        id : string,
        description : string,
        message : string,
        addedDate : string,
        addedUserId : string,
        isRead : string,
        addedDateStr : string,
        type: string,
        chatFlag: string,
        buyerUserId: string,
        sellerUserId: string,
        senderName: string,
        senderCoverPhoto: string,
        itemId: string,
        defaultPhoto : DefaultPhoto,        
        ) {
            this.id = id;
            this.description = description;
            this.message = message;
            this.addedDate = addedDate;
            this.addedUserId = addedUserId;
            this.isRead = isRead;
            this.addedDateStr = addedDateStr;
            this.type = type;
            this.chatFlag = chatFlag;
            this.buyerUserId = buyerUserId;
            this.sellerUserId = sellerUserId;
            this.senderName = senderName;
            this.senderCoverPhoto = senderCoverPhoto;
            this.itemId = itemId;
        this.defaultPhoto = defaultPhoto;
       

        return this;

    }
    
    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: Noti) : any {
        const map = {};

        map['id'] =object.id;
        map['description'] =object.description;
        map['message'] =object.message;
        map['added_date'] =object.addedDate;
        map['added_user_id'] =object.addedUserId;
        map['is_read'] =object.isRead;
        map['added_date_str'] =object.addedDateStr;
        map['type'] =object.type;
        map['chat_flag'] =object.chatFlag;
        map['buyer_user_id'] =object.buyerUserId;
        map['seller_user_id'] =object.sellerUserId;
        map['sender_name'] =object.senderName;
        map['sender_cover_photo'] =object.senderCoverPhoto;
        map['item_id'] =object.itemId;
        map['default_photo'] = new DefaultPhoto().toMap(object.defaultPhoto);

        return map;
    }

    toMapList(objectList: Noti[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj : any) {
        return new Noti().init(

            obj.id,
            obj.description,
            obj.message,
            obj.added_date,
            obj.added_user_id,
            obj.is_read,
            obj.added_date_str,
            obj.type,
            obj.chat_flag,
            obj.buyer_user_id,
            obj.seller_user_id,
            obj.sender_name,
            obj.sender_cover_photo,
            obj.item_id,
            new DefaultPhoto().fromMap(obj.default_photo),
        );        
    }

    fromMapList(objList : any[] ) : Noti[] {
        const notiList : Noti[] = [];
        for(const obj in objList) {
            if(obj != null) {
                notiList.push(this.fromMap(obj));
            }
        }

        return notiList;
    }
}
