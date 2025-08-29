import { PsObject } from "./core/PsObject";

export default class ChatUserPresence extends PsObject<ChatUserPresence>{


        userId : string ='';
        userName : string ='';

    init(
        userId: string,
        userName: string,
        
    ) {
        this.userId =userId;
        this.userName =userName;
        return this;

    }

    getPrimaryKey(): string {
        return this.userId;
    }

    fromMap(obj : any) : ChatUserPresence {
        return new ChatUserPresence().init(

         obj.userId, 
         obj.userName, 
         
        );
    }

    fromMapList(objList : any[] ) : ChatUserPresence[] {
        const chatUserPresence : ChatUserPresence[] = [];
        for(const obj in objList) {
            if(obj != null) {
                chatUserPresence.push(this.fromMap(obj));
            }
        }

        return chatUserPresence;
    }

    toMap(object: ChatUserPresence) : any {
        const map = {};
        map['userId'] = object.userId;
        map['userName'] = object.userName;
       
    
        return map;
    }

    toMapList(objectList: ChatUserPresence[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }
}
