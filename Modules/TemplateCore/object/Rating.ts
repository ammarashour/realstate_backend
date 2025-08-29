
import { PsObject } from "@templateCore/object/core/PsObject";
import User from "./User";

export default class Rating extends PsObject<Rating> {

    id : string ='';
    fromUserId : string ='';
    toUserId : string ='';
    rating : string ='';
    title : string ='';
    description : string ='';
    addedDate : string ='';
    addedDateStr : string =''; 
    fromUser   : User = new User();
    toUser   : User = new User();
    


    init(

        id: string,
        fromUserId: string,
        toUserId: string,
        rating: string,
        title: string,
        description: string,
        addedDate: string,
        addedDateStr: string,
        fromUser: User,
        toUser: User
        

    ) {
        this.id = id;
        this.fromUserId = fromUserId;
        this.toUserId = toUserId;
        this.rating = rating;
        this.title = title;
        this.description = description;
        this.addedDate = addedDate;
        this.addedDateStr = addedDateStr;
        this.fromUser =fromUser;
        this.toUser=toUser;

        

        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: Rating): any {
        const map = {};

        map['id'] = object.id;
        map['from_user_id'] = object.fromUserId;
        map['to_user_id'] = object.toUserId;
        map['rating'] = object.rating;
        map['title'] = object.title;
        map['description'] = object.description;
        map['added_date'] = object.addedDate;
        map['added_date_str'] = object.addedDateStr;
        map['user'] = new User().toMap(object.fromUser);
        map['user'] = new User().toMap(object.toUser);


        return map;
    }

    toMapList(objectList: Rating[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new Rating().init(

            obj.id,
            obj.from_user_id,
            obj.to_user_id,
            obj.rating,
            obj.title,
            obj.description,
            obj.added_date,
            obj.added_date_str,
            new User().fromMap(obj.from_user),
            new User().fromMap(obj.to_user),
            
        );
    }

    fromMapList(objList: any[]): Rating[] {
        const ratingList: Rating[] = [];
        for (const obj in objList) {
            if (obj != null) {
                ratingList.push(this.fromMap(obj));
            }
        }

        return ratingList;
    }
}
