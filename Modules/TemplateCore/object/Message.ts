import { PsObject } from "./core/PsObject";

export default class Message extends PsObject<Message>{

    addedDateTimeStamp: Number = 0;
    id: string = '';
    isSold: Boolean = false;
    isUserBought: Boolean = false;
    itemId: string = '';
    message: string = '';
    offerStatus: Number = 0;
    sendByUserId: string = '';
    sessionId: string = '';
    type: Number = 0;

    init(
        addedDateTimeStamp: Number,
        id: string,
        isSold: Boolean,
        isUserBought: Boolean,
        itemId: string,
        message: string,
        offerStatus: Number,
        sendByUserId: string,
        sessionId: string,
        type: Number,


    ) {
        this.addedDateTimeStamp = addedDateTimeStamp;
        this.id = id;
        this.isSold = isSold;
        this.isUserBought = isUserBought;
        this.itemId = itemId;
        this.message = message;
        this.offerStatus = offerStatus;
        this.sendByUserId = sendByUserId;
        this.sessionId = sessionId;
        this.type = type;

        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    fromMap(obj: any) {
        return new Message().init(
            obj.addedDate,
            obj.id,
            obj.isSold,
            obj.isUserBought,
            obj.itemId,
            obj.message,
            obj.offerStatus,
            obj.sendByUserId,
            obj.sessionId,
            obj.type,

        );
    }


    fromMapList(objList: any[]): Message[] {
        const messageList: Message[] = [];
        for (const obj in objList) {
            if (obj != null) {
                messageList.push(this.fromMap(obj));
            }
        }

        return messageList;
    }


    toMap(object: Message): any {
        const map = {};
        map['addedDate'] = object.addedDateTimeStamp;
        map['id'] = object.id;
        map['isSold'] = object.isSold;
        map['isUserBought'] = object.isUserBought;
        map['itemId'] = object.itemId;
        map['message'] = object.message;
        map['offerStatus'] = object.offerStatus;
        map['sendByUserId'] = object.sendByUserId;
        map['sessionId'] = object.sessionId;
        map['type'] = object.type;


        return map;
    }

    toMapList(objectList: Message[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }


}