import { PsObject } from "./core/PsObject";
import User from "./User";

export default class UserLogin extends PsObject<UserLogin>{


    id : string ='';
    login : Boolean = false;
    user   : User = new User();

    init(
        id: string,
        login: Boolean,
        user: User,
        
    ) {
        this.id =id;
        this.login =login;
        this.user = user;
        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    fromMap(obj : any) : UserLogin {
        return new UserLogin().init(

         obj.id, 
         obj.map_key,
         obj.user, 
         
        );
    }

    fromMapList(objList : any[] ) : UserLogin[] {
        const userLoginList : UserLogin[] = [];
        for(const obj in objList) {
            if(obj != null) {
                userLoginList.push(this.fromMap(obj));
            }
        }

        return userLoginList;
    }

    toMap(object: UserLogin) : any {
        const map = {};
        map['id'] = object.id;
        map['map_key'] = object.login;
        map['user'] = new User().toMap(object.user);
    
        return map;
    }

    toMapList(objectList: UserLogin[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }
}
