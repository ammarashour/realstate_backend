import { PsObject } from "./core/PsObject";

export default class Chat extends PsObject<Chat>{


    itemId : string ='';
    receiverId : string ='';
    senderId : string ='';

    init(
        itemId: string,
        receiverId: string,
        senderId : string,
        
    ) {
        this.itemId =itemId;
        this.receiverId =receiverId;
        this.senderId = senderId;
        return this;

    }

    getPrimaryKey(): string {
        return this.itemId;
    }

    fromMap(obj : any) : Chat {
        return new Chat().init(

         obj.itemId, 
         obj.receiver_id,
         obj.sender_id, 
         
        );
    }

    fromMapList(objList : any[] ) : Chat[] {
        const chat : Chat[] = [];
        for(const obj in objList) {
            if(obj != null) {
                chat.push(this.fromMap(obj));
            }
        }

        return chat;
    }

    toMap(object: Chat) : any {
        const map = {};
        map['itemId'] = object.itemId;
        map['receiver_id'] = object.receiverId;
        map['sender_id'] = object.senderId;
    
        return map;
    }

    toMapList(objectList: Chat[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }
}
